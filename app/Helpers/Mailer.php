<?php
// app/Helpers/Mailer.php

namespace Helpers;

require '/home/timbowor/mwshop.hu/data/phpmailer/src/Exception.php';
require '/home/timbowor/mwshop.hu/data/phpmailer/src/PHPMailer.php';
require '/home/timbowor/mwshop.hu/data/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    
    private PHPMailer $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        // Szerver beállítások
        $this->mail->isSMTP();
        $this->mail->Host = MAIL_HOST;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = MAIL_USERNAME;
        $this->mail->Password = MAIL_PASSWORD;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = MAIL_PORT;
        
        // Karakterkódolás
        $this->mail->CharSet = 'UTF-8';
        
        // Feladó
        $this->mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
    }
    
    public function send(string $to, string $subject, string $body, array $attachments = []): bool {
        try {
            // Címzett
            $this->mail->addAddress($to);
            
            // Tartalom
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $this->wrapInTemplate($body);
            $this->mail->AltBody = strip_tags($body);
            
            // Csatolmányok
            foreach ($attachments as $attachment) {
                if (file_exists($attachment)) {
                    $this->mail->addAttachment($attachment);
                }
            }
            
            // Küldés
            $this->mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log('Mail Error: ' . $this->mail->ErrorInfo);
            return false;
        }
    }
    
    public function sendWorksheetNotification(array $worksheet, string $type = 'created'): bool {
        if (empty($worksheet['customer_email'])) {
            return false;
        }
        
        $subject = '';
        $template = '';
        
        switch ($type) {
            case 'created':
                $subject = 'Munkalap létrehozva - ' . $worksheet['worksheet_number'];
                $template = 'worksheet_created';
                break;
                
            case 'status_changed':
                $subject = 'Státusz változás - ' . $worksheet['worksheet_number'];
                $template = 'worksheet_status_changed';
                break;
                
            case 'completed':
                $subject = 'Javítás elkészült - ' . $worksheet['worksheet_number'];
                $template = 'worksheet_completed';
                break;
        }
        
        $body = $this->renderEmailTemplate($template, ['worksheet' => $worksheet]);
        
        return $this->send($worksheet['customer_email'], $subject, $body);
    }
    
    private function wrapInTemplate(string $content): string {
        return '
        <!DOCTYPE html>
        <html lang="hu">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background: #fff;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                .header {
                    background: #667eea;
                    color: #fff;
                    padding: 20px;
                    text-align: center;
                    border-radius: 5px 5px 0 0;
                    margin: -20px -20px 20px -20px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .content {
                    padding: 20px 0;
                }
                .footer {
                    text-align: center;
                    padding: 20px 0;
                    color: #666;
                    font-size: 12px;
                    border-top: 1px solid #eee;
                    margin-top: 20px;
                }
                .button {
                    display: inline-block;
                    padding: 10px 20px;
                    background: #667eea;
                    color: #fff;
                    text-decoration: none;
                    border-radius: 5px;
                    margin: 10px 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                th, td {
                    padding: 10px;
                    text-align: left;
                    border-bottom: 1px solid #eee;
                }
                th {
                    background: #f8f9fa;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>' . APP_NAME . '</h1>
                    <p>Elektromos Kerékpár és Roller Szerviz</p>
                </div>
                <div class="content">
                    ' . $content . '
                </div>
                <div class="footer">
                    <p>Ez egy automatikus üzenet, kérjük ne válaszoljon rá.</p>
                    <p>&copy; ' . date('Y') . ' ' . APP_NAME . '</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function renderEmailTemplate(string $template, array $data): string {
        extract($data);
        
        ob_start();
        
        switch ($template) {
            case 'worksheet_created':
                ?>
                <h2>Tisztelt <?= htmlspecialchars($worksheet['customer_name']) ?>!</h2>
                
                <p>Értesítjük, hogy munkalapját sikeresen rögzítettük rendszerünkben.</p>
                
                <table>
                    <tr>
                        <th>Munkalap szám:</th>
                        <td><strong><?= htmlspecialchars($worksheet['worksheet_number']) ?></strong></td>
                    </tr>
                    <tr>
                        <th>Felvétel időpontja:</th>
                        <td><?= date('Y.m.d H:i', strtotime($worksheet['created_at'])) ?></td>
                    </tr>
                    <?php if ($worksheet['device_name']): ?>
                    <tr>
                        <th>Eszköz:</th>
                        <td><?= htmlspecialchars($worksheet['device_name']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Vállalási határidő:</th>
                        <td><?= date('Y.m.d', strtotime($worksheet['warranty_date'])) ?></td>
                    </tr>
                    <tr>
                        <th>Hibaleírás:</th>
                        <td><?= nl2br(htmlspecialchars($worksheet['description'])) ?></td>
                    </tr>
                </table>
                
                <p>A javítás előrehaladásáról emailben értesítjük.</p>
                
                <p>Üdvözlettel,<br><?= APP_NAME ?> csapata</p>
                <?php
                break;
                
            case 'worksheet_status_changed':
                ?>
                <h2>Tisztelt <?= htmlspecialchars($worksheet['customer_name']) ?>!</h2>
                
                <p>Értesítjük, hogy <strong><?= htmlspecialchars($worksheet['worksheet_number']) ?></strong> számú munkalapjának státusza megváltozott.</p>
                
                <p>Új státusz: <strong><?= htmlspecialchars($worksheet['status_name']) ?></strong></p>
                
                <?php if ($worksheet['device_name']): ?>
                <p>Eszköz: <?= htmlspecialchars($worksheet['device_name']) ?></p>
                <?php endif; ?>
                
                <p>Amennyiben kérdése van, kérjük keressen minket elérhetőségeinken.</p>
                
                <p>Üdvözlettel,<br><?= APP_NAME ?> csapata</p>
                <?php
                break;
                
            case 'worksheet_completed':
                ?>
                <h2>Tisztelt <?= htmlspecialchars($worksheet['customer_name']) ?>!</h2>
                
                <p>Örömmel értesítjük, hogy <strong><?= htmlspecialchars($worksheet['worksheet_number']) ?></strong> számú munkalapja elkészült!</p>
                
                <?php if ($worksheet['device_name']): ?>
                <p>Eszköz: <?= htmlspecialchars($worksheet['device_name']) ?></p>
                <?php endif; ?>
                
                <?php if ($worksheet['total_price'] > 0): ?>
                <p>Fizetendő összeg: <strong><?= number_format($worksheet['total_price'], 0, ',', ' ') ?> Ft</strong></p>
                <?php endif; ?>
                
                <p>Kérjük, hogy eszközét nyitvatartási időben vegye át szervizünkben.</p>
                
                <p>Nyitvatartás:</p>
                <ul>
                    <li>Hétfő-Péntek: 8:00 - 18:00</li>
                    <li>Szombat: 9:00 - 13:00</li>
                    <li>Vasárnap: Zárva</li>
                </ul>
                
                <p>Üdvözlettel,<br><?= APP_NAME ?> csapata</p>
                <?php
                break;
        }
        
        return ob_get_clean();
    }
}