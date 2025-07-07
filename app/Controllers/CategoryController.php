<?php
// app/Controllers/CategoryController.php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Category;
use Models\Part;

class CategoryController extends Controller {
    
    /**
     * List all categories in tree view
     */
    public function index(): void {
        $this->requireAuth();
        $this->requirePermission('part.view');
        
        $categoryModel = new Category();
        $categories = $categoryModel->getTree();
        
        // Get statistics for each category
        $this->addStatisticsToTree($categories);
        
        $this->view->render('categories/index', [
            'categories' => $categories
        ]);
    }
    
    /**
     * Add statistics to category tree
     */
    private function addStatisticsToTree(array &$categories): void {
        $categoryModel = new Category();
        
        foreach ($categories as &$category) {
            $stats = $categoryModel->getStatistics($category['id']);
            $category['direct_parts'] = $stats['direct_parts'];
            $category['total_parts'] = $stats['total_parts'];
            
            if (isset($category['children'])) {
                $this->addStatisticsToTree($category['children']);
            }
        }
    }
    
    /**
     * Show create form
     */
    public function create(): void {
        $this->requireAuth();
        $this->requirePermission('part.create');
        
        $categoryModel = new Category();
        $categories = $categoryModel->getForSelect();
        
        $this->view->render('categories/create', [
            'categories' => $categories
        ]);
    }
    
    /**
     * Store new category
     */
    public function store(): void {
        $this->requireAuth();
        $this->requirePermission('part.create');
        
        // Validation
        $errors = $this->validate($_POST, [
            'name' => 'required|min:2|max:100',
            'code' => 'max:20',
            'sort_order' => 'numeric'
        ]);
        
        if (!empty($errors)) {
            $this->setErrors($errors);
            $this->setOldInput($_POST);
            $this->redirectBack();
        }
        
        $categoryModel = new Category();
        
        // Check code uniqueness
        if (!empty($_POST['code'])) {
            if ($categoryModel->checkCode($_POST['code'])) {
                $this->error('Ez a kód már létezik!');
                $this->setOldInput($_POST);
                $this->redirectBack();
            }
        }
        
        // Check for circular reference
        if (!empty($_POST['parent_id']) && $_POST['parent_id'] == ($_POST['id'] ?? 0)) {
            $this->error('A kategória nem lehet saját maga szülője!');
            $this->setOldInput($_POST);
            $this->redirectBack();
        }
        
        $data = [
            'parent_id' => !empty($_POST['parent_id']) ? $_POST['parent_id'] : null,
            'name' => $_POST['name'],
            'code' => $_POST['code'] ?? '',
            'description' => $_POST['description'] ?? '',
            'sort_order' => $_POST['sort_order'] ?? 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        try {
            $categoryId = $categoryModel->create($data);
            $this->success('Kategória sikeresen létrehozva!');
            $this->redirect('categories');
        } catch (\Exception $e) {
            $this->error('Hiba történt: ' . $e->getMessage());
            $this->setOldInput($_POST);
            $this->redirectBack();
        }
    }
    
    /**
     * Show edit form
     */
    public function edit(int $id): void {
        $this->requireAuth();
        $this->requirePermission('part.edit');
        
        $categoryModel = new Category();
        $category = $categoryModel->find($id);
        
        if (!$category) {
            $this->redirect('error/404');
        }
        
        // Get categories for parent select (exclude self and children)
        $allCategories = $categoryModel->getForSelect();
        $categories = [];
        
        foreach ($allCategories as $catId => $catName) {
            // Skip self and children
            $cat = $categoryModel->find($catId);
            if ($catId != $id && strpos($cat['path'], '/' . $id . '/') === false) {
                $categories[$catId] = $catName;
            }
        }
        
        // Get statistics
        $stats = $categoryModel->getStatistics($id);
        
        $this->view->render('categories/edit', [
            'category' => $category,
            'categories' => $categories,
            'stats' => $stats
        ]);
    }
    
    /**
     * Update category
     */
    public function update(int $id): void {
        $this->requireAuth();
        $this->requirePermission('part.edit');
        
        $categoryModel = new Category();
        $category = $categoryModel->find($id);
        
        if (!$category) {
            $this->redirect('error/404');
        }
        
        // Validation
        $errors = $this->validate($_POST, [
            'name' => 'required|min:2|max:100',
            'code' => 'max:20',
            'sort_order' => 'numeric'
        ]);
        
        if (!empty($errors)) {
            $this->setErrors($errors);
            $this->redirectBack();
        }
        
        // Check code uniqueness
        if (!empty($_POST['code']) && $_POST['code'] !== $category['code']) {
            if ($categoryModel->checkCode($_POST['code'], $id)) {
                $this->error('Ez a kód már létezik!');
                $this->redirectBack();
            }
        }
        
        // Check for circular reference
        if (!empty($_POST['parent_id'])) {
            if ($_POST['parent_id'] == $id) {
                $this->error('A kategória nem lehet saját maga szülője!');
                $this->redirectBack();
            }
            
            // Check if new parent is not a child of this category
            $newParent = $categoryModel->find($_POST['parent_id']);
            if (strpos($newParent['path'], '/' . $id . '/') !== false) {
                $this->error('A kiválasztott szülő kategória ennek a kategóriának a gyermeke!');
                $this->redirectBack();
            }
        }
        
        $data = [
            'parent_id' => !empty($_POST['parent_id']) ? $_POST['parent_id'] : null,
            'name' => $_POST['name'],
            'code' => $_POST['code'] ?? '',
            'description' => $_POST['description'] ?? '',
            'sort_order' => $_POST['sort_order'] ?? 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        try {
            $categoryModel->update($id, $data);
            $this->success('Kategória sikeresen frissítve!');
            $this->redirectBack();
        } catch (\Exception $e) {
            $this->error('Hiba történt: ' . $e->getMessage());
            $this->redirectBack();
        }
    }
    
    /**
     * Delete category
     */
    public function delete(int $id): void {
        $this->requireAuth();
        $this->requirePermission('part.delete');
        
        $categoryModel = new Category();
        $category = $categoryModel->find($id);
        
        if (!$category) {
            $this->redirect('error/404');
        }
        
        // Check if has children or parts
        if ($categoryModel->hasChildren($id)) {
            $this->error('A kategória nem törölhető, mert alkategóriái vannak!');
            $this->redirectBack();
        }
        
        if ($categoryModel->hasParts($id)) {
            $this->error('A kategória nem törölhető, mert alkatrészek/szolgáltatások tartoznak hozzá!');
            $this->redirectBack();
        }
        
        if ($categoryModel->delete($id)) {
            $this->success('Kategória sikeresen törölve!');
        } else {
            $this->error('Hiba történt a törlés során!');
        }
        
        $this->redirect('categories');
    }
    
    /**
     * Show parts in category
     */
    public function parts(int $id): void {
        $this->requireAuth();
        $this->requirePermission('part.view');
        
        $categoryModel = new Category();
        $category = $categoryModel->find($id);
        
        if (!$category) {
            $this->redirect('error/404');
        }
        
        $partModel = new Part();
        $page = (int) ($_GET['page'] ?? 1);
        
        // Get parts in this category and subcategories
        $includeSubcategories = isset($_GET['include_sub']) ? true : false;
        
        if ($includeSubcategories) {
            // Get all category IDs (this + children)
            $categoryIds = $this->getCategoryIdsWithChildren($id);
            $conditions = ['category_id' => $categoryIds, 'is_active' => 1];
        } else {
            $conditions = ['category_id' => $id, 'is_active' => 1];
        }
        
        $pagination = $partModel->paginate($page, null, $conditions, 'name ASC');
        
        // Add usage statistics
        foreach ($pagination['items'] as &$part) {
            $part['usage_count'] = $partModel->getUsageCount($part['id']);
        }
        
        // Get breadcrumb
        $breadcrumb = $categoryModel->getBreadcrumb($id);
        
        $this->view->render('categories/parts', [
            'category' => $category,
            'breadcrumb' => $breadcrumb,
            'pagination' => $pagination,
            'includeSubcategories' => $includeSubcategories
        ]);
    }
    
    /**
     * Get category IDs including all children
     */
    private function getCategoryIdsWithChildren(int $categoryId): array {
        $categoryModel = new Category();
        $ids = [$categoryId];
        
        $sql = "SELECT id FROM " . DB_PREFIX . "parts_categories 
                WHERE path LIKE ?";
        $children = $this->db->fetchAll($sql, ['%/' . $categoryId . '/%']);
        
        foreach ($children as $child) {
            $ids[] = $child['id'];
        }
        
        return $ids;
    }
    
    /**
     * AJAX: Get category tree for select
     */
    public function tree(): void {
        $this->requireAuth();
        
        $categoryModel = new Category();
        $categories = $categoryModel->getTree();
        
        $this->json(['categories' => $categories]);
    }
    
    /**
     * AJAX: Search categories
     */
    public function search(): void {
        $this->requireAuth();
        
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            $this->json([]);
        }
        
        $categoryModel = new Category();
        $sql = "SELECT * FROM " . DB_PREFIX . "parts_categories 
                WHERE is_active = 1 
                AND (name LIKE ? OR code LIKE ?)
                ORDER BY name
                LIMIT 20";
        
        $searchTerm = "%{$query}%";
        $categories = $this->db->fetchAll($sql, [$searchTerm, $searchTerm]);
        
        $results = [];
        foreach ($categories as $category) {
            $results[] = [
                'id' => $category['id'],
                'text' => str_repeat('— ', $category['level']) . $category['name'],
                'name' => $category['name'],
                'code' => $category['code'],
                'level' => $category['level']
            ];
        }
        
        $this->json($results);
    }
}