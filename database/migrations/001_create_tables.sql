-- database/migrations/001_create_tables.sql

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET COLLATION utf8mb4_unicode_ci;

-- Szerepkörök tábla
CREATE TABLE IF NOT EXISTS `SZE_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ügyfelek tábla
CREATE TABLE IF NOT EXISTS `SZE_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100),
  `phone` varchar(20) NOT NULL,
  `address` varchar(200),
  `city` varchar(100),
  `postal_code` varchar(10),
  `is_company` tinyint(1) NOT NULL DEFAULT '0',
  `company_name` varchar(100),
  `tax_number` varchar(20),
  `company_address` varchar(200),
  `internal_note` text,
  `priority_id` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `priority_id` (`priority_id`),
  KEY `idx_name_phone` (`name`, `phone`),
  CONSTRAINT `fk_customers_priority` FOREIGN KEY (`priority_id`) REFERENCES `SZE_priority_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Eszköz állapotok
CREATE TABLE IF NOT EXISTS `SZE_device_conditions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Eszközök tábla
CREATE TABLE IF NOT EXISTS `SZE_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `serial_number` varchar(50),
  `condition_id` int(11) NOT NULL,
  `accessories` text,
  `purchase_date` date,
  `purchase_price` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial_number` (`serial_number`),
  KEY `customer_id` (`customer_id`),
  KEY `condition_id` (`condition_id`),
  CONSTRAINT `fk_devices_customer` FOREIGN KEY (`customer_id`) REFERENCES `SZE_customers` (`id`),
  CONSTRAINT `fk_devices_condition` FOREIGN KEY (`condition_id`) REFERENCES `SZE_device_conditions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Javítás típusok
CREATE TABLE IF NOT EXISTS `SZE_repair_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Státusz típusok
CREATE TABLE IF NOT EXISTS `SZE_status_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#6c757d',
  `is_closed` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Munkalapok tábla
CREATE TABLE IF NOT EXISTS `SZE_worksheets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `worksheet_number` varchar(20) NOT NULL,
  `location_id` int(11) NOT NULL,
  `technician_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `device_id` int(11),
  `repair_type_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `warranty_date` date NOT NULL,
  `description` text NOT NULL,
  `internal_note` text,
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `worksheet_number` (`worksheet_number`),
  KEY `location_id` (`location_id`),
  KEY `technician_id` (`technician_id`),
  KEY `customer_id` (`customer_id`),
  KEY `device_id` (`device_id`),
  KEY `repair_type_id` (`repair_type_id`),
  KEY `status_id` (`status_id`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_worksheets_location` FOREIGN KEY (`location_id`) REFERENCES `SZE_locations` (`id`),
  CONSTRAINT `fk_worksheets_technician` FOREIGN KEY (`technician_id`) REFERENCES `SZE_users` (`id`),
  CONSTRAINT `fk_worksheets_customer` FOREIGN KEY (`customer_id`) REFERENCES `SZE_customers` (`id`),
  CONSTRAINT `fk_worksheets_device` FOREIGN KEY (`device_id`) REFERENCES `SZE_devices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_worksheets_repair_type` FOREIGN KEY (`repair_type_id`) REFERENCES `SZE_repair_types` (`id`),
  CONSTRAINT `fk_worksheets_status` FOREIGN KEY (`status_id`) REFERENCES `SZE_status_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Alkatrészek/Szolgáltatások tábla
CREATE TABLE IF NOT EXISTS `SZE_parts_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `sku` varchar(50),
  `type` enum('part','service') NOT NULL,
  `unit` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `idx_name_sku` (`name`, `sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Munkalap tételek
CREATE TABLE IF NOT EXISTS `SZE_worksheet_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `worksheet_id` int(11) NOT NULL,
  `part_service_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `worksheet_id` (`worksheet_id`),
  KEY `part_service_id` (`part_service_id`),
  CONSTRAINT `fk_worksheet_items_worksheet` FOREIGN KEY (`worksheet_id`) REFERENCES `SZE_worksheets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_worksheet_items_part_service` FOREIGN KEY (`part_service_id`) REFERENCES `SZE_parts_services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Csatolmányok tábla
CREATE TABLE IF NOT EXISTS `SZE_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `worksheet_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `worksheet_id` (`worksheet_id`),
  KEY `uploaded_by` (`uploaded_by`),
  CONSTRAINT `fk_attachments_worksheet` FOREIGN KEY (`worksheet_id`) REFERENCES `SZE_worksheets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_attachments_user` FOREIGN KEY (`uploaded_by`) REFERENCES `SZE_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Munkalap előzmények
CREATE TABLE IF NOT EXISTS `SZE_worksheet_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `worksheet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `old_status_id` int(11),
  `new_status_id` int(11),
  `note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `worksheet_id` (`worksheet_id`),
  KEY `user_id` (`user_id`),
  KEY `old_status_id` (`old_status_id`),
  KEY `new_status_id` (`new_status_id`),
  CONSTRAINT `fk_history_worksheet` FOREIGN KEY (`worksheet_id`) REFERENCES `SZE_worksheets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_history_user` FOREIGN KEY (`user_id`) REFERENCES `SZE_users` (`id`),
  CONSTRAINT `fk_history_old_status` FOREIGN KEY (`old_status_id`) REFERENCES `SZE_status_types` (`id`),
  CONSTRAINT `fk_history_new_status` FOREIGN KEY (`new_status_id`) REFERENCES `SZE_status_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jogosultságok tábla
CREATE TABLE IF NOT EXISTS `SZE_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Szerepkör-jogosultság kapcsolat
CREATE TABLE IF NOT EXISTS `SZE_role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `fk_role_permissions_role` FOREIGN KEY (`role_id`) REFERENCES `SZE_roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_role_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `SZE_permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Telephelyek tábla
CREATE TABLE IF NOT EXISTS `SZE_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `address` varchar(200),
  `phone` varchar(20),
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Felhasználók tábla
CREATE TABLE IF NOT EXISTS `SZE_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20),
  `role_id` int(11) NOT NULL,
  `location_id` int(11),
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `SZE_roles` (`id`),
  CONSTRAINT `fk_users_location` FOREIGN KEY (`location_id`) REFERENCES `SZE_locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Prioritás típusok
CREATE TABLE IF NOT EXISTS `SZE_priority_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#6c757d',
  `level` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  