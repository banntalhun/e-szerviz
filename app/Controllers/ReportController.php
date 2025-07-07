<?php
// app/Controllers/ReportController.php

namespace Controllers;

use Core\Controller;
use Core\Auth;

class ReportController extends Controller {
    
    public function index(): void {
        $this->requireAuth();
        $this->requirePermission('report.view');
        
        $this->view->render('reports/index');
    }
    
    public function revenue(): void {
        $this->requireAuth();
        $this->requirePermission('report.view');
        
        $dateFrom = $_GET['date_from'] ?? date('Y-m-01');
        $dateTo = $_GET['date_to'] ?? date('Y-m-d');
        $groupBy = $_GET['group_by'] ?? 'day';
        
        // Bevételi adatok lekérése
        $revenueData = $this->getRevenueData($dateFrom, $dateTo, $groupBy);
        
        // Top ügyfelek
        $topCustomers = $this->db->fetchAll(
            "SELECT c.name, COUNT(w.id) as worksheet_count, 
                    SUM(w.total_price) as total_revenue
             FROM " . DB_PREFIX . "worksheets w
             JOIN " . DB_PREFIX . "customers c ON w.customer_id = c.id
             WHERE w.created_at BETWEEN ? AND ?
             AND w.is_paid = 1
             GROUP BY c.id
             ORDER BY total_revenue DESC
             LIMIT 10",
            [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']
        );
        
        // Top alkatrészek/szolgáltatások
        $topParts = $this->db->fetchAll(
            "SELECT ps.name, ps.type, COUNT(wi.id) as usage_count,
                    SUM(wi.quantity) as total_quantity,
                    SUM(wi.total_price) as total_revenue
             FROM " . DB_PREFIX . "worksheet_items wi
             JOIN " . DB_PREFIX . "parts_services ps ON wi.part_service_id = ps.id
             JOIN " . DB_PREFIX . "worksheets w ON wi.worksheet_id = w.id
             WHERE w.created_at BETWEEN ? AND ?
             GROUP BY ps.id
             ORDER BY total_revenue DESC
             LIMIT 10",
            [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']
        );
        
        // Javítás típusok szerinti megoszlás
        $repairTypes = $this->db->fetchAll(
            "SELECT rt.name, COUNT(w.id) as count,
                    SUM(w.total_price) as total_revenue
             FROM " . DB_PREFIX . "worksheets w
             JOIN " . DB_PREFIX . "repair_types rt ON w.repair_type_id = rt.id
             WHERE w.created_at BETWEEN ? AND ?
             GROUP BY rt.id
             ORDER BY count DESC",
            [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']
        );
        
        // Összesítő statisztikák
        $summary = $this->db->fetchOne(
            "SELECT COUNT(*) as total_worksheets,
                    COUNT(DISTINCT customer_id) as unique_customers,
                    SUM(total_price) as total_revenue,
                    AVG(total_price) as avg_revenue,
                    SUM(CASE WHEN is_paid = 1 THEN total_price ELSE 0 END) as paid_revenue,
                    SUM(CASE WHEN is_paid = 0 THEN total_price ELSE 0 END) as unpaid_revenue
             FROM " . DB_PREFIX . "worksheets
             WHERE created_at BETWEEN ? AND ?",
            [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']
        );
        
        $this->view->render('reports/revenue', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'groupBy' => $groupBy,
            'revenueData' => $revenueData,
            'topCustomers' => $topCustomers,
            'topParts' => $topParts,
            'repairTypes' => $repairTypes,
            'summary' => $summary
        ]);
    }
    
    public function technician(): void {
        $this->requireAuth();
        $this->requirePermission('report.view');
        
        $dateFrom = $_GET['date_from'] ?? date('Y-m-01');
        $dateTo = $_GET['date_to'] ?? date('Y-m-d');
        $technicianId = $_GET['technician_id'] ?? '';
        
        // Szerelők listája
        $technicians = $this->db->fetchAll(
            "SELECT u.* FROM " . DB_PREFIX . "users u
             JOIN " . DB_PREFIX . "roles r ON u.role_id = r.id
             WHERE r.name IN ('admin', 'technician')
             ORDER BY u.full_name"
        );
        
        // Szerelő teljesítmény adatok
        $performanceData = [];
        if ($technicianId) {
            $performanceData = $this->getTechnicianPerformance($technicianId, $dateFrom, $dateTo);
        } else {
            // Összes szerelő összehasonlítása
            foreach ($technicians as $tech) {
                $performanceData[] = array_merge(
                    ['technician' => $tech],
                    $this->getTechnicianSummary($tech['id'], $dateFrom, $dateTo)
                );
            }
        }
        
        $this->view->render('reports/technician', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'technicianId' => $technicianId,
            'technicians' => $technicians,
            'performanceData' => $performanceData
        ]);
    }
    
    public function device(): void {
        $this->requireAuth();
        $this->requirePermission('report.view');
        
        // Eszköz statisztikák
        $deviceStats = $this->db->fetchOne(
            "SELECT COUNT(*) as total_devices,
                    COUNT(DISTINCT customer_id) as unique_customers
             FROM " . DB_PREFIX . "devices"
        );
        
        // Állapot szerinti megoszlás
        $byCondition = $this->db->fetchAll(
            "SELECT dc.name, dc.color, COUNT(d.id) as count
             FROM " . DB_PREFIX . "devices d
             JOIN " . DB_PREFIX . "device_conditions dc ON d.condition_id = dc.id
             GROUP BY dc.id
             ORDER BY count DESC"
        );
        
        // Legtöbbet javított eszközök
        $mostRepaired = $this->db->fetchAll(
            "SELECT d.*, c.name as customer_name,
                    COUNT(w.id) as repair_count,
                    SUM(w.total_price) as total_repair_cost,
                    MAX(w.created_at) as last_repair
             FROM " . DB_PREFIX . "devices d
             JOIN " . DB_PREFIX . "customers c ON d.customer_id = c.id
             JOIN " . DB_PREFIX . "worksheets w ON d.id = w.device_id
             GROUP BY d.id
             HAVING repair_count > 1
             ORDER BY repair_count DESC
             LIMIT 20"
        );
        
        // Eszköz típusok (név alapján csoportosítva)
        $deviceTypes = $this->db->fetchAll(
            "SELECT SUBSTRING_INDEX(name, ' ', 1) as type,
                    COUNT(*) as count
             FROM " . DB_PREFIX . "devices
             GROUP BY type
             ORDER BY count DESC
             LIMIT 10"
        );
        
        $this->view->render('reports/device', [
            'deviceStats' => $deviceStats,
            'byCondition' => $byCondition,
            'mostRepaired' => $mostRepaired,
            'deviceTypes' => $deviceTypes
        ]);
    }
    
    public function customer(): void {
        $this->requireAuth();
        $this->requirePermission('report.view');
        
        $dateFrom = $_GET['date_from'] ?? date('Y-01-01');
        $dateTo = $_GET['date_to'] ?? date('Y-m-d');
        
        // Top ügyfelek bevétel szerint
        $topByRevenue = $this->db->fetchAll(
            "SELECT c.*, pt.name as priority_name, pt.color as priority_color,
                    COUNT(DISTINCT w.id) as worksheet_count,
                    COUNT(DISTINCT d.id) as device_count,
                    SUM(w.total_price) as total_revenue,
                    MAX(w.created_at) as last_visit
             FROM " . DB_PREFIX . "customers c
             LEFT JOIN " . DB_PREFIX . "priority_types pt ON c.priority_id = pt.id
             LEFT JOIN " . DB_PREFIX . "worksheets w ON c.id = w.customer_id
             LEFT JOIN " . DB_PREFIX . "devices d ON c.id = d.customer_id
             WHERE w.created_at BETWEEN ? AND ?
             GROUP BY c.id
             ORDER BY total_revenue DESC
             LIMIT 20",
            [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']
        );
        
        // Top ügyfelek látogatás szerint
        $topByVisits = $this->db->fetchAll(
            "SELECT c.*, pt.name as priority_name, pt.color as priority_color,
                    COUNT(DISTINCT w.id) as worksheet_count,
                    SUM(w.total_price) as total_revenue,
                    MAX(w.created_at) as last_visit
             FROM " . DB_PREFIX . "customers c
             LEFT JOIN " . DB_PREFIX . "priority_types pt ON c.priority_id = pt.id
             LEFT JOIN " . DB_PREFIX . "worksheets w ON c.id = w.customer_id
             WHERE w.created_at BETWEEN ? AND ?
             GROUP BY c.id
             ORDER BY worksheet_count DESC
             LIMIT 20",
            [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']
        );
        
        // Új ügyfelek
        $newCustomers = $this->db->fetchAll(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as count
             FROM " . DB_PREFIX . "customers
             WHERE created_at BETWEEN ? AND ?
             GROUP BY month
             ORDER BY month",
            [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']
        );
        
        // Prioritás szerinti megoszlás
        $byPriority = $this->db->fetchAll(
            "SELECT pt.name, pt.color, COUNT(c.id) as count
             FROM " . DB_PREFIX . "customers c
             JOIN " . DB_PREFIX . "priority_types pt ON c.priority_id = pt.id
             GROUP BY pt.id
             ORDER BY pt.level"
        );
        
        $this->view->render('reports/customer', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'topByRevenue' => $topByRevenue,
            'topByVisits' => $topByVisits,
            'newCustomers' => $newCustomers,
            'byPriority' => $byPriority
        ]);
    }
    
    private function getRevenueData(string $dateFrom, string $dateTo, string $groupBy): array {
        $format = '';
        switch ($groupBy) {
            case 'day':
                $format = '%Y-%m-%d';
                break;
            case 'week':
                $format = '%Y-%u';
                break;
            case 'month':
                $format = '%Y-%m';
                break;
            case 'year':
                $format = '%Y';
                break;
        }
        
        return $this->db->fetchAll(
            "SELECT DATE_FORMAT(created_at, ?) as period,
                    COUNT(*) as worksheet_count,
                    SUM(total_price) as revenue,
                    SUM(CASE WHEN is_paid = 1 THEN total_price ELSE 0 END) as paid_revenue
             FROM " . DB_PREFIX . "worksheets
             WHERE created_at BETWEEN ? AND ?
             GROUP BY period
             ORDER BY period",
            [$format, $dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']
        );
    }
    
    private function getTechnicianPerformance(int $technicianId, string $dateFrom, string $dateTo): array {
        // Alapstatisztikák
        $summary = $this->getTechnicianSummary($technicianId, $dateFrom, $dateTo);
        
        // Napi teljesítmény
        $dailyPerformance = $this->db->fetchAll(
            "SELECT DATE(created_at) as date,
                    COUNT(*) as worksheet_count,
                    SUM(total_price) as revenue
             FROM " . DB_PREFIX . "worksheets
             WHERE technician_id = ?
             AND created_at BETWEEN ? AND ?
             GROUP BY date
             ORDER BY date",
            [$technicianId, $dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']
        );
        
        // Státusz szerinti megoszlás
        $byStatus = $this->db->fetchAll(
            "SELECT st.name, st.color, COUNT(w.id) as count
             FROM " . DB_PREFIX . "worksheets w
             JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
             WHERE w.technician_id = ?
             AND w.created_at BETWEEN ? AND ?
             GROUP BY st.id",
            [$technicianId, $dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']
        );
        
        return [
            'summary' => $summary,
            'dailyPerformance' => $dailyPerformance,
            'byStatus' => $byStatus
        ];
    }
    
    private function getTechnicianSummary(int $technicianId, string $dateFrom, string $dateTo): array {
        return $this->db->fetchOne(
            "SELECT COUNT(*) as total_worksheets,
                    COUNT(DISTINCT customer_id) as unique_customers,
                    SUM(total_price) as total_revenue,
                    AVG(total_price) as avg_revenue,
                    COUNT(CASE WHEN status_id IN (
                        SELECT id FROM " . DB_PREFIX . "status_types WHERE is_closed = 1
                    ) THEN 1 END) as completed_worksheets,
                    AVG(CASE WHEN status_id IN (
                        SELECT id FROM " . DB_PREFIX . "status_types WHERE is_closed = 1
                    ) THEN TIMESTAMPDIFF(HOUR, created_at, updated_at) END) as avg_completion_hours
             FROM " . DB_PREFIX . "worksheets
             WHERE technician_id = ?
             AND created_at BETWEEN ? AND ?",
            [$technicianId, $dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']
        );
    }
}
