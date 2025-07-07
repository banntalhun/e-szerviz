<?php
// app/Helpers/PdfGenerator.php

namespace Helpers;

require('/home/timbowor/mwshop.hu/data/emailpdf/tfpdf/tfpdf.php');

class PdfGenerator extends \tFPDF {
    
    private array $worksheetData;
    
    public function __construct() {
        parent::__construct('P', 'mm', 'A4');
        
        // PDF tulajdonságok
        $this->SetCreator(APP_NAME);
        $this->SetAuthor(APP_NAME);
        $this->SetTitle('Munkalap');
        
        // Margók
        $this->SetMargins(15, 15, 15);
        
        // Automatikus sortörés
        $this->SetAutoPageBreak(TRUE, 25);
        
        // UTF-8 támogatás
        $this->AddFont('DejaVu','','DejaVuSans.ttf',true);
        $this->AddFont('DejaVu','B','DejaVuSans-Bold.ttf',true);
        $this->AddFont('DejaVu','I','DejaVuSans-Oblique.ttf',true);
        $this->SetFont('DejaVu', '', 10);
    }
    
    public function Header() {
        // Egyszerű fekete-fehér fejléc
        $this->SetFont('DejaVu', 'B', 18);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 10, 'MUNKALAP', 0, 1, 'C');
        
        // Nagyon vékony és halvány vonal
        $this->SetDrawColor(220, 220, 220);
        $this->SetLineWidth(0.1);
        $this->Line(15, $this->GetY() + 2, 195, $this->GetY() + 2);
        $this->Ln(8);
    }
    
    public function Footer() {
        $this->SetY(-22);
        
        // Felső elválasztó vonal - nagyon halvány
        $this->SetDrawColor(230, 230, 230);
        $this->SetLineWidth(0.1);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        
        $this->Ln(2);
        $this->SetFont('DejaVu', '', 7);
        $this->SetTextColor(80, 80, 80);
        
        // Program info
        $this->Cell(0, 4, '© ' . date('Y') . ' ' . APP_NAME . ' - wSoft Workshop szerviz program v1.52.0.0', 0, 1, 'L');
        $this->Cell(0, 4, 'Nyomtatva: ' . date('Y. m. d. H:i:s') . ' | Jogosult: RBR Roll Kft.', 0, 0, 'L');
        $this->Cell(0, 4, 'Oldal ' . $this->PageNo() . '/{nb}', 0, 1, 'R');
    }
    
    public function generateWorksheet(array $worksheet, array $items): void {
        try {
            $this->worksheetData = $worksheet;
            
            // UTF-8 támogatás beállítása
            $this->AliasNbPages();
            
            // Új oldal
            $this->AddPage();
            
            // Munkalap szám - középre igazítva, egy sorban
            $this->SetFont('DejaVu', '', 11);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(0, 6, 'Munkalap szám: ' . $worksheet['worksheet_number'], 0, 1, 'C');
            $this->Ln(5);
            
            // Színek beállítása - nagyon halvány vonalak
            $this->SetDrawColor(200, 200, 200);
            $this->SetLineWidth(0.1);
            
            // Táblázatos elrendezés kezdete
            $startY = $this->GetY();
            
            // Bal oldali táblázat - Alapadatok
            $this->SetXY(15, $startY);
            $this->SetFont('DejaVu', 'B', 10);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(245, 245, 245);
            $this->Cell(85, 7, ' Alapadatok', 1, 1, 'L', 1);
            
            // Alapadatok tartalom
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(252, 252, 252);
            $this->SetFont('DejaVu', '', 9);
            
            $data = [
                ['Felvétel:', $this->formatDate($worksheet['created_at'], 'Y.m.d H:i')],
                ['Telephely:', $worksheet['location_name'] ?? 'Főszerviz'],
                ['Szerelő:', $worksheet['technician_name'] ?? 'Rendszer Admin'],
                ['Javítás típus:', $worksheet['repair_type_name'] ?? 'Javítás'],
                ['Határidő:', $this->formatDate($worksheet['warranty_date'] ?? '', 'Y.m.d')]
            ];
            
            $i = 0;
            foreach ($data as $row) {
                $this->SetX(15);
                $fill = ($i % 2 == 1) ? 1 : 0;
                $this->Cell(28, 6, $row[0], 1, 0, 'L', $fill);
                $this->SetFont('DejaVu', 'B', 9);
                $this->Cell(57, 6, $row[1], 1, 1, 'L', $fill);
                $this->SetFont('DejaVu', '', 9);
                $i++;
            }
            
            $bottomY1 = $this->GetY();
            
            // Jobb oldali táblázat - Ügyfél adatok
            $this->SetXY(105, $startY);
            $this->SetFont('DejaVu', 'B', 10);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(245, 245, 245);
            $this->Cell(85, 7, ' Ügyfél adatok', 1, 1, 'L', 1);
            
            // Ügyfél adatok tartalom
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('DejaVu', '', 9);
            
            $customerData = [
                ['Név:', $worksheet['customer_name'] ?? '-'],
                ['Telefon:', $worksheet['customer_phone'] ?? '-']
            ];
            
            if (!empty($worksheet['customer_email'])) {
                $customerData[] = ['Email:', $worksheet['customer_email']];
            }
            if (!empty($worksheet['customer_address'])) {
                $customerData[] = ['Cím:', $worksheet['customer_address']];
            }
            
            // Üres sorokkal feltöltjük
            while (count($customerData) < 5) {
                $customerData[] = ['', ''];
            }
            
            $i = 0;
            foreach ($customerData as $row) {
                $this->SetX(105);
                $fill = ($i % 2 == 1) ? 1 : 0;
                if (!empty($row[0])) {
                    $this->Cell(28, 6, $row[0], 1, 0, 'L', $fill);
                    $this->SetFont('DejaVu', 'B', 9);
                    $this->Cell(57, 6, $row[1], 1, 1, 'L', $fill);
                    $this->SetFont('DejaVu', '', 9);
                } else {
                    $this->Cell(85, 6, '', 1, 1, 'L', $fill);
                }
                $i++;
            }
            
            // Eszköz adatok
            $this->SetY($bottomY1 + 5);
            $this->SetX(15);
            $this->SetFont('DejaVu', 'B', 10);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(245, 245, 245);
            $this->Cell(85, 7, ' Eszköz adatok', 1, 1, 'L', 1);
            
            // Eszköz adatok tartalom
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('DejaVu', '', 9);
            
            $deviceData = [
                ['Eszköz:', $worksheet['device_name'] ?? '-'],
                ['Gyári szám:', $worksheet['serial_number'] ?? '-'],
                ['Tartozékok:', $worksheet['accessories'] ?? '-']
            ];
            
            $i = 0;
            foreach ($deviceData as $row) {
                $this->SetX(15);
                $fill = ($i % 2 == 1) ? 1 : 0;
                $this->Cell(28, 6, $row[0], 1, 0, 'L', $fill);
                $this->SetFont('DejaVu', 'B', 9);
                $this->Cell(57, 6, $row[1], 1, 1, 'L', $fill);
                $this->SetFont('DejaVu', '', 9);
                $i++;
            }
            
            // Hibaleírás
            $this->Ln(8);
            $this->SetFont('DejaVu', 'B', 11);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(0, 7, 'Hibaleírás:', 0, 1);
            
            $this->SetFont('DejaVu', '', 9);
            $this->SetFillColor(255, 255, 255);
            $this->SetDrawColor(210, 210, 210);
            
            $description = $worksheet['description'] ?? 'Nincs megadva';
            $this->MultiCell(180, 5, $description, 1, 'L', 0);
            
            // Költségek táblázat
            $this->Ln(8);
            $this->SetFont('DejaVu', 'B', 11);
            $this->Cell(0, 7, 'Költségek:', 0, 1);
            
            // Táblázat fejléc
            $this->SetFont('DejaVu', 'B', 9);
            $this->SetFillColor(245, 245, 245);
            $this->SetTextColor(0, 0, 0);
            $this->SetDrawColor(200, 200, 200);
            
            $this->Cell(90, 7, 'Megnevezés', 1, 0, 'L', 1);
            $this->Cell(25, 7, 'Menny.', 1, 0, 'C', 1);
            $this->Cell(35, 7, 'Egységár', 1, 0, 'R', 1);
            $this->Cell(30, 7, 'Összesen', 1, 1, 'R', 1);
            
            // Táblázat sorok
            $this->SetFont('DejaVu', '', 9);
            $this->SetFillColor(252, 252, 252);
            $displayedTotal = 0;
            $rowCount = 0;
            
            if (!empty($items)) {
                $publicItems = array_filter($items, function($item) {
                    return !isset($item['is_internal']) || $item['is_internal'] == 0;
                });
                
                foreach ($publicItems as $item) {
                    $itemTotal = $item['total_price'] ?? 0;
                    $discount = $item['discount'] ?? 0;
                    
                    $itemName = $item['name'] ?? '-';
                    if ($discount > 0) {
                        $itemName .= ' (-' . $discount . '%)';
                    }
                    
                    // Váltakozó háttérszín
                    $fill = ($rowCount % 2 == 1) ? 1 : 0;
                    
                    $this->Cell(90, 6, $itemName, 1, 0, 'L', $fill);
                    $this->Cell(25, 6, ($item['quantity'] ?? '0') . ' ' . ($item['unit'] ?? 'db'), 1, 0, 'C', $fill);
                    $this->Cell(35, 6, number_format($item['unit_price'] ?? 0, 0, ',', ' ') . ' Ft', 1, 0, 'R', $fill);
                    $this->Cell(30, 6, number_format($itemTotal, 0, ',', ' ') . ' Ft', 1, 1, 'R', $fill);
                    
                    $displayedTotal += $itemTotal;
                    $rowCount++;
                }
            }
            
            // Ha nincs tétel
            if ($displayedTotal == 0) {
                $this->SetTextColor(60, 60, 60);
                $this->SetFont('DejaVu', 'I', 9);
                $this->Cell(90, 6, 'Még nincs tétel rögzítve', 1, 0, 'L', 0);
                $this->Cell(25, 6, '-', 1, 0, 'C', 0);
                $this->Cell(35, 6, '-', 1, 0, 'R', 0);
                $this->Cell(30, 6, '0 Ft', 1, 1, 'R', 0);
                $this->SetFont('DejaVu', '', 9);
                $this->SetTextColor(0, 0, 0);
            }
            
            // Összesen sor
            $this->SetFont('DejaVu', 'B', 10);
            $this->SetFillColor(245, 245, 245);
            $this->Cell(150, 7, 'Összesen:', 1, 0, 'R', 1);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(30, 7, number_format($displayedTotal, 0, ',', ' ') . ' Ft', 1, 1, 'R', 1);
            
            // Megjegyzés blokk
            $this->Ln(10);
            $this->SetFont('DejaVu', '', 8);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(252, 252, 252);
            $this->SetDrawColor(220, 220, 220);
            
            $noteText = "Megrendelési feltételek: A szerelési munkát a megrendelő megbízásából végezzük. A javítás során keletkezett hulladék elszállításáról a megrendelő gondoskodik.";
            $this->MultiCell(180, 4, $noteText, 1, 'C', 1);
            
            // Aláírások
            $this->Ln(20);
            $this->SetFont('DejaVu', '', 9);
            $this->SetTextColor(0, 0, 0);
            $this->SetDrawColor(180, 180, 180);
            $this->SetLineWidth(0.1);
            
            // Aláírás vonalak
            $this->Line(30, $this->GetY(), 80, $this->GetY());
            $this->Line(130, $this->GetY(), 180, $this->GetY());
            
            $this->Ln(2);
            $this->Cell(90, 5, 'Ügyfél aláírása', 0, 0, 'C');
            $this->Cell(90, 5, 'Szerviz aláírása', 0, 1, 'C');
            
            // Output
            $filename = 'munkalap_' . $worksheet['worksheet_number'] . '.pdf';
            $this->Output($filename, 'I');
            
        } catch (\Exception $e) {
            header('Content-Type: text/html; charset=utf-8');
            echo '<h1>PDF generálási hiba:</h1>';
            echo '<pre>' . $e->getMessage() . '</pre>';
            echo '<h2>Stack trace:</h2>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
            die();
        }
    }
    
    private function formatDate($date, string $format = 'Y.m.d H:i'): string {
        if (empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
            return date($format);
        }
        return date($format, strtotime($date));
    }
}