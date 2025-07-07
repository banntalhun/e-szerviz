<?php
// app/Controllers/CustomerController.php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Customer;

class CustomerController extends Controller {
    
    public function index(): void {
        $this->requireAuth();
        $this->requirePermission('customer.view');
        
        $customerModel = new Customer();
        
        $page = (int) ($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        
        if (!empty($search)) {
            $customers = $customerModel->searchByNameOrPhone($search);
            $pagination = [
                'items' => $customers,
                'total' => count($customers),
                'current_page' => 1,
                'last_page' => 1
            ];
        } else {
            $pagination = $customerModel->paginate($page);
            
            // Statisztikák hozzáadása
            foreach ($pagination['items'] as &$customer) {
                $customer['stats'] = $customerModel->getStatistics($customer['id']);
            }
        }
        
        $this->view->render('customers/index', [
            'pagination' => $pagination,
            'search' => $search
        ]);
    }
    
    public function create(): void {
        $this->requireAuth();
        $this->requirePermission('customer.create');
        
        $priorityTypes = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "priority_types ORDER BY level");
        
        $this->view->render('customers/create', [
            'priorityTypes' => $priorityTypes
        ]);
    }
    
    public function store(): void {
        $this->requireAuth();
        $this->requirePermission('customer.create');
        
        // Validáció
        $errors = $this->validate($_POST, [
            'name' => 'required|min:3|max:100',
            'phone' => 'required|min:10|max:20',
            'email' => 'email|max:100',
            'address' => 'max:200'
        ]);
        
        if (!empty($errors)) {
            $this->setErrors($errors);
            $this->setOldInput($_POST);
            $this->redirectBack();
        }
        
        $customerModel = new Customer();
        
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'],
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'postal_code' => $_POST['postal_code'] ?? '',
            'is_company' => isset($_POST['is_company']) ? 1 : 0,
            'company_name' => $_POST['company_name'] ?? '',
            'tax_number' => $_POST['tax_number'] ?? '',
            'company_address' => $_POST['company_address'] ?? '',
            'internal_note' => $_POST['internal_note'] ?? '',
            'priority_id' => $_POST['priority_id'] ?? 1
        ];
        
        try {
            $customerId = $customerModel->create($data);
            $this->success('Ügyfél sikeresen létrehozva!');
            $this->redirect('customers/' . $customerId . '/edit');
        } catch (\Exception $e) {
            $this->error('Hiba történt: ' . $e->getMessage());
            $this->setOldInput($_POST);
            $this->redirectBack();
        }
    }
    
    public function edit(int $id): void {
        $this->requireAuth();
        $this->requirePermission('customer.edit');
        
        $customerModel = new Customer();
        $customer = $customerModel->find($id);
        
        if (!$customer) {
            $this->redirect('error/404');
        }
        
        $priorityTypes = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "priority_types ORDER BY level");
        
        // Ügyfél statisztikák
        $stats = $customerModel->getStatistics($id);
        
        // Munkalapok
        $worksheets = $customerModel->getWorksheets($id);
        
        // Eszközök
        $devices = $customerModel->getDevices($id);
        
        $this->view->render('customers/edit', [
            'customer' => $customer,
            'priorityTypes' => $priorityTypes,
            'stats' => $stats,
            'worksheets' => $worksheets,
            'devices' => $devices
        ]);
    }
    
    public function update(int $id): void {
        $this->requireAuth();
        $this->requirePermission('customer.edit');
        
        $customerModel = new Customer();
        $customer = $customerModel->find($id);
        
        if (!$customer) {
            $this->redirect('error/404');
        }
        
        // Validáció
        $errors = $this->validate($_POST, [
            'name' => 'required|min:3|max:100',
            'phone' => 'required|min:10|max:20',
            'email' => 'email|max:100',
            'address' => 'max:200'
        ]);
        
        if (!empty($errors)) {
            $this->setErrors($errors);
            $this->redirectBack();
        }
        
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'],
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'postal_code' => $_POST['postal_code'] ?? '',
            'is_company' => isset($_POST['is_company']) ? 1 : 0,
            'company_name' => $_POST['company_name'] ?? '',
            'tax_number' => $_POST['tax_number'] ?? '',
            'company_address' => $_POST['company_address'] ?? '',
            'internal_note' => $_POST['internal_note'] ?? '',
            'priority_id' => $_POST['priority_id'] ?? 1
        ];
        
        try {
            $customerModel->update($id, $data);
            $this->success('Ügyfél adatok sikeresen frissítve!');
            $this->redirectBack();
        } catch (\Exception $e) {
            $this->error('Hiba történt: ' . $e->getMessage());
            $this->redirectBack();
        }
    }
    
    public function delete(int $id): void {
        $this->requireAuth();
        $this->requirePermission('customer.delete');
        
        $customerModel = new Customer();
        
        // Ellenőrizzük, hogy van-e kapcsolódó munkalap
        $worksheetCount = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM " . DB_PREFIX . "worksheets WHERE customer_id = ?",
            [$id]
        );
        
        if ($worksheetCount['count'] > 0) {
            $this->error('Az ügyfél nem törölhető, mert kapcsolódó munkalapok vannak!');
            $this->redirectBack();
        }
        
        if ($customerModel->delete($id)) {
            $this->success('Ügyfél sikeresen törölve!');
        } else {
            $this->error('Hiba történt a törlés során!');
        }
        
        $this->redirect('customers');
    }
    
    public function search(): void {
        $this->requireAuth();
        
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            $this->json([]);
        }
        
        $customerModel = new Customer();
        $customers = $customerModel->searchByNameOrPhone($query);
        
        $results = [];
        foreach ($customers as $customer) {
            $results[] = [
                'id' => $customer['id'],
                'text' => $customer['name'] . ' - ' . $customer['phone'],
                'name' => $customer['name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
                'address' => $customer['address'],
                'is_company' => $customer['is_company'],
                'company_name' => $customer['company_name'],
                'priority' => [
                    'name' => $customer['priority_name'],
                    'color' => $customer['priority_color']
                ]
            ];
        }
        
        $this->json($results);
    }
}
