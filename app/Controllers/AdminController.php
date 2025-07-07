<?php
// app/Controllers/AdminController.php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\User;

class AdminController extends Controller {
    
    public function index(): void {
        $this->requireAdmin();
        
        // Admin dashboard statisztikák
        $stats = [
            'total_users' => $this->db->fetchOne("SELECT COUNT(*) as count FROM " . DB_PREFIX . "users")['count'],
            'active_users' => $this->db->fetchOne("SELECT COUNT(*) as count FROM " . DB_PREFIX . "users WHERE is_active = 1")['count'],
            'total_roles' => $this->db->fetchOne("SELECT COUNT(*) as count FROM " . DB_PREFIX . "roles")['count'],
            'total_locations' => $this->db->fetchOne("SELECT COUNT(*) as count FROM " . DB_PREFIX . "locations")['count']
        ];
        
        // Legutóbbi bejelentkezések
        $recentLogins = $this->db->fetchAll(
            "SELECT u.*, r.name as role_name
             FROM " . DB_PREFIX . "users u
             JOIN " . DB_PREFIX . "roles r ON u.role_id = r.id
             WHERE u.last_login IS NOT NULL
             ORDER BY u.last_login DESC
             LIMIT 10"
        );
        
        $this->view->render('admin/index', [
            'stats' => $stats,
            'recentLogins' => $recentLogins
        ]);
    }
    
    public function users(): void {
        $this->requireAdmin();
        
        $userModel = new User();
        $page = (int) ($_GET['page'] ?? 1);
        
        $pagination = $userModel->paginate($page);
        
        // Role információk hozzáadása
        foreach ($pagination['items'] as &$user) {
            $user['role'] = $this->db->fetchOne(
                "SELECT * FROM " . DB_PREFIX . "roles WHERE id = ?",
                [$user['role_id']]
            );
            $user['worksheet_count'] = $userModel->getWorksheetCount($user['id']);
        }
        
        $this->view->render('admin/users', [
            'pagination' => $pagination
        ]);
    }
    
    public function createUser(): void {
        $this->requireAdmin();
        
        $roles = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "roles ORDER BY name");
        $locations = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "locations WHERE is_active = 1 ORDER BY name");
        
        $this->view->render('admin/user_form', [
            'user' => null,
            'roles' => $roles,
            'locations' => $locations
        ]);
    }
    
    public function storeUser(): void {
        $this->requireAdmin();
        
        // Validáció
        $errors = $this->validate($_POST, [
            'username' => 'required|min:3|max:50|unique:users.username',
            'password' => 'required|min:6|confirmed',
            'email' => 'required|email|unique:users.email',
            'full_name' => 'required|min:3|max:100',
            'phone' => 'max:20',
            'role_id' => 'required|numeric'
        ]);
        
        if (!empty($errors)) {
            $this->setErrors($errors);
            $this->setOldInput($_POST);
            $this->redirectBack();
        }
        
        $userModel = new User();
        
        $data = [
            'username' => $_POST['username'],
            'password' => Auth::generatePassword($_POST['password']),
            'email' => $_POST['email'],
            'full_name' => $_POST['full_name'],
            'phone' => $_POST['phone'] ?? '',
            'role_id' => $_POST['role_id'],
            'location_id' => $_POST['location_id'] ?? null,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        try {
            $userId = $userModel->create($data);
            $this->success('Felhasználó sikeresen létrehozva!');
            $this->redirect('admin/users');
        } catch (\Exception $e) {
            $this->error('Hiba történt: ' . $e->getMessage());
            $this->setOldInput($_POST);
            $this->redirectBack();
        }
    }
    
    public function editUser(int $id): void {
        $this->requireAdmin();
        
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            $this->redirect('error/404');
        }
        
        $roles = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "roles ORDER BY name");
        $locations = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "locations WHERE is_active = 1 ORDER BY name");
        
        $this->view->render('admin/user_form', [
            'user' => $user,
            'roles' => $roles,
            'locations' => $locations
        ]);
    }
    
    public function updateUser(int $id): void {
        $this->requireAdmin();
        
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            $this->redirect('error/404');
        }
        
        // Validáció
        $rules = [
            'username' => 'required|min:3|max:50',
            'email' => 'required|email',
            'full_name' => 'required|min:3|max:100',
            'phone' => 'max:20',
            'role_id' => 'required|numeric'
        ];
        
        // Jelszó csak ha ki van töltve
        if (!empty($_POST['password'])) {
            $rules['password'] = 'min:6|confirmed';
        }
        
        $errors = $this->validate($_POST, $rules);
        
        // Egyediség ellenőrzése
        if ($_POST['username'] !== $user['username']) {
            $existing = $userModel->findByUsername($_POST['username']);
            if ($existing) {
                $errors['username'] = 'Ez a felhasználónév már foglalt.';
            }
        }
        
        if ($_POST['email'] !== $user['email']) {
            $existing = $userModel->findByEmail($_POST['email']);
            if ($existing) {
                $errors['email'] = 'Ez az email cím már használatban van.';
            }
        }
        
        if (!empty($errors)) {
            $this->setErrors($errors);
            $this->redirectBack();
        }
        
        $data = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'full_name' => $_POST['full_name'],
            'phone' => $_POST['phone'] ?? '',
            'role_id' => $_POST['role_id'],
            'location_id' => $_POST['location_id'] ?? null,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        // Jelszó frissítése ha megadták
        if (!empty($_POST['password'])) {
            $data['password'] = Auth::generatePassword($_POST['password']);
        }
        
        try {
            $userModel->update($id, $data);
            $this->success('Felhasználó sikeresen frissítve!');
            $this->redirect('admin/users');
        } catch (\Exception $e) {
            $this->error('Hiba történt: ' . $e->getMessage());
            $this->redirectBack();
        }
    }
    
    public function deleteUser(int $id): void {
        $this->requireAdmin();
        
        // Saját fiók törlésének megakadályozása
        if ($id == Auth::id()) {
            $this->error('Saját fiókot nem lehet törölni!');
            $this->redirectBack();
        }
        
        $userModel = new User();
        
        // Ellenőrizzük, hogy van-e kapcsolódó munkalap
        $worksheetCount = $userModel->getWorksheetCount($id);
        
        if ($worksheetCount > 0) {
            $this->error('A felhasználó nem törölhető, mert kapcsolódó munkalapok vannak!');
            $this->redirectBack();
        }
        
        if ($userModel->delete($id)) {
            $this->success('Felhasználó sikeresen törölve!');
        } else {
            $this->error('Hiba történt a törlés során!');
        }
        
        $this->redirect('admin/users');
    }
    
    public function settings(): void {
        $this->requireAdmin();
        
        // Beállítások betöltése
        $settings = [
            'locations' => $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "locations ORDER BY is_default DESC, name"),
            'status_types' => $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "status_types ORDER BY sort_order"),
            'priority_types' => $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "priority_types ORDER BY level"),
            'repair_types' => $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "repair_types ORDER BY name"),
            'device_conditions' => $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "device_conditions ORDER BY name")
        ];
        
        $this->view->render('admin/settings', [
            'settings' => $settings
        ]);
    }
    
    public function updateSettings(): void {
        $this->requireAdmin();
        
        $type = $_POST['type'] ?? '';
        $action = $_POST['action'] ?? '';
        
        try {
            switch ($type) {
                case 'location':
                    $this->handleLocationSettings($action);
                    break;
                    
                case 'status_type':
                    $this->handleStatusTypeSettings($action);
                    break;
                    
                case 'priority_type':
                    $this->handlePriorityTypeSettings($action);
                    break;
                    
                case 'repair_type':
                    $this->handleRepairTypeSettings($action);
                    break;
                    
                case 'device_condition':
                    $this->handleDeviceConditionSettings($action);
                    break;
                    
                default:
                    throw new \Exception('Ismeretlen beállítás típus');
            }
            
            $this->success('Beállítások sikeresen frissítve!');
        } catch (\Exception $e) {
            $this->error('Hiba történt: ' . $e->getMessage());
        }
        
        $this->redirectBack();
    }
    
    public function permissions(): void {
        $this->requireAdmin();
        
        // Szerepkörök és jogosultságok betöltése
        $roles = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "roles ORDER BY name");
        $permissions = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "permissions ORDER BY category, name");
        
        // Szerepkör-jogosultság kapcsolatok
        $rolePermissions = [];
        $results = $this->db->fetchAll(
            "SELECT role_id, permission_id FROM " . DB_PREFIX . "role_permissions"
        );
        
        foreach ($results as $rp) {
            if (!isset($rolePermissions[$rp['role_id']])) {
                $rolePermissions[$rp['role_id']] = [];
            }
            $rolePermissions[$rp['role_id']][] = $rp['permission_id'];
        }
        
        // Jogosultságok kategóriák szerint csoportosítva
        $permissionsByCategory = [];
        foreach ($permissions as $permission) {
            $category = $permission['category'];
            if (!isset($permissionsByCategory[$category])) {
                $permissionsByCategory[$category] = [];
            }
            $permissionsByCategory[$category][] = $permission;
        }
        
        $this->view->render('admin/permissions', [
            'roles' => $roles,
            'permissions' => $permissions,
            'permissionsByCategory' => $permissionsByCategory,
            'rolePermissions' => $rolePermissions
        ]);
    }
    
    public function updatePermissions(): void {
        $this->requireAdmin();
        
        $roleId = $_POST['role_id'] ?? 0;
        $permissions = $_POST['permissions'] ?? [];
        
        if (!$roleId) {
            $this->error('Érvénytelen szerepkör!');
            $this->redirectBack();
        }
        
        try {
            $this->db->beginTransaction();
            
            // Meglévő jogosultságok törlése
            $this->db->delete('role_permissions', ['role_id' => $roleId]);
            
            // Új jogosultságok hozzáadása
            foreach ($permissions as $permissionId) {
                $this->db->insert('role_permissions', [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId
                ]);
            }
            
            $this->db->commit();
            $this->success('Jogosultságok sikeresen frissítve!');
        } catch (\Exception $e) {
            $this->db->rollback();
            $this->error('Hiba történt: ' . $e->getMessage());
        }
        
        $this->redirectBack();
    }
    
    private function handleLocationSettings(string $action): void {
        switch ($action) {
            case 'add':
                $this->db->insert('locations', [
                    'name' => $_POST['name'],
                    'address' => $_POST['address'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'is_default' => isset($_POST['is_default']) ? 1 : 0,
                    'is_active' => 1
                ]);
                
                // Ha alapértelmezett, akkor a többit visszaállítjuk
                if (isset($_POST['is_default'])) {
                    $lastId = $this->db->getLastInsertId();
                    $this->db->query(
                        "UPDATE " . DB_PREFIX . "locations SET is_default = 0 WHERE id != ?",
                        [$lastId]
                    );
                }
                break;
                
            case 'update':
                $id = $_POST['id'];
                $this->db->update('locations', [
                    'name' => $_POST['name'],
                    'address' => $_POST['address'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'is_active' => isset($_POST['is_active']) ? 1 : 0
                ], ['id' => $id]);
                break;
                
            case 'delete':
                $id = $_POST['id'];
                $this->db->delete('locations', ['id' => $id]);
                break;
        }
    }
    
    private function handleStatusTypeSettings(string $action): void {
        switch ($action) {
            case 'add':
                $this->db->insert('status_types', [
                    'name' => $_POST['name'],
                    'color' => $_POST['color'] ?? '#6c757d',
                    'is_closed' => isset($_POST['is_closed']) ? 1 : 0,
                    'sort_order' => $_POST['sort_order'] ?? 0
                ]);
                break;
                
            case 'update':
                $id = $_POST['id'];
                $this->db->update('status_types', [
                    'name' => $_POST['name'],
                    'color' => $_POST['color'] ?? '#6c757d',
                    'is_closed' => isset($_POST['is_closed']) ? 1 : 0,
                    'sort_order' => $_POST['sort_order'] ?? 0
                ], ['id' => $id]);
                break;
                
            case 'delete':
                $id = $_POST['id'];
                // Ellenőrizzük, hogy használatban van-e
                $count = $this->db->fetchOne(
                    "SELECT COUNT(*) as count FROM " . DB_PREFIX . "worksheets WHERE status_id = ?",
                    [$id]
                )['count'];
                
                if ($count > 0) {
                    throw new \Exception('A státusz nem törölhető, mert használatban van!');
                }
                
                $this->db->delete('status_types', ['id' => $id]);
                break;
        }
    }
    
    private function handlePriorityTypeSettings(string $action): void {
        switch ($action) {
            case 'add':
                $this->db->insert('priority_types', [
                    'name' => $_POST['name'],
                    'color' => $_POST['color'] ?? '#6c757d',
                    'level' => $_POST['level'] ?? 0
                ]);
                break;
                
            case 'update':
                $id = $_POST['id'];
                $this->db->update('priority_types', [
                    'name' => $_POST['name'],
                    'color' => $_POST['color'] ?? '#6c757d',
                    'level' => $_POST['level'] ?? 0
                ], ['id' => $id]);
                break;
                
            case 'delete':
                $id = $_POST['id'];
                $this->db->delete('priority_types', ['id' => $id]);
                break;
        }
    }
    
    private function handleRepairTypeSettings(string $action): void {
        switch ($action) {
            case 'add':
                $this->db->insert('repair_types', [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'] ?? ''
                ]);
                break;
                
            case 'update':
                $id = $_POST['id'];
                $this->db->update('repair_types', [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'] ?? ''
                ], ['id' => $id]);
                break;
                
            case 'delete':
                $id = $_POST['id'];
                $this->db->delete('repair_types', ['id' => $id]);
                break;
        }
    }
    
    private function handleDeviceConditionSettings(string $action): void {
        switch ($action) {
            case 'add':
                $this->db->insert('device_conditions', [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'] ?? ''
                ]);
                break;
                
            case 'update':
                $id = $_POST['id'];
                $this->db->update('device_conditions', [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'] ?? ''
                ], ['id' => $id]);
                break;
                
            case 'delete':
                $id = $_POST['id'];
                $this->db->delete('device_conditions', ['id' => $id]);
                break;
        }
    }
}
