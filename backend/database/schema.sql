-- =====================================================
-- H.A.C. Renovation - Database Schema
-- MySQL 5.7+ / MariaDB 10.2+
-- =====================================================

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS `hac_renovation` 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `hac_renovation`;

-- =====================================================
-- Tabla: users
-- Usuarios del sistema (administradores)
-- =====================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) DEFAULT NULL,
    `role` ENUM('admin', 'manager', 'viewer') DEFAULT 'viewer',
    `is_active` TINYINT(1) DEFAULT 1,
    `last_login` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_username` (`username`),
    INDEX `idx_email` (`email`),
    INDEX `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: clients
-- Clientes que solicitan cotizaciones
-- =====================================================
CREATE TABLE IF NOT EXISTS `clients` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `city` VARCHAR(100) DEFAULT NULL,
    `state` VARCHAR(50) DEFAULT NULL,
    `zip_code` VARCHAR(10) DEFAULT NULL,
    `country` VARCHAR(50) DEFAULT 'México',
    `notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_email` (`email`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: services
-- Servicios que reemplazan las categorías en questions
-- =====================================================
CREATE TABLE IF NOT EXISTS `services` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_name` (`name`),
    INDEX `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: companies
-- Empresas del sistema
-- =====================================================
CREATE TABLE IF NOT EXISTS `companies` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(200) NOT NULL,
    `logo` VARCHAR(255) DEFAULT NULL,
    `acronym` VARCHAR(20) DEFAULT NULL,
    `slogan` VARCHAR(255) DEFAULT NULL,
    `code` VARCHAR(50) DEFAULT NULL UNIQUE,
    `rbq_number` VARCHAR(50) DEFAULT NULL COMMENT 'Número de licencia RBQ (Régie du bâtiment du Québec)',
    `web_site` VARCHAR(255) DEFAULT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `other_phone_number` VARCHAR(20) DEFAULT NULL COMMENT 'Teléfono alternativo',
    `address` TEXT DEFAULT NULL COMMENT 'Dirección completa de la compañía',
    `social_media` JSON DEFAULT NULL COMMENT 'JSON con 5 redes sociales principales (facebook, instagram, twitter, linkedin, youtube)',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_name` (`name`),
    INDEX `idx_code` (`code`),
    INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: company_info
-- Información adicional y estadísticas de la compañía para el landing page
-- =====================================================
CREATE TABLE IF NOT EXISTS `company_info` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `company_id` INT(11) UNSIGNED NOT NULL,
    `years_experience` INT(11) DEFAULT 0 COMMENT 'Años de experiencia',
    `total_projects` INT(11) DEFAULT 0 COMMENT 'Total de proyectos completados',
    `total_clients` INT(11) DEFAULT 0 COMMENT 'Total de clientes',
    `client_satisfaction` DECIMAL(5,2) DEFAULT 0.00 COMMENT 'Porcentaje de satisfacción (0-100)',
    `team_size` INT(11) DEFAULT 0 COMMENT 'Tamaño del equipo',
    `service_areas` JSON DEFAULT NULL COMMENT 'Áreas de servicio (array de strings)',
    `awards_certifications` JSON DEFAULT NULL COMMENT 'Premios y certificaciones',
    `featured_stats` JSON DEFAULT NULL COMMENT 'Estadísticas destacadas personalizables',
    `testimonials_count` INT(11) DEFAULT 0 COMMENT 'Cantidad de testimonios',
    `about_text` TEXT DEFAULT NULL COMMENT 'Texto descriptivo sobre la compañía',
    `mission` TEXT DEFAULT NULL COMMENT 'Misión de la compañía',
    `vision` TEXT DEFAULT NULL COMMENT 'Visión de la compañía',
    `values` JSON DEFAULT NULL COMMENT 'Valores de la compañía (array)',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_company_id` (`company_id`),
    INDEX `idx_company_id` (`company_id`),
    FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: questions
-- Preguntas del cuestionario de evaluación
-- =====================================================
CREATE TABLE IF NOT EXISTS `questions` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `service_id` INT(11) UNSIGNED NOT NULL,
    `question_text` TEXT NOT NULL,
    `question_type` ENUM('text', 'number', 'select', 'radio', 'checkbox', 'textarea') DEFAULT 'text',
    `options` JSON DEFAULT NULL COMMENT 'Opciones para select/radio/checkbox',
    `translations` JSON DEFAULT NULL COMMENT 'Traducciones en francés (fr) y español (es)',
    `is_required` TINYINT(1) DEFAULT 1,
    `order` INT(11) DEFAULT 0,
    `form_position` INT(11) DEFAULT 1 COMMENT 'Step del wizard donde se muestra la pregunta (1-4)',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_service_id` (`service_id`),
    INDEX `idx_order` (`order`),
    INDEX `idx_form_position` (`form_position`),
    INDEX `idx_is_active` (`is_active`),
    FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: quotes
-- Cotizaciones generadas
-- =====================================================
CREATE TABLE IF NOT EXISTS `quotes` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `client_id` INT(11) UNSIGNED NOT NULL,
    `quote_number` VARCHAR(50) NOT NULL UNIQUE,
    `status` ENUM('draft', 'pending', 'sent', 'accepted', 'rejected', 'expired') DEFAULT 'draft',
    `total_amount` DECIMAL(10,2) DEFAULT 0.00,
    `currency` VARCHAR(3) DEFAULT 'MXN',
    `valid_until` DATE DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `metadata` JSON DEFAULT NULL COMMENT 'Datos adicionales del formulario',
    `created_by` INT(11) UNSIGNED DEFAULT NULL COMMENT 'Usuario que creó la cotización',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_quote_number` (`quote_number`),
    INDEX `idx_client_id` (`client_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_quote_number` (`quote_number`),
    INDEX `idx_created_at` (`created_at`),
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: quote_answers
-- Respuestas del cuestionario asociadas a cada cotización
-- =====================================================
CREATE TABLE IF NOT EXISTS `quote_answers` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `quote_id` INT(11) UNSIGNED NOT NULL,
    `question_id` INT(11) UNSIGNED NOT NULL,
    `answer_text` TEXT DEFAULT NULL,
    `answer_value` DECIMAL(10,2) DEFAULT NULL,
    `answer_json` JSON DEFAULT NULL COMMENT 'Para respuestas complejas (arrays, objetos)',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_quote_question` (`quote_id`, `question_id`),
    INDEX `idx_quote_id` (`quote_id`),
    INDEX `idx_question_id` (`question_id`),
    FOREIGN KEY (`quote_id`) REFERENCES `quotes`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: projects
-- Proyectos derivados de cotizaciones aceptadas
-- =====================================================
CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `quote_id` INT(11) UNSIGNED NOT NULL,
    `client_id` INT(11) UNSIGNED NOT NULL,
    `project_number` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(200) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `status` ENUM('planning', 'in_progress', 'on_hold', 'completed', 'cancelled') DEFAULT 'planning',
    `start_date` DATE DEFAULT NULL,
    `end_date` DATE DEFAULT NULL,
    `budget` DECIMAL(10,2) DEFAULT NULL,
    `actual_cost` DECIMAL(10,2) DEFAULT NULL,
    `progress` TINYINT(3) UNSIGNED DEFAULT 0 COMMENT 'Porcentaje de avance 0-100',
    `notes` TEXT DEFAULT NULL,
    `assigned_to` INT(11) UNSIGNED DEFAULT NULL COMMENT 'Usuario asignado',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_project_number` (`project_number`),
    INDEX `idx_quote_id` (`quote_id`),
    INDEX `idx_client_id` (`client_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_project_number` (`project_number`),
    FOREIGN KEY (`quote_id`) REFERENCES `quotes`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: sessions
-- Sesiones de usuario (opcional, para mejor control)
-- =====================================================
CREATE TABLE IF NOT EXISTS `sessions` (
    `id` VARCHAR(128) NOT NULL,
    `user_id` INT(11) UNSIGNED DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `last_activity` INT(11) UNSIGNED NOT NULL,
    `data` TEXT DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_last_activity` (`last_activity`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Datos iniciales
-- =====================================================

-- Usuario administrador por defecto
-- Password: admin123 (cambiar en producción)
-- Hash generado con password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO `users` (`username`, `email`, `password`, `full_name`, `role`, `is_active`) VALUES
('admin', 'admin@hacrenovation.com', '$2y$10$4usqKQO4v857XbGiclERfO6eeE89125vyJ.VpcS9fpCs8Jr64hPGa', 'Administrador', 'admin', 1)
ON DUPLICATE KEY UPDATE `username`=`username`;

-- Servicios iniciales (Our Work Projects)
INSERT INTO `services` (`name`, `description`, `is_active`) VALUES
('Kitchen Renovation', 'Complete kitchen remodeling and renovation services', 1),
('Bathroom Remodel', 'Bathroom transformation and remodeling services', 1),
('Basement Finishing', 'Basement finishing and conversion services', 1),
('Patio Construction', 'Custom patio construction and outdoor living spaces', 1)
ON DUPLICATE KEY UPDATE `name`=`name`;

-- Empresa de prueba: HAC
INSERT INTO `companies` (`name`, `logo`, `acronym`, `slogan`, `code`, `rbq_number`, `web_site`, `email`, `phone`, `other_phone_number`, `address`, `social_media`) VALUES
('HAC', '/assets/images/logo-hac.png', 'HAC', 'Transformamos espacios, creamos hogares', 'HAC-001', NULL, 'https://www.hacrenovation.com', 'contacto@hacrenovation.com', '+52 55 1234 5678', NULL, NULL, 
'{"facebook": "https://facebook.com/hacrenovation", "instagram": "https://instagram.com/hacrenovation", "twitter": "https://twitter.com/hacrenovation", "linkedin": "https://linkedin.com/company/hacrenovation", "youtube": "https://youtube.com/@hacrenovation"}')
ON DUPLICATE KEY UPDATE `name`=`name`;

-- Información adicional de la compañía
INSERT INTO `company_info` (`company_id`, `years_experience`, `total_projects`, `total_clients`, `client_satisfaction`, `team_size`, `service_areas`, `about_text`, `mission`, `vision`, `values`) VALUES
((SELECT id FROM `companies` WHERE `code` = 'HAC-001' LIMIT 1), 
 15, 0, 0, 0.00, 0, 
 '["Montreal", "Laval", "Longueuil"]',
 'With years of experience in the construction industry, H.A.C. Renovation Inc. has built a reputation for excellence, reliability, and outstanding craftsmanship. We specialize in transforming residential and commercial spaces into beautiful, functional environments.',
 'To deliver exceptional construction and renovation services that exceed our clients expectations while maintaining the highest standards of quality and professionalism.',
 'To be the leading construction and renovation company recognized for innovation, craftsmanship, and customer satisfaction in Montreal and surrounding areas.',
 '["Quality", "Reliability", "Excellence", "Customer Focus", "Integrity"]')
ON DUPLICATE KEY UPDATE `company_id`=`company_id`;

-- Preguntas en inglés con traducciones (fr y es) y form_position
-- Kitchen Renovation Questions
INSERT INTO `questions` (`service_id`, `question_text`, `question_type`, `is_required`, `order`, `form_position`, `translations`, `options`) VALUES
((SELECT id FROM `services` WHERE `name` = 'Kitchen Renovation' LIMIT 1), 
 'What is your main goal for this kitchen?', 
 'radio', 1, 1, 2,
 '{"fr": "Quel est votre objectif principal pour cette cuisine?", "es": "¿Cuál es su objetivo principal para esta cocina?"}',
 '["Refresh the look while keeping the kitchen functional", "Upgrade for better flow, storage, and a modern style", "Create a custom, design-driven kitchen experience"]'),

((SELECT id FROM `services` WHERE `name` = 'Kitchen Renovation' LIMIT 1), 
 'Are you planning to keep the existing kitchen layout?', 
 'radio', 1, 2, 2,
 '{"fr": "Prévoyez-vous de conserver la disposition existante de la cuisine?", "es": "¿Planea mantener el diseño existente de la cocina?"}',
 '["Yes, no changes to plumbing or electrical", "Minor adjustments (appliance relocation, island addition)", "No, I want a full layout redesign"]'),

((SELECT id FROM `services` WHERE `name` = 'Kitchen Renovation' LIMIT 1), 
 'What type of cabinetry are you interested in?', 
 'radio', 1, 3, 2,
 '{"fr": "Quel type d''armoires vous intéresse?", "es": "¿Qué tipo de gabinetes le interesan?"}',
 '["Stock cabinets or refacing existing cabinets", "Semi-custom cabinets with upgraded storage options", "Fully custom cabinetry built to fit the space"]'),

((SELECT id FROM `services` WHERE `name` = 'Kitchen Renovation' LIMIT 1), 
 'What level of finishes do you prefer?', 
 'radio', 1, 4, 3,
 '{"fr": "Quel niveau de finitions préférez-vous?", "es": "¿Qué nivel de acabados prefiere?"}',
 '["Simple, durable, and cost-effective", "Modern finishes with upgraded materials", "Luxury finishes and designer details"]'),

((SELECT id FROM `services` WHERE `name` = 'Kitchen Renovation' LIMIT 1), 
 'What type of countertop are you considering?', 
 'radio', 1, 5, 3,
 '{"fr": "Quel type de comptoir envisagez-vous?", "es": "¿Qué tipo de encimera está considerando?"}',
 '["Laminate or entry-level materials", "Quartz or upgraded stone", "Natural stone or premium surfaces"]'),

((SELECT id FROM `services` WHERE `name` = 'Kitchen Renovation' LIMIT 1), 
 'Are you planning to add or upgrade any of the following?', 
 'checkbox', 0, 6, 3,
 '{"fr": "Prévoyez-vous d''ajouter ou de mettre à niveau l''un des éléments suivants?", "es": "¿Planea agregar o actualizar alguno de los siguientes?"}',
 '["Kitchen island or peninsula", "Soft-close drawers & organizers", "Under-cabinet lighting", "Built-in pantry or custom storage"]'),

((SELECT id FROM `services` WHERE `name` = 'Kitchen Renovation' LIMIT 1), 
 'What appliance level are you aiming for?', 
 'radio', 1, 7, 4,
 '{"fr": "Quel niveau d\'appareils visez-vous?", "es": "¿Qué nivel de electrodomésticos busca?"}',
 '["Existing appliances or standard replacements", "New appliances with better efficiency and features", "Panel-ready or high-end integrated appliances"]'),

((SELECT id FROM `services` WHERE `name` = 'Kitchen Renovation' LIMIT 1), 
 'How important is long-term durability and craftsmanship?', 
 'radio', 1, 8, 4,
 '{"fr": "Quelle est l''importance de la durabilité à long terme et de l''artisanat?", "es": "¿Qué tan importante es la durabilidad a largo plazo y la artesanía?"}',
 '["Standard installation is fine", "I want quality materials and solid installation", "I want premium craftsmanship and long-term value"]'),

((SELECT id FROM `services` WHERE `name` = 'Kitchen Renovation' LIMIT 1), 
 'Which budget range best aligns with your expectations?', 
 'radio', 1, 9, 4,
 '{"fr": "Quelle fourchette budgétaire correspond le mieux à vos attentes?", "es": "¿Qué rango de presupuesto se alinea mejor con sus expectativas?"}',
 '["$15,000 – $25,000", "$25,000 – $45,000", "$45,000 – $80,000+"]'),

-- Bathroom Remodel Questions
((SELECT id FROM `services` WHERE `name` = 'Bathroom Remodel' LIMIT 1), 
 'What is your main goal for this bathroom?', 
 'radio', 1, 10, 2,
 '{"fr": "Quel est votre objectif principal pour cette salle de bain?", "es": "¿Cuál es su objetivo principal para este baño?"}',
 '["Refresh and modernize it, keeping things simple and functional", "Upgrade it for comfort, durability, and a modern look", "Create a spa-like bathroom with premium finishes"]'),

((SELECT id FROM `services` WHERE `name` = 'Bathroom Remodel' LIMIT 1), 
 'Are you planning to keep the existing layout?', 
 'radio', 1, 11, 2,
 '{"fr": "Prévoyez-vous de conserver la disposition existante?", "es": "¿Planea mantener el diseño existente?"}',
 '["Yes, no plumbing changes", "Possibly small changes (vanity or shower location)", "No, I want a full redesign"]'),

((SELECT id FROM `services` WHERE `name` = 'Bathroom Remodel' LIMIT 1), 
 'What type of shower or tub are you envisioning?', 
 'radio', 1, 12, 2,
 '{"fr": "Quel type de douche ou baignoire envisagez-vous?", "es": "¿Qué tipo de ducha o bañera está considerando?"}',
 '["Existing tub or acrylic shower base", "Tiled shower with glass enclosure", "Custom walk-in shower and/or freestanding tub"]'),

((SELECT id FROM `services` WHERE `name` = 'Bathroom Remodel' LIMIT 1), 
 'What level of finishes do you prefer?', 
 'radio', 1, 13, 3,
 '{"fr": "Quel niveau de finitions préférez-vous?", "es": "¿Qué nivel de acabados prefiere?"}',
 '["Simple, durable, and cost-effective", "Modern and stylish with upgraded materials", "High-end, luxury, and design-focused"]'),

((SELECT id FROM `services` WHERE `name` = 'Bathroom Remodel' LIMIT 1), 
 'What type of vanity are you interested in?', 
 'radio', 1, 14, 3,
 '{"fr": "Quel type de vanité vous intéresse?", "es": "¿Qué tipo de tocador le interesa?"}',
 '["Standard vanity with laminate top", "Semi-custom vanity with quartz countertop", "Custom vanity with stone or designer finishes"]'),

((SELECT id FROM `services` WHERE `name` = 'Bathroom Remodel' LIMIT 1), 
 'Are upgrades like these important to you?', 
 'checkbox', 0, 15, 3,
 '{"fr": "Les améliorations comme celles-ci sont-elles importantes pour vous?", "es": "¿Son importantes para usted mejoras como estas?"}',
 '["Heated floors", "Shower niches or built-in storage", "Rain shower or upgraded fixtures", "Smart mirrors / lighting / steam shower"]'),

((SELECT id FROM `services` WHERE `name` = 'Bathroom Remodel' LIMIT 1), 
 'How important is long-term durability and waterproofing?', 
 'radio', 1, 16, 4,
 '{"fr": "Quelle est l''importance de la durabilité à long terme et de l''étanchéité?", "es": "¿Qué tan importante es la durabilidad a largo plazo y la impermeabilización?"}',
 '["Standard installation is fine", "I want proper waterproofing systems", "I want top-tier systems and materials"]'),

((SELECT id FROM `services` WHERE `name` = 'Bathroom Remodel' LIMIT 1), 
 'Which budget range best aligns with your expectations?', 
 'radio', 1, 17, 4,
 '{"fr": "Quelle fourchette budgétaire correspond le mieux à vos attentes?", "es": "¿Qué rango de presupuesto se alinea mejor con sus expectativas?"}',
 '["$10,000 – $18,000", "$18,000 – $30,000", "$30,000 – $60,000+"]'),

-- Basement Finishing Questions
((SELECT id FROM `services` WHERE `name` = 'Basement Finishing' LIMIT 1), 
 'What is the main purpose for finishing your basement?', 
 'radio', 1, 18, 2,
 '{"fr": "Quel est l\'objectif principal de finir votre sous-sol?", "es": "¿Cuál es el propósito principal de terminar su sótano?"}',
 '["Entertainment space (home theater, game room)", "Living space (bedroom, family room)", "Home office or gym", "Multi-purpose space"]'),

((SELECT id FROM `services` WHERE `name` = 'Basement Finishing' LIMIT 1), 
 'What is the approximate square footage of your basement?', 
 'number', 1, 19, 2,
 '{"fr": "Quelle est la superficie approximative de votre sous-sol? (pieds carrés)", "es": "¿Cuál es el área aproximada de su sótano? (pies cuadrados)"}',
 NULL),

((SELECT id FROM `services` WHERE `name` = 'Basement Finishing' LIMIT 1), 
 'What areas do you want to include in the finished basement?', 
 'checkbox', 1, 20, 3,
 '{"fr": "Quelles zones souhaitez-vous inclure dans le sous-sol fini?", "es": "¿Qué áreas desea incluir en el sótano terminado?"}',
 '["Bedroom", "Bathroom", "Kitchenette/Bar", "Home theater", "Office", "Storage", "Laundry room"]'),

((SELECT id FROM `services` WHERE `name` = 'Basement Finishing' LIMIT 1), 
 'What level of finishes are you looking for?', 
 'radio', 1, 21, 3,
 '{"fr": "Quel niveau de finitions recherchez-vous?", "es": "¿Qué nivel de acabados busca?"}',
 '["Basic finishes for functionality", "Mid-range finishes for comfort and style", "High-end finishes with premium materials"]'),

((SELECT id FROM `services` WHERE `name` = 'Basement Finishing' LIMIT 1), 
 'Do you need plumbing or electrical work?', 
 'checkbox', 0, 22, 4,
 '{"fr": "Avez-vous besoin de travaux de plomberie ou d''électricité?", "es": "¿Necesita trabajos de plomería o electricidad?"}',
 '["New bathroom plumbing", "Kitchenette/bar plumbing", "Additional electrical outlets", "Lighting installation", "HVAC modifications"]'),

((SELECT id FROM `services` WHERE `name` = 'Basement Finishing' LIMIT 1), 
 'What is your budget range for this project?', 
 'select', 1, 23, 4,
 '{"fr": "Quel est votre fourchette budgétaire pour ce projet?", "es": "¿Cuál es su rango de presupuesto para este proyecto?"}',
 '["Under $20,000", "$20,000 - $40,000", "$40,000 - $60,000", "$60,000 - $100,000", "Over $100,000", "Not sure"]'),

-- Patio Construction Questions
((SELECT id FROM `services` WHERE `name` = 'Patio Construction' LIMIT 1), 
 'What is the approximate size of the patio area? (square feet)', 
 'number', 1, 24, 2,
 '{"fr": "Quelle est la taille approximative de la zone de patio? (pieds carrés)", "es": "¿Cuál es el tamaño aproximado del área del patio? (pies cuadrados)"}',
 NULL),

((SELECT id FROM `services` WHERE `name` = 'Patio Construction' LIMIT 1), 
 'What type of patio are you interested in?', 
 'radio', 1, 25, 2,
 '{"fr": "Quel type de patio vous intéresse?", "es": "¿Qué tipo de patio le interesa?"}',
 '["Concrete patio", "Pavers or interlocking stones", "Natural stone", "Wood deck", "Composite decking"]'),

((SELECT id FROM `services` WHERE `name` = 'Patio Construction' LIMIT 1), 
 'What features would you like to include?', 
 'checkbox', 0, 26, 3,
 '{"fr": "Quelles fonctionnalités souhaitez-vous inclure?", "es": "¿Qué características le gustaría incluir?"}',
 '["Covered area or pergola", "Outdoor kitchen or BBQ area", "Fire pit or fireplace", "Lighting", "Privacy screens or fencing", "Drainage system"]'),

((SELECT id FROM `services` WHERE `name` = 'Patio Construction' LIMIT 1), 
 'Do you need any additional features?', 
 'checkbox', 0, 27, 3,
 '{"fr": "Avez-vous besoin de fonctionnalités supplémentaires?", "es": "¿Necesita características adicionales?"}',
 '["Retaining walls", "Steps or stairs", "Pathways", "Landscaping", "Irrigation system"]'),

((SELECT id FROM `services` WHERE `name` = 'Patio Construction' LIMIT 1), 
 'What is your budget range for this project?', 
 'select', 1, 28, 4,
 '{"fr": "Quel est votre fourchette budgétaire pour ce projet?", "es": "¿Cuál es su rango de presupuesto para este proyecto?"}',
 '["Under $5,000", "$5,000 - $10,000", "$10,000 - $20,000", "$20,000 - $40,000", "Over $40,000", "Not sure"]')
ON DUPLICATE KEY UPDATE `question_text`=`question_text`;

-- =====================================================
-- Vistas útiles
-- =====================================================

-- Vista: quotes_with_client
CREATE OR REPLACE VIEW `v_quotes_with_client` AS
SELECT 
    q.id,
    q.quote_number,
    q.status,
    q.total_amount,
    q.currency,
    q.valid_until,
    q.created_at,
    c.id AS client_id,
    CONCAT(c.first_name, ' ', c.last_name) AS client_name,
    c.email AS client_email,
    c.phone AS client_phone
FROM quotes q
INNER JOIN clients c ON q.client_id = c.id;

-- Vista: projects_with_details
CREATE OR REPLACE VIEW `v_projects_with_details` AS
SELECT 
    p.id,
    p.project_number,
    p.name,
    p.status,
    p.start_date,
    p.end_date,
    p.budget,
    p.actual_cost,
    p.progress,
    q.quote_number,
    c.id AS client_id,
    CONCAT(c.first_name, ' ', c.last_name) AS client_name,
    u.username AS assigned_user
FROM projects p
INNER JOIN quotes q ON p.quote_id = q.id
INNER JOIN clients c ON p.client_id = c.id
LEFT JOIN users u ON p.assigned_to = u.id;

-- =====================================================
-- Procedimientos almacenados útiles
-- =====================================================

DELIMITER //

-- Generar número de cotización único
DROP PROCEDURE IF EXISTS `sp_generate_quote_number`//
CREATE PROCEDURE `sp_generate_quote_number`(OUT quote_number VARCHAR(50))
BEGIN
    DECLARE new_number VARCHAR(50);
    DECLARE counter INT DEFAULT 1;
    
    SET new_number = CONCAT('QT-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(counter, 4, '0'));
    
    WHILE EXISTS(SELECT 1 FROM quotes WHERE quote_number = new_number) DO
        SET counter = counter + 1;
        SET new_number = CONCAT('QT-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(counter, 4, '0'));
    END WHILE;
    
    SET quote_number = new_number;
END//

-- Generar número de proyecto único
DROP PROCEDURE IF EXISTS `sp_generate_project_number`//
CREATE PROCEDURE `sp_generate_project_number`(OUT project_number VARCHAR(50))
BEGIN
    DECLARE new_number VARCHAR(50);
    DECLARE counter INT DEFAULT 1;
    
    SET new_number = CONCAT('PRJ-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(counter, 4, '0'));
    
    WHILE EXISTS(SELECT 1 FROM projects WHERE project_number = new_number) DO
        SET counter = counter + 1;
        SET new_number = CONCAT('PRJ-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(counter, 4, '0'));
    END WHILE;
    
    SET project_number = new_number;
END//

DELIMITER ;

-- =====================================================
-- Índices adicionales para optimización
-- =====================================================

-- Índices compuestos para consultas comunes
CREATE INDEX `idx_quotes_client_status` ON `quotes`(`client_id`, `status`);
CREATE INDEX `idx_quotes_created_status` ON `quotes`(`created_at`, `status`);
CREATE INDEX `idx_projects_status_assigned` ON `projects`(`status`, `assigned_to`);

-- =====================================================
-- Datos de prueba
-- =====================================================

-- Clientes de prueba
INSERT INTO `clients` (`first_name`, `last_name`, `email`, `phone`, `address`, `city`, `state`, `zip_code`, `country`, `notes`) VALUES
('John', 'Smith', 'john.smith@email.com', '(514) 555-0101', '123 Main Street', 'Montreal', 'Quebec', 'H1A 1A1', 'Canada', 'Interested in kitchen renovation. Prefers modern style.'),
('Marie', 'Dubois', 'marie.dubois@email.com', '(514) 555-0102', '456 Rue Saint-Denis', 'Montreal', 'Quebec', 'H2B 2B2', 'Canada', 'Looking for bathroom remodel. Budget-conscious.'),
('Robert', 'Tremblay', 'robert.tremblay@email.com', '(514) 555-0103', '789 Avenue du Parc', 'Laval', 'Quebec', 'H7A 3C3', 'Canada', 'Wants to finish basement. Needs home office space.'),
('Sophie', 'Gagnon', 'sophie.gagnon@email.com', '(514) 555-0104', '321 Boulevard René-Lévesque', 'Longueuil', 'Quebec', 'J4K 4D4', 'Canada', 'Planning patio construction for summer.'),
('Michael', 'Johnson', 'michael.johnson@email.com', '(514) 555-0105', '654 Rue Sherbrooke', 'Montreal', 'Quebec', 'H3A 5E5', 'Canada', 'Kitchen renovation project. High-end finishes preferred.'),
('Isabelle', 'Martineau', 'isabelle.martineau@email.com', '(514) 555-0106', '987 Rue de la Commune', 'Montreal', 'Quebec', 'H2Y 6F6', 'Canada', 'Bathroom remodel. Wants spa-like experience.'),
('David', 'Brown', 'david.brown@email.com', '(514) 555-0107', '147 Rue Saint-Hubert', 'Montreal', 'Quebec', 'H2N 7G7', 'Canada', 'Basement finishing for entertainment area.'),
('Julie', 'Lavoie', 'julie.lavoie@email.com', '(514) 555-0108', '258 Avenue Papineau', 'Montreal', 'Quebec', 'H2K 8H8', 'Canada', 'Patio construction with outdoor kitchen.'),
('James', 'Wilson', 'james.wilson@email.com', '(514) 555-0109', '369 Rue Ontario', 'Montreal', 'Quebec', 'H2X 9I9', 'Canada', 'Complete kitchen renovation. Needs custom cabinetry.'),
('Amélie', 'Roy', 'amelie.roy@email.com', '(514) 555-0110', '741 Rue Rachel', 'Montreal', 'Quebec', 'H2J 1J1', 'Canada', 'Bathroom upgrade. Wants heated floors.')
ON DUPLICATE KEY UPDATE `email`=`email`;

-- Cotizaciones de prueba
-- Nota: Los quote_number se generan manualmente para evitar conflictos. En producción usar el procedimiento almacenado.
INSERT INTO `quotes` (`client_id`, `quote_number`, `status`, `total_amount`, `currency`, `valid_until`, `notes`, `metadata`, `created_by`) VALUES
((SELECT id FROM `clients` WHERE `email` = 'john.smith@email.com' LIMIT 1), 
 'QT-20241201-0001', 'sent', 35000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Kitchen renovation with modern finishes. Includes new cabinets, countertops, and appliances.',
 '{"service_type": "Kitchen Renovation", "preferred_start": "2024-12-15", "urgency": "medium"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'marie.dubois@email.com' LIMIT 1), 
 'QT-20241201-0002', 'pending', 18000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Bathroom remodel with standard finishes. Budget-friendly options.',
 '{"service_type": "Bathroom Remodel", "preferred_start": "2025-01-10", "urgency": "low"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'robert.tremblay@email.com' LIMIT 1), 
 'QT-20241202-0001', 'accepted', 45000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Basement finishing project. Includes bedroom, bathroom, and home office.',
 '{"service_type": "Basement Finishing", "preferred_start": "2025-01-05", "urgency": "high", "square_feet": 800}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'sophie.gagnon@email.com' LIMIT 1), 
 'QT-20241202-0002', 'draft', 25000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Patio construction with pergola and outdoor kitchen area.',
 '{"service_type": "Patio Construction", "preferred_start": "2025-04-01", "urgency": "low", "square_feet": 400}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'michael.johnson@email.com' LIMIT 1), 
 'QT-20241203-0001', 'sent', 65000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'High-end kitchen renovation with custom cabinetry and premium appliances.',
 '{"service_type": "Kitchen Renovation", "preferred_start": "2025-02-01", "urgency": "medium"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'isabelle.martineau@email.com' LIMIT 1), 
 'QT-20241203-0002', 'pending', 42000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Luxury bathroom remodel with spa features and premium finishes.',
 '{"service_type": "Bathroom Remodel", "preferred_start": "2025-01-20", "urgency": "medium"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'david.brown@email.com' LIMIT 1), 
 'QT-20241204-0001', 'accepted', 38000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Basement finishing for entertainment space with home theater.',
 '{"service_type": "Basement Finishing", "preferred_start": "2025-01-15", "urgency": "medium", "square_feet": 600}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'julie.lavoie@email.com' LIMIT 1), 
 'QT-20241204-0002', 'draft', 32000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Patio with outdoor kitchen, fire pit, and lighting system.',
 '{"service_type": "Patio Construction", "preferred_start": "2025-05-01", "urgency": "low", "square_feet": 500}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'james.wilson@email.com' LIMIT 1), 
 'QT-20241205-0001', 'sent', 55000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Complete kitchen renovation with custom design and high-end materials.',
 '{"service_type": "Kitchen Renovation", "preferred_start": "2025-02-15", "urgency": "high"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'amelie.roy@email.com' LIMIT 1), 
 'QT-20241205-0002', 'rejected', 28000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL -5 DAY), 
 'Bathroom upgrade project. Client decided to postpone.',
 '{"service_type": "Bathroom Remodel", "preferred_start": "2025-03-01", "urgency": "low", "rejection_reason": "Postponed by client"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1))
ON DUPLICATE KEY UPDATE `quote_number`=`quote_number`;

-- Proyectos de prueba (solo para cotizaciones aceptadas)
INSERT INTO `projects` (`quote_id`, `client_id`, `project_number`, `name`, `description`, `status`, `start_date`, `end_date`, `budget`, `actual_cost`, `progress`, `notes`, `assigned_to`) VALUES
((SELECT id FROM `quotes` WHERE `quote_number` = 'QT-20241202-0001' LIMIT 1),
 (SELECT client_id FROM `quotes` WHERE `quote_number` = 'QT-20241202-0001' LIMIT 1),
 'PRJ-20241202-0001', 'Basement Finishing - Tremblay Residence', 
 'Complete basement finishing including bedroom, bathroom, and home office. Total area: 800 sq ft.',
 'in_progress', '2025-01-05', '2025-03-15', 45000.00, 28000.00, 62,
 'Project progressing well. Framing and electrical completed. Currently working on drywall installation.',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `quotes` WHERE `quote_number` = 'QT-20241204-0001' LIMIT 1),
 (SELECT client_id FROM `quotes` WHERE `quote_number` = 'QT-20241204-0001' LIMIT 1),
 'PRJ-20241204-0001', 'Basement Entertainment Space - Brown Residence', 
 'Basement finishing for entertainment area with home theater setup. Total area: 600 sq ft.',
 'planning', '2025-01-15', '2025-04-01', 38000.00, NULL, 0,
 'Project in planning phase. Awaiting final material selections from client.',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1))
ON DUPLICATE KEY UPDATE `project_number`=`project_number`;

-- =====================================================
-- Fin del Schema
-- =====================================================