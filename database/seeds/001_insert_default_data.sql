-- database/seeds/001_insert_default_data.sql

-- Szerepkörök
INSERT INTO `SZE_roles` (`name`, `display_name`, `description`) VALUES
('admin', 'Adminisztrátor', 'Teljes hozzáférés a rendszerhez'),
('technician', 'Szerelő', 'Munkalapok kezelése, ügyfelek és eszközök kezelése'),
('viewer', 'Megtekintő', 'Csak olvasási jogosultság');

-- Jogosultságok
INSERT INTO `SZE_permissions` (`name`, `display_name`, `category`) VALUES
-- Munkalap jogosultságok
('worksheet.view', 'Munkalapok megtekintése', 'worksheet'),
('worksheet.create', 'Munkalap létrehozása', 'worksheet'),
('worksheet.edit', 'Munkalap szerkesztése', 'worksheet'),
('worksheet.delete', 'Munkalap törlése', 'worksheet'),
('worksheet.print', 'Munkalap nyomtatása', 'worksheet'),
('worksheet.export', 'Munkalapok exportálása', 'worksheet'),
-- Ügyfél jogosultságok
('customer.view', 'Ügyfelek megtekintése', 'customer'),
('customer.create', 'Ügyfél létrehozása', 'customer'),
('customer.edit', 'Ügyfél szerkesztése', 'customer'),
('customer.delete', 'Ügyfél törlése', 'customer'),
-- Eszköz jogosultságok
('device.view', 'Eszközök megtekintése', 'device'),
('device.create', 'Eszköz létrehozása', 'device'),
('device.edit', 'Eszköz szerkesztése', 'device'),
('device.delete', 'Eszköz törlése', 'device'),
-- Alkatrész/szolgáltatás jogosultságok
('part.view', 'Alkatrészek megtekintése', 'part'),
('part.create', 'Alkatrész létrehozása', 'part'),
('part.edit', 'Alkatrész szerkesztése', 'part'),
('part.delete', 'Alkatrész törlése', 'part'),
-- Kimutatás jogosultságok
('report.view', 'Kimutatások megtekintése', 'report'),
('report.export', 'Kimutatások exportálása', 'report'),
-- Admin jogosultságok
('admin.access', 'Admin felület elérése', 'admin'),
('admin.users', 'Felhasználók kezelése', 'admin'),
('admin.settings', 'Beállítások kezelése', 'admin'),
('admin.permissions', 'Jogosultságok kezelése', 'admin');

-- Admin szerepkör - minden jogosultság
INSERT INTO `SZE_role_permissions` (`role_id`, `permission_id`)
SELECT 1, `id` FROM `SZE_permissions`;

-- Szerelő szerepkör jogosultságai
INSERT INTO `SZE_role_permissions` (`role_id`, `permission_id`)
SELECT 2, `id` FROM `SZE_permissions` 
WHERE `name` IN (
    'worksheet.view', 'worksheet.create', 'worksheet.edit', 'worksheet.print',
    'customer.view', 'customer.create', 'customer.edit',
    'device.view', 'device.create', 'device.edit',
    'part.view',
    'report.view'
);

-- Megtekintő szerepkör jogosultságai
INSERT INTO `SZE_role_permissions` (`role_id`, `permission_id`)
SELECT 3, `id` FROM `SZE_permissions` 
WHERE `name` IN (
    'worksheet.view', 'worksheet.print',
    'customer.view',
    'device.view',
    'part.view',
    'report.view'
);

-- Alapértelmezett telephely
INSERT INTO `SZE_locations` (`name`, `address`, `phone`, `is_default`) VALUES
('Főszerviz', 'Budapest, Kossuth u. 1.', '+36 1 234 5678', 1),
('Budai szerviz', 'Budapest, Margit krt. 23.', '+36 1 987 6543', 0);

-- Admin felhasználó (jelszó: admin123)
INSERT INTO `SZE_users` (`username`, `password`, `email`, `full_name`, `phone`, `role_id`, `location_id`) VALUES
('admin', '$2y$10$YQqxLpClxJgRgNW4vQpqsOKxMBmKKrRRGG8EBvkOJvLhqCQQxX5Aq', 'admin@szerviz.hu', 'Rendszer Admin', '+36 30 123 4567', 1, 1);

-- Prioritás típusok
INSERT INTO `SZE_priority_types` (`name`, `color`, `level`) VALUES
('Normál', '#6c757d', 0),
('Sürgős', '#ffc107', 1),
('Nagyon sürgős', '#dc3545', 2);

-- Eszköz állapotok
INSERT INTO `SZE_device_conditions` (`name`, `description`) VALUES
('Új', 'Újszerű állapotú eszköz'),
('Jó', 'Jó állapotú, működőképes'),
('Használt', 'Használt, de működőképes'),
('Sérült', 'Sérült, javításra szorul'),
('Hibás', 'Nem működőképes');

-- Javítás típusok
INSERT INTO `SZE_repair_types` (`name`, `description`) VALUES
('Garanciális', 'Garanciális javítás'),
('Fizetős', 'Normál fizetős javítás'),
('Szerviz', 'Rendszeres szerviz, karbantartás'),
('Átvizsgálás', 'Általános átvizsgálás, diagnosztika');

-- Státusz típusok
INSERT INTO `SZE_status_types` (`name`, `color`, `is_closed`, `sort_order`) VALUES
('Felvéve', '#17a2b8', 0, 1),
('Bevizsgálás alatt', '#ffc107', 0, 2),
('Alkatrészre vár', '#6f42c1', 0, 3),
('Javítás alatt', '#fd7e14', 0, 4),
('Tesztelés', '#20c997', 0, 5),
('Kész', '#28a745', 1, 6),
('Átadva', '#6c757d', 1, 7),
('Visszahozva', '#dc3545', 0, 8);

-- Példa alkatrészek és szolgáltatások
INSERT INTO `SZE_parts_services` (`name`, `sku`, `type`, `unit`, `price`, `description`) VALUES
-- Alkatrészek
('Belső gumi 26"', 'GUMI-26', 'part', 'db', 2500.00, 'Elektromos kerékpár belső gumi 26 colos'),
('Külső gumi 26"', 'KULSO-26', 'part', 'db', 8500.00, 'Elektromos kerékpár külső gumi 26 colos'),
('Fékbetét szett', 'FEK-001', 'part', 'szett', 3500.00, 'Tárcsafék betét szett'),
('Lánc', 'LANC-001', 'part', 'db', 4500.00, 'Kerékpár lánc'),
('Küllő', 'KULLO-001', 'part', 'db', 300.00, 'Kerékpár küllő'),
('Akkumulátor 36V 10Ah', 'AKK-36-10', 'part', 'db', 125000.00, 'Lítium akkumulátor 36V 10Ah'),
('Vezérlő egység', 'VEZERLO-001', 'part', 'db', 45000.00, 'Elektromos vezérlő egység'),
('Kijelző LCD', 'LCD-001', 'part', 'db', 18000.00, 'LCD kijelző sebességmérővel'),
('Gázkar', 'GAZ-001', 'part', 'db', 8500.00, 'Elektromos gázkar'),
('Motor 250W', 'MOTOR-250', 'part', 'db', 85000.00, 'Elektromos motor 250W'),
-- Szolgáltatások
('Alapszerviz', 'SZERV-001', 'service', 'alkalom', 8500.00, 'Általános átvizsgálás és beállítás'),
('Diagnosztika', 'DIAG-001', 'service', 'alkalom', 5000.00, 'Elektromos rendszer diagnosztika'),
('Gumicsere', 'SZOLG-001', 'service', 'kerék', 2000.00, 'Belső vagy külső gumi csere munkadíja'),
('Fékbeállítás', 'SZOLG-002', 'service', 'alkalom', 3000.00, 'Fékrendszer beállítása'),
('Küllőzés', 'SZOLG-003', 'service', 'kerék', 4000.00, 'Kerék küllőzése, centrírozása'),
('Akkumulátor felújítás', 'SZOLG-004', 'service', 'alkalom', 35000.00, 'Akkumulátor cellák cseréje'),
('Vezérlő javítás', 'SZOLG-005', 'service', 'alkalom', 15000.00, 'Vezérlő elektronika javítása'),
('Motor csere', 'SZOLG-006', 'service', 'alkalom', 12000.00, 'Elektromos motor csere munkadíja'),
('Kábelezés javítás', 'SZOLG-007', 'service', 'óra', 6000.00, 'Elektromos kábelezés javítása'),
('Sürgősségi felár', 'SZOLG-008', 'service', 'alkalom', 5000.00, 'Sürgős javítás felára');

-- Példa ügyfelek
INSERT INTO `SZE_customers` (`name`, `email`, `phone`, `address`, `city`, `postal_code`, `priority_id`) VALUES
('Kovács János', 'kovacs.janos@gmail.com', '+36 20 123 4567', 'Petőfi u. 15.', 'Budapest', '1052', 1),
('Nagy Éva', 'eva.nagy@hotmail.com', '+36 30 234 5678', 'Kossuth tér 8.', 'Budapest', '1055', 1),
('Szabó Kerékpár Kft.', 'info@szabokerekpar.hu', '+36 1 456 7890', 'Rákóczi út 45.', 'Budapest', '1072', 2);

-- Cég adatok hozzáadása
UPDATE `SZE_customers` SET 
    `is_company` = 1,
    `company_name` = 'Szabó Kerékpár Kft.',
    `tax_number` = '12345678-2-41',
    `company_address` = 'Budapest, Rákóczi út 45. 1072'
WHERE `id` = 3;

-- Példa eszközök
INSERT INTO `SZE_devices` (`customer_id`, `name`, `serial_number`, `condition_id`, `accessories`, `purchase_date`, `purchase_price`) VALUES
(1, 'Elektromos kerékpár Crussis', 'CR2021001234', 2, 'Töltő, kulcs', '2021-05-15', 285000.00),
(2, 'E-roller Xiaomi Pro 2', 'XM2022005678', 3, 'Töltő', '2022-03-20', 165000.00),
(3, 'Elektromos kerékpár Kellys', 'KL2020009876', 4, 'Töltő, tartalék belső', '2020-08-10', 320000.00);