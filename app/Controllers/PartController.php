<?php
// app/Controllers/PartController.php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Part;
use Models\Category;

class PartController extends Controller {
    
    public function index(): void {
        $this->requireAuth();
        $this->requirePermission('part.view');
        
        $partModel = new Part();
        $categoryModel = new Category();
        
        $page = (int) ($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $type = $_GET['type'] ?? '';
        $categoryId = $_GET['category_id'] ?? '';
        
        $conditions = ['is_active' => 1];
        if ($type) {
            $conditions['type'] = $type;
        }
        if ($categoryId) {
            $conditions['category_id'] = $categoryId;
        }
        
        if (!empty($search)) {
            // Use searchWithFilters for search
            $filters = [
                'search' => $search,
                'type' => $type,
                'category_id' => $categoryId
            ];
            $parts = $partModel->searchWithFilters($filters);
            $pagination = [
                'items' => $parts,
                'total' => count($parts),
                'current' => 1,
                'pages' => 1
            ];
        } else {
            // Use paginateWithCategory for normal listing
            $pagination = $partModel->paginateWithCategory($page, null, $conditions, 'ps.name ASC');
        }
        
        // Add usage statistics
        foreach ($pagination['items'] as &$part) {
            $part['usage_count'] = $partModel->getUsageCount($part['id']);
            $part['total_revenue'] = $partModel->getTotalRevenue($part['id']);
        }
        
        // Get categories for filter
        $categories = $categoryModel->getForSelect();
        
        $this->view->render('parts/index', [
            'pagination' => $pagination,
            'search' => $search,
            'type' => $type,
            'categoryId' => $categoryId,
            'categories' => $categories
        ]);
    }
    
    public function create(): void {
        $this->requireAuth();
        $this->requirePermission('part.create');
        
        $categoryModel = new Category();
        $categories = $categoryModel->getForSelect();
        
        // Pre-select category if passed in URL
        $selectedCategoryId = $_GET['category_id'] ?? null;
        
        $this->view->render('parts/create', [
            'categories' => $categories,
            'selectedCategoryId' => $selectedCategoryId
        ]);
    }
    
    public function store(): void {
        $this->requireAuth();
        $this->requirePermission('part.create');
        
        // Validation
        $errors = $this->validate($_POST, [
            'name' => 'required|min:3|max:100',
            'sku' => 'max:50',
            'type' => 'required',
            'unit' => 'required|max:20',
            'price' => 'required|numeric'
        ]);
        
        if (!empty($errors)) {
            $this->setErrors($errors);
            $this->setOldInput($_POST);
            $this->redirectBack();
        }
        
        $partModel = new Part();
        
        // Check SKU uniqueness
        if (!empty($_POST['sku'])) {
            if ($partModel->checkSku($_POST['sku'])) {
                $this->error('Ez a cikkszám már létezik!');
                $this->setOldInput($_POST);
                $this->redirectBack();
            }
        }
        
        $data = [
            'name' => $_POST['name'],
            'sku' => $_POST['sku'] ?? '',
            'type' => $_POST['type'],
            'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
            'unit' => $_POST['unit'],
            'price' => $_POST['price'],
            'description' => $_POST['description'] ?? '',
            'stock' => (int)($_POST['stock'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        try {
            $partId = $partModel->create($data);
            
            // Handle image upload
            if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($_FILES['image']['type'], $allowedTypes)) {
                    $filename = $partModel->saveImage($_FILES['image'], $partId);
                    if ($filename) {
                        $partModel->update($partId, ['image' => $filename]);
                    }
                } else {
                    $this->error('Csak JPG, PNG és GIF képek tölthetők fel!');
                }
            }
            
            $this->success('Alkatrész/szolgáltatás sikeresen létrehozva!');
            $this->redirect('parts/' . $partId . '/edit');
        } catch (\Exception $e) {
            $this->error('Hiba történt: ' . $e->getMessage());
            $this->setOldInput($_POST);
            $this->redirectBack();
        }
    }

    public function edit(int $id): void {
        $this->requireAuth();
        $this->requirePermission('part.edit');
        
        $partModel = new Part();
        $part = $partModel->find($id);
        
        if (!$part) {
            $this->redirect('error/404');
        }
        
        $categoryModel = new Category();
        $categories = $categoryModel->getForSelect();
        
        // Usage statistics
        $stats = [
            'usage_count' => $partModel->getUsageCount($id),
            'total_revenue' => $partModel->getTotalRevenue($id)
        ];
        
        // Last usages
        $lastUsages = $this->db->fetchAll(
            "SELECT w.id as worksheet_id, w.worksheet_number, w.created_at, wi.quantity, wi.total_price,
                    c.name as customer_name
             FROM " . DB_PREFIX . "worksheet_items wi
             JOIN " . DB_PREFIX . "worksheets w ON wi.worksheet_id = w.id
             JOIN " . DB_PREFIX . "customers c ON w.customer_id = c.id
             WHERE wi.part_service_id = ?
             ORDER BY w.created_at DESC
             LIMIT 10",
            [$id]
        );
        
        $this->view->render('parts/edit', [
            'part' => $part,
            'categories' => $categories,
            'stats' => $stats,
            'lastUsages' => $lastUsages
        ]);
    }
    
    public function update(int $id): void {
        $this->requireAuth();
        $this->requirePermission('part.edit');
        
        $partModel = new Part();
        $part = $partModel->find($id);
        
        if (!$part) {
            $this->redirect('error/404');
        }
        
        // Validation
        $errors = $this->validate($_POST, [
            'name' => 'required|min:3|max:100',
            'sku' => 'max:50',
            'type' => 'required',
            'unit' => 'required|max:20',
            'price' => 'required|numeric'
        ]);
        
        if (!empty($errors)) {
            $this->setErrors($errors);
            $this->redirectBack();
        }
        
        // SKU uniqueness check
        if (!empty($_POST['sku']) && $_POST['sku'] !== $part['sku']) {
            if ($partModel->checkSku($_POST['sku'], $id)) {
                $this->error('Ez a cikkszám már létezik!');
                $this->redirectBack();
            }
        }
        
        $data = [
            'name' => $_POST['name'],
            'sku' => $_POST['sku'] ?? '',
            'type' => $_POST['type'],
            'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
            'unit' => $_POST['unit'],
            'price' => $_POST['price'],
            'description' => $_POST['description'] ?? '',
            'stock' => (int)($_POST['stock'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        try {
            $partModel->update($id, $data);
            
            // Handle image upload
            if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($_FILES['image']['type'], $allowedTypes)) {
                    // Delete old image if exists
                    if (!empty($part['image'])) {
                        $partModel->deleteImage($part['image']);
                    }
                    
                    $filename = $partModel->saveImage($_FILES['image'], $id);
                    if ($filename) {
                        $partModel->update($id, ['image' => $filename]);
                    }
                } else {
                    $this->error('Csak JPG, PNG és GIF képek tölthetők fel!');
                }
            }
            
            // Handle image deletion
            if (isset($_POST['delete_image']) && !empty($part['image'])) {
                $partModel->deleteImage($part['image']);
                $partModel->update($id, ['image' => null]);
                $this->success('Kép sikeresen törölve!');
            }
            
            $this->success('Alkatrész/szolgáltatás sikeresen frissítve!');
            $this->redirectBack();
        } catch (\Exception $e) {
            $this->error('Hiba történt: ' . $e->getMessage());
            $this->redirectBack();
        }
    }
    
    public function delete(int $id): void {
        $this->requireAuth();
        $this->requirePermission('part.delete');
        
        $partModel = new Part();
        $part = $partModel->find($id);
        
        if (!$part) {
            $this->redirect('error/404');
        }
        
        // Check if in use
        $usageCount = $partModel->getUsageCount($id);
        
        if ($usageCount > 0) {
            $this->error('Az alkatrész/szolgáltatás nem törölhető, mert használatban van!');
            $this->redirectBack();
        }
        
        // Delete image if exists
        if (!empty($part['image'])) {
            $partModel->deleteImage($part['image']);
        }
        
        if ($partModel->delete($id)) {
            $this->success('Alkatrész/szolgáltatás sikeresen törölve!');
        } else {
            $this->error('Hiba történt a törlés során!');
        }
        
        $this->redirect('parts');
    }
    
    public function search(): void {
        $this->requireAuth();
        
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            $this->json([]);
        }
        
        $partModel = new Part();
        $parts = $partModel->searchItems($query);
        
        $results = [];
        foreach ($parts as $part) {
            $results[] = [
                'id' => $part['id'],
                'text' => $part['name'] . ' (' . $part['sku'] . ')',
                'name' => $part['name'],
                'sku' => $part['sku'],
                'type' => $part['type'],
                'unit' => $part['unit'],
                'price' => $part['price'],
                'description' => $part['description'],
                'category_name' => $part['category_name'] ?? ''
            ];
        }
        
        $this->json($results);
    }
}