<?php
// app/Controllers/DashboardController.php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Worksheet;
use Models\Customer;
use Models\Device;

class DashboardController extends Controller {
    
    public function index(): void {
        $this->requireAuth();
        
        $worksheetModel = new Worksheet();
        $customerModel = new Customer();
        $deviceModel = new Device();
        
        // Dashboard statisztikák
        $stats = $worksheetModel->getDashboardStats();
        
        // További statisztikák
        $stats['total_customers'] = $customerModel->count();
        $stats['total_devices'] = $deviceModel->count();
        
        // Legutóbbi munkalapok
        $recentWorksheets = $worksheetModel->getFilteredWorksheets([]);
        $recentWorksheets = array_slice($recentWorksheets, 0, 10);
        
        // Sürgős munkalapok
        $urgentWorksheets = $this->db->fetchAll(
            "SELECT w.*, c.name as customer_name, d.name as device_name,
                    st.name as status_name, st.color as status_color,
                    pt.name as priority_name, pt.color as priority_color
             FROM " . DB_PREFIX . "worksheets w
             JOIN " . DB_PREFIX . "customers c ON w.customer_id = c.id
             LEFT JOIN " . DB_PREFIX . "devices d ON w.device_id = d.id
             JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
             JOIN " . DB_PREFIX . "priority_types pt ON c.priority_id = pt.id
             WHERE pt.level >= 2 AND st.is_closed = 0
             ORDER BY pt.level DESC, w.created_at ASC
             LIMIT 5"
        );
        
        // Szerelők teljesítménye (ha admin)
        $technicianStats = [];
        if (Auth::isAdmin()) {
            $technicianStats = $this->db->fetchAll(
                "SELECT u.full_name, 
                        COUNT(DISTINCT w.id) as total_worksheets,
                        COUNT(DISTINCT CASE WHEN st.is_closed = 0 THEN w.id END) as active_worksheets,
                        SUM(w.total_price) as total_revenue
                 FROM " . DB_PREFIX . "users u
                 LEFT JOIN " . DB_PREFIX . "worksheets w ON u.id = w.technician_id
                 LEFT JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
                 WHERE u.role_id IN (SELECT id FROM " . DB_PREFIX . "roles WHERE name IN ('admin', 'technician'))
                 GROUP BY u.id
                 ORDER BY total_worksheets DESC"
            );
        }
        
        $this->view->render('dashboard/index', [
            'stats' => $stats,
            'recentWorksheets' => $recentWorksheets,
            'urgentWorksheets' => $urgentWorksheets,
            'technicianStats' => $technicianStats
        ]);
    }
}
