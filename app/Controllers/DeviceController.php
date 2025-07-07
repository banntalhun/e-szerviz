<?php
// app/Controllers/DeviceController.php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Device;
use Models\Customer;

class DeviceController extends Controller {
   
   public function index(): void {
       $this->requireAuth();
       $this->requirePermission('device.view');
       
       $deviceModel = new Device();
       
       $page = (int) ($_GET['page'] ?? 1);
       $search = $_GET['search'] ?? '';
       $customerId = $_GET['customer_id'] ?? null;
       
       $conditions = [];
       if ($customerId) {
           $conditions['customer_id'] = $customerId;
       }
       
       if (!empty($search)) {
           $devices = $deviceModel->search($search, ['name', 'serial_number'], $conditions);
           $pagination = [
               'items' => $devices,
               'total' => count($devices),
               'current_page' => 1,
               'last_page' => 1
           ];
       } else {
           $pagination = $deviceModel->paginate($page, null, $conditions);
       }
       
       // További adatok betöltése - JAVÍTVA!
       foreach ($pagination['items'] as &$device) {
           // Ügyfél adatok
           $customerModel = new Customer();
           $device['customer'] = $customerModel->find($device['customer_id']);
           
           // Állapot adatok
           $device['condition'] = $this->db->fetchOne(
               "SELECT * FROM " . DB_PREFIX . "device_conditions WHERE id = ?",
               [$device['condition_id']]
           );
           
           // Egyéb statisztikák
           $device['repair_count'] = $deviceModel->getRepairCount($device['id']);
           $device['total_repair_cost'] = $deviceModel->getTotalRepairCost($device['id']);
       }
       
       $this->view->render('devices/index', [
           'pagination' => $pagination,
           'search' => $search,
           'customerId' => $customerId
       ]);
   }
   
   public function create(): void {
       $this->requireAuth();
       $this->requirePermission('device.create');
       
       $customerId = $_GET['customer_id'] ?? null;
       $customer = null;
       
       // Customer model példány
       $customerModel = new Customer();
       
       // Ha van customer_id, betöltjük azt az ügyfelet
       if ($customerId) {
           $customer = $customerModel->find($customerId);
       }
       
       // ÖSSZES ügyfél lekérdezése közvetlen SQL-lel
       $customers = $this->db->fetchAll(
           "SELECT * FROM " . DB_PREFIX . "customers ORDER BY name"
       );
       
       // Eszköz állapotok
       $deviceConditions = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "device_conditions ORDER BY name");
       
       $this->view->render('devices/create', [
           'customer' => $customer,
           'customers' => $customers,
           'deviceConditions' => $deviceConditions
       ]);
   }
   
   public function store(): void {
       $this->requireAuth();
       $this->requirePermission('device.create');
       
       // Validáció
       $errors = $this->validate($_POST, [
           'customer_id' => 'required|numeric',
           'name' => 'required|min:3|max:100',
           'serial_number' => 'max:50',
           'condition_id' => 'required|numeric'
       ]);
       
       if (!empty($errors)) {
           $this->setErrors($errors);
           $this->setOldInput($_POST);
           $this->redirectBack();
       }
       
       // Gyári szám egyediség ellenőrzése
       if (!empty($_POST['serial_number'])) {
           $deviceModel = new Device();
           $existing = $deviceModel->searchBySerialNumber($_POST['serial_number']);
           if ($existing) {
               $this->error('Ez a gyári szám már létezik!');
               $this->setOldInput($_POST);
               $this->redirectBack();
           }
       }
       
       $data = [
           'customer_id' => $_POST['customer_id'],
           'name' => $_POST['name'],
           'serial_number' => $_POST['serial_number'] ?? '',
           'condition_id' => $_POST['condition_id'],
           'accessories' => $_POST['accessories'] ?? '',
           'purchase_date' => $_POST['purchase_date'] ?? null,
           'purchase_price' => $_POST['purchase_price'] ?? 0
       ];
       
       try {
           $deviceModel = new Device();
           $deviceId = $deviceModel->create($data);
           
           $this->success('Eszköz sikeresen létrehozva!');
           
           // Ha worksheet create-ből jöttünk
           if (!empty($_POST['redirect_to_worksheet'])) {
               $this->redirect('worksheets/create?customer_id=' . $_POST['customer_id'] . '&device_id=' . $deviceId);
           } else {
               $this->redirect('devices/' . $deviceId . '/edit');
           }
       } catch (\Exception $e) {
           $this->error('Hiba történt: ' . $e->getMessage());
           $this->setOldInput($_POST);
           $this->redirectBack();
       }
   }
   
   public function edit(int $id): void {
       $this->requireAuth();
       $this->requirePermission('device.edit');
       
       $deviceModel = new Device();
       $device = $deviceModel->find($id);
       
       if (!$device) {
           $this->redirect('error/404');
       }
       
       // Ügyfél adatok betöltése - JAVÍTVA!
       $customerModel = new Customer();
       $customer = $customerModel->find($device['customer_id']);
       
       $deviceConditions = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "device_conditions ORDER BY name");
       
       // Javítási előzmények
       $repairHistory = $deviceModel->getRepairHistory($id);
       
       // Statisztikák
       $stats = [
           'repair_count' => $deviceModel->getRepairCount($id),
           'total_repair_cost' => $deviceModel->getTotalRepairCost($id),
           'last_repair' => !empty($repairHistory) ? $repairHistory[0]['created_at'] : null
       ];
       
       $this->view->render('devices/edit', [
           'device' => $device,
           'customer' => $customer,
           'deviceConditions' => $deviceConditions,
           'repairHistory' => $repairHistory,
           'stats' => $stats
       ]);
   }
   
   public function update(int $id): void {
       $this->requireAuth();
       $this->requirePermission('device.edit');
       
       $deviceModel = new Device();
       $device = $deviceModel->find($id);
       
       if (!$device) {
           $this->redirect('error/404');
       }
       
       // Validáció
       $errors = $this->validate($_POST, [
           'name' => 'required|min:3|max:100',
           'serial_number' => 'max:50',
           'condition_id' => 'required|numeric'
       ]);
       
       if (!empty($errors)) {
           $this->setErrors($errors);
           $this->redirectBack();
       }
       
       // Gyári szám egyediség ellenőrzése
       if (!empty($_POST['serial_number']) && $_POST['serial_number'] !== $device['serial_number']) {
           $existing = $deviceModel->searchBySerialNumber($_POST['serial_number']);
           if ($existing) {
               $this->error('Ez a gyári szám már létezik!');
               $this->redirectBack();
           }
       }
       
       $data = [
           'name' => $_POST['name'],
           'serial_number' => $_POST['serial_number'] ?? '',
           'condition_id' => $_POST['condition_id'],
           'accessories' => $_POST['accessories'] ?? '',
           'purchase_date' => $_POST['purchase_date'] ?? null,
           'purchase_price' => $_POST['purchase_price'] ?? 0
       ];
       
       try {
           $deviceModel->update($id, $data);
           $this->success('Eszköz adatok sikeresen frissítve!');
           $this->redirectBack();
       } catch (\Exception $e) {
           $this->error('Hiba történt: ' . $e->getMessage());
           $this->redirectBack();
       }
   }
   
   public function delete(int $id): void {
       $this->requireAuth();
       $this->requirePermission('device.delete');
       
       $deviceModel = new Device();
       
       // Ellenőrizzük, hogy van-e kapcsolódó munkalap
       $worksheetCount = $this->db->fetchOne(
           "SELECT COUNT(*) as count FROM " . DB_PREFIX . "worksheets WHERE device_id = ?",
           [$id]
       );
       
       if ($worksheetCount['count'] > 0) {
           $this->error('Az eszköz nem törölhető, mert kapcsolódó munkalapok vannak!');
           $this->redirectBack();
       }
       
       if ($deviceModel->delete($id)) {
           $this->success('Eszköz sikeresen törölve!');
       } else {
           $this->error('Hiba történt a törlés során!');
       }
       
       $this->redirect('devices');
   }
}