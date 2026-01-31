/*
Navicat MySQL Data Transfer

Source Server         : LOCALHOST
Source Server Version : 50724
Source Host           : localhost:3306
Source Database       : hac_renovation

Target Server Type    : MYSQL
Target Server Version : 50724
File Encoding         : 65001

Date: 2026-01-31 05:28:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for clients
-- ----------------------------
DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'México',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of clients
-- ----------------------------
INSERT INTO `clients` VALUES ('1', 'Carlos', 'Rodríguez', 'carlos.rodriguez@email.com', '(514) 555-0201', '1590 Rue Saint-Laurent', 'Montreal', 'Quebec', 'H2X 2T9', 'Canada', 'Cliente interesado en renovación completa de cocina. Prefiere estilo contemporáneo. sdads', '2026-01-26 22:24:43', '2026-01-30 06:18:41');
INSERT INTO `clients` VALUES ('2', 'Patricia', 'Lefebvre', 'patricia.lefebvre@email.com', '(514) 555-0202', '2345 Boulevard Saint-Joseph', 'Montreal', 'Quebec', 'H2J 1M8', 'Canada', 'Remodelación de baño principal. Busca acabados de lujo.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `clients` VALUES ('3', 'Thomas', 'Anderson', 'thomas.anderson@email.com', '(514) 555-0203', '3456 Avenue Mont-Royal', 'Montreal', 'Quebec', 'H2R 1X7', 'Canada', 'Terminación de sótano para área de entretenimiento. Necesita home theater.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `clients` VALUES ('4', 'Élise', 'Bouchard', 'elise.bouchard@email.com', '(514) 555-0204', '4567 Rue Beaubien', 'Montreal', 'Quebec', 'H2G 1C6', 'Canada', 'Construcción de patio con cocina exterior y área de fuego.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `clients` VALUES ('5', 'Marc', 'Pelletier', 'marc.pelletier@email.com', '(514) 555-0205', '5678 Rue Jean-Talon', 'Montreal', 'Quebec', 'H2R 1V8', 'Canada', 'Renovación de cocina con isla central. Presupuesto medio-alto.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `clients` VALUES ('6', 'Sandra', 'Morin', 'sandra.morin@email.com', '(514) 555-0206', '6789 Boulevard Décarie', 'Montreal', 'Quebec', 'H3X 2J9', 'Canada', 'Remodelación de dos baños. Prioridad en durabilidad.  And other think', '2026-01-26 22:24:43', '2026-01-30 06:18:27');
INSERT INTO `clients` VALUES ('7', 'Jean', 'Fortin', 'jean.fortin@email.com', '(514) 555-0207', '7890 Rue Masson', 'Montreal', 'Quebec', 'H1X 3K1', 'Canada', 'Terminación de sótano para oficina en casa y gimnasio.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `clients` VALUES ('8', 'Marie-Claire', 'Bergeron', 'marieclaire.bergeron@email.com', '(514) 555-0208', '8901 Avenue du Parc', 'Montreal', 'Quebec', 'H2X 4L2', 'Canada', 'Patio con pergola y sistema de iluminación LED.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `clients` VALUES ('9', 'Daniel', 'Côté', 'daniel.cote@email.com', '(514) 555-0209', '9012 Rue Saint-Denis', 'Montreal', 'Quebec', 'H2X 3M3', 'Canada', 'Renovación completa de cocina. Necesita gabinetes personalizados.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `clients` VALUES ('10', 'Louise', 'Girard', 'louise.girard@email.com', '(514) 555-0210', '1234 Rue Sherbrooke Ouest', 'Montreal', 'Quebec', 'H3G 1N4', 'Canada', 'Baño principal con características tipo spa. Pisos con calefacción.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `clients` VALUES ('11', 'Pierre', 'Beaulieu', 'pierre.beaulieu@email.com', '(514) 555-0211', '2345 Avenue Papineau', 'Montreal', 'Quebec', 'H2K 4O5', 'Canada', 'Sótano terminado con dormitorio, baño y área de lavandería.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `clients` VALUES ('12', 'Françoise', 'Desjardins', 'francoise.desjardins@email.com', '(514) 555-0212', '3456 Rue Rachel', 'Montreal', 'Quebec', 'H2J 2P6', 'Canada', 'Patio con cocina exterior completa y área de comedor.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `clients` VALUES ('13', 'Michel', 'Lapointe', 'michel.lapointe@email.com', '(514) 555-0213', '4567 Rue Ontario Est', 'Montreal', 'Quebec', 'H2L 1Q7', 'Canada', 'Renovación de cocina estilo moderno. Electrodomésticos de alta gama.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `clients` VALUES ('14', 'Chantal', 'Simard', 'chantal.simard@email.com', '(514) 555-0214', '5678 Boulevard Rosemont', 'Montreal', 'Quebec', 'H1X 2R8', 'Canada', 'Remodelación de baño con ducha tipo lluvia y bañera independiente.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `clients` VALUES ('15', 'André', 'Bélanger', 'andre.belanger@email.com', '(514) 555-0215', '6789 Rue Saint-Hubert', 'Montreal', 'Quebec', 'H2N 1S9', 'Canada', 'Terminación de sótano para suite de invitados completa.', '2026-01-26 22:24:43', '2026-01-26 22:24:43');

-- ----------------------------
-- Table structure for companies
-- ----------------------------
DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acronym` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slogan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rbq_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de licencia RBQ (Régie du bâtiment du Québec)',
  `web_site` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono alternativo',
  `address` text COLLATE utf8mb4_unicode_ci COMMENT 'Dirección completa de la compañía',
  `social_media` json DEFAULT NULL COMMENT 'JSON con 5 redes sociales principales (facebook, instagram, twitter, linkedin, youtube)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `idx_name` (`name`),
  KEY `idx_code` (`code`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of companies
-- ----------------------------
INSERT INTO `companies` VALUES ('1', 'HAC Renovation Inc.', '/public/logo.png', 'HAC', 'Transform Your Space with Us', 'HAC-001', '5843-7187-01', 'https://www.hacrenovation.com', 'contacto@hacrenovation.com', '(438) 989-5253', '(514) 462-7417', null, '{\"twitter\": \"\", \"youtube\": \"\", \"facebook\": \"https://facebook.com/hacrenovation\", \"linkedin\": \"\", \"instagram\": \"https://instagram.com/hacrenovation\"}', '2026-01-23 05:35:02', '2026-01-31 04:26:21');

-- ----------------------------
-- Table structure for company_info
-- ----------------------------
DROP TABLE IF EXISTS `company_info`;
CREATE TABLE `company_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) unsigned NOT NULL,
  `years_experience` int(11) DEFAULT '0' COMMENT 'Años de experiencia',
  `total_projects` int(11) DEFAULT '0' COMMENT 'Total de proyectos completados',
  `total_clients` int(11) DEFAULT '0' COMMENT 'Total de clientes',
  `client_satisfaction` decimal(5,2) DEFAULT '0.00' COMMENT 'Porcentaje de satisfacción (0-100)',
  `team_size` int(11) DEFAULT '0' COMMENT 'Tamaño del equipo',
  `service_areas` json DEFAULT NULL COMMENT 'Áreas de servicio (array de strings)',
  `awards_certifications` json DEFAULT NULL COMMENT 'Premios y certificaciones',
  `featured_stats` json DEFAULT NULL COMMENT 'Estadísticas destacadas personalizables',
  `testimonials_count` int(11) DEFAULT '0' COMMENT 'Cantidad de testimonios',
  `about_text` text COLLATE utf8mb4_unicode_ci COMMENT 'Texto descriptivo sobre la compañía',
  `mission` text COLLATE utf8mb4_unicode_ci COMMENT 'Misión de la compañía',
  `vision` text COLLATE utf8mb4_unicode_ci COMMENT 'Visión de la compañía',
  `values` json DEFAULT NULL COMMENT 'Valores de la compañía (array)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_company_id` (`company_id`),
  KEY `idx_company_id` (`company_id`),
  CONSTRAINT `company_info_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of company_info
-- ----------------------------
INSERT INTO `company_info` VALUES ('1', '1', '5', '20', '0', '60.00', '0', '[\"Montreal\", \"Laval\", \"Longueuil\"]', null, null, '0', 'With years of experience in the construction industry, H.A.C. Renovation Inc. has built a reputation for excellence, reliability, and outstanding craftsmanship. We specialize in transforming residential and commercial spaces into beautiful, functional environments.', 'To deliver exceptional construction and renovation services that exceed our clients expectations while maintaining the highest standards of quality and professionalism.', 'To be the leading construction and renovation company recognized for innovation, craftsmanship, and customer satisfaction in Montreal and surrounding areas.', '[\"Quality\", \"Reliability\", \"Excellence\", \"Customer Focus\", \"Integrity\"]', '2026-01-23 05:35:02', '2026-01-24 16:25:49');

-- ----------------------------
-- Table structure for projects
-- ----------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `quote_id` int(11) unsigned NOT NULL,
  `client_id` int(11) unsigned NOT NULL,
  `project_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('planning','in_progress','on_hold','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'planning',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `actual_cost` decimal(10,2) DEFAULT NULL,
  `progress` tinyint(3) unsigned DEFAULT '0' COMMENT 'Porcentaje de avance 0-100',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `assigned_to` int(11) unsigned DEFAULT NULL COMMENT 'Usuario asignado',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_number` (`project_number`),
  UNIQUE KEY `uk_project_number` (`project_number`),
  KEY `idx_quote_id` (`quote_id`),
  KEY `idx_client_id` (`client_id`),
  KEY `idx_status` (`status`),
  KEY `idx_project_number` (`project_number`),
  KEY `assigned_to` (`assigned_to`),
  KEY `idx_projects_status_assigned` (`status`,`assigned_to`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `projects_ibfk_3` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of projects
-- ----------------------------

-- ----------------------------
-- Table structure for questions
-- ----------------------------
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` int(11) unsigned NOT NULL,
  `question_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_type` enum('text','number','select','radio','checkbox','textarea') COLLATE utf8mb4_unicode_ci DEFAULT 'text',
  `options` json DEFAULT NULL COMMENT 'Opciones para select/radio/checkbox',
  `translations` json DEFAULT NULL COMMENT 'Traducciones en francés (fr) y español (es)',
  `is_required` tinyint(1) DEFAULT '1',
  `order` int(11) DEFAULT '0',
  `form_position` int(11) DEFAULT '1' COMMENT 'Step del wizard donde se muestra la pregunta (1-4)',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_service_id` (`service_id`),
  KEY `idx_order` (`order`),
  KEY `idx_form_position` (`form_position`),
  KEY `idx_is_active` (`is_active`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of questions
-- ----------------------------
INSERT INTO `questions` VALUES ('1', '1', 'What is your main goal for this kitchen?', 'radio', '[\"Refresh the look while keeping the kitchen functional\", \"Upgrade for better flow, storage, and a modern style\", \"Create a custom, design-driven kitchen experience\"]', '{\"es\": \"¿Cuál es su objetivo principal para esta cocina?\", \"fr\": \"Quel est votre objectif principal pour cette cuisine?\"}', '1', '1', '2', '1', '2026-01-23 05:35:02', '2026-01-30 05:58:47');
INSERT INTO `questions` VALUES ('2', '1', 'Are you planning to keep the existing kitchen layout?', 'radio', '[\"Yes, no changes to plumbing or electrical\", \"Minor adjustments (appliance relocation, island addition)\", \"No, I want a full layout redesign\"]', '{\"es\": \"¿Planea mantener el diseño existente de la cocina?\", \"fr\": \"Prévoyez-vous de conserver la disposition existante de la cuisine?\"}', '1', '2', '2', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('3', '1', 'What type of cabinetry are you interested in?', 'radio', '[\"Stock cabinets or refacing existing cabinets\", \"Semi-custom cabinets with upgraded storage options\", \"Fully custom cabinetry built to fit the space\"]', '{\"es\": \"¿Qué tipo de gabinetes le interesan?\", \"fr\": \"Quel type d\'armoires vous intéresse?\"}', '1', '3', '2', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('4', '1', 'What level of finishes do you prefer?', 'radio', '[\"Simple, durable, and cost-effective\", \"Modern finishes with upgraded materials\", \"Luxury finishes and designer details\"]', '{\"es\": \"¿Qué nivel de acabados prefiere?\", \"fr\": \"Quel niveau de finitions préférez-vous?\"}', '1', '4', '3', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('5', '1', 'What type of countertop are you considering?', 'radio', '[\"Laminate or entry-level materials\", \"Quartz or upgraded stone\", \"Natural stone or premium surfaces\"]', '{\"es\": \"¿Qué tipo de encimera está considerando?\", \"fr\": \"Quel type de comptoir envisagez-vous?\"}', '1', '5', '3', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('6', '1', 'Are you planning to add or upgrade any of the following?', 'checkbox', '[\"Kitchen island or peninsula\", \"Soft-close drawers & organizers\", \"Under-cabinet lighting\", \"Built-in pantry or custom storage\"]', '{\"es\": \"¿Planea agregar o actualizar alguno de los siguientes?\", \"fr\": \"Prévoyez-vous d\'ajouter ou de mettre à niveau l\'un des éléments suivants?\"}', '0', '6', '3', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('7', '1', 'What appliance level are you aiming for?', 'radio', '[\"Existing appliances or standard replacements\", \"New appliances with better efficiency and features\", \"Panel-ready or high-end integrated appliances\"]', '{\"es\": \"¿Qué nivel de electrodomésticos busca?\", \"fr\": \"Quel niveau d\'appareils visez-vous?\"}', '1', '7', '4', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('8', '1', 'How important is long-term durability and craftsmanship?', 'radio', '[\"Standard installation is fine\", \"I want quality materials and solid installation\", \"I want premium craftsmanship and long-term value\"]', '{\"es\": \"¿Qué tan importante es la durabilidad a largo plazo y la artesanía?\", \"fr\": \"Quelle est l\'importance de la durabilité à long terme et de l\'artisanat?\"}', '1', '8', '4', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('9', '1', 'Which budget range best aligns with your expectations?', 'radio', '[\"$15,000 – $25,000\", \"$25,000 – $45,000\", \"$45,000 – $80,000+\"]', '{\"es\": \"¿Qué rango de presupuesto se alinea mejor con sus expectativas?\", \"fr\": \"Quelle fourchette budgétaire correspond le mieux à vos attentes?\"}', '1', '9', '4', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('10', '2', 'What is your main goal for this bathroom?', 'radio', '[\"Refresh and modernize it, keeping things simple and functional\", \"Upgrade it for comfort, durability, and a modern look\", \"Create a spa-like bathroom with premium finishes\"]', '{\"es\": \"¿Cuál es su objetivo principal para este baño?\", \"fr\": \"Quel est votre objectif principal pour cette salle de bain?\"}', '1', '10', '2', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('11', '2', 'Are you planning to keep the existing layout?', 'radio', '[\"Yes, no plumbing changes\", \"Possibly small changes (vanity or shower location)\", \"No, I want a full redesign\"]', '{\"es\": \"¿Planea mantener el diseño existente?\", \"fr\": \"Prévoyez-vous de conserver la disposition existante?\"}', '1', '11', '2', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('12', '2', 'What type of shower or tub are you envisioning?', 'radio', '[\"Existing tub or acrylic shower base\", \"Tiled shower with glass enclosure\", \"Custom walk-in shower and/or freestanding tub\"]', '{\"es\": \"¿Qué tipo de ducha o bañera está considerando?\", \"fr\": \"Quel type de douche ou baignoire envisagez-vous?\"}', '1', '12', '2', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('13', '2', 'What level of finishes do you prefer?', 'radio', '[\"Simple, durable, and cost-effective\", \"Modern and stylish with upgraded materials\", \"High-end, luxury, and design-focused\"]', '{\"es\": \"¿Qué nivel de acabados prefiere?\", \"fr\": \"Quel niveau de finitions préférez-vous?\"}', '1', '13', '3', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('14', '2', 'What type of vanity are you interested in?', 'radio', '[\"Standard vanity with laminate top\", \"Semi-custom vanity with quartz countertop\", \"Custom vanity with stone or designer finishes\"]', '{\"es\": \"¿Qué tipo de tocador le interesa?\", \"fr\": \"Quel type de vanité vous intéresse?\"}', '1', '14', '3', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('15', '2', 'Are upgrades like these important to you?', 'checkbox', '[\"Heated floors\", \"Shower niches or built-in storage\", \"Rain shower or upgraded fixtures\", \"Smart mirrors / lighting / steam shower\"]', '{\"es\": \"¿Son importantes para usted mejoras como estas?\", \"fr\": \"Les améliorations comme celles-ci sont-elles importantes pour vous?\"}', '0', '15', '3', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('16', '2', 'How important is long-term durability and waterproofing?', 'radio', '[\"Standard installation is fine\", \"I want proper waterproofing systems\", \"I want top-tier systems and materials\"]', '{\"es\": \"¿Qué tan importante es la durabilidad a largo plazo y la impermeabilización?\", \"fr\": \"Quelle est l\'importance de la durabilité à long terme et de l\'étanchéité?\"}', '1', '16', '4', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('17', '2', 'Which budget range best aligns with your expectations?', 'radio', '[\"$10,000 – $18,000\", \"$18,000 – $30,000\", \"$30,000 – $60,000+\"]', '{\"es\": \"¿Qué rango de presupuesto se alinea mejor con sus expectativas?\", \"fr\": \"Quelle fourchette budgétaire correspond le mieux à vos attentes?\"}', '1', '17', '4', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('18', '3', 'What is the main purpose for finishing your basement?', 'radio', '[\"Entertainment space (home theater, game room)\", \"Living space (bedroom, family room)\", \"Home office or gym\", \"Multi-purpose space\"]', '{\"es\": \"¿Cuál es el propósito principal de terminar su sótano?\", \"fr\": \"Quel est l\'objectif principal de finir votre sous-sol?\"}', '1', '18', '2', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('19', '3', 'What is the approximate square footage of your basement?', 'number', null, '{\"es\": \"¿Cuál es el área aproximada de su sótano? (pies cuadrados)\", \"fr\": \"Quelle est la superficie approximative de votre sous-sol? (pieds carrés)\"}', '1', '19', '2', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('20', '3', 'What areas do you want to include in the finished basement?', 'checkbox', '[\"Bedroom\", \"Bathroom\", \"Kitchenette/Bar\", \"Home theater\", \"Office\", \"Storage\", \"Laundry room\"]', '{\"es\": \"¿Qué áreas desea incluir en el sótano terminado?\", \"fr\": \"Quelles zones souhaitez-vous inclure dans le sous-sol fini?\"}', '1', '20', '3', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('21', '3', 'What level of finishes are you looking for?', 'radio', '[\"Basic finishes for functionality\", \"Mid-range finishes for comfort and style\", \"High-end finishes with premium materials\"]', '{\"es\": \"¿Qué nivel de acabados busca?\", \"fr\": \"Quel niveau de finitions recherchez-vous?\"}', '1', '21', '3', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('22', '3', 'Do you need plumbing or electrical work?', 'checkbox', '[\"New bathroom plumbing\", \"Kitchenette/bar plumbing\", \"Additional electrical outlets\", \"Lighting installation\", \"HVAC modifications\"]', '{\"es\": \"¿Necesita trabajos de plomería o electricidad?\", \"fr\": \"Avez-vous besoin de travaux de plomberie ou d\'électricité?\"}', '0', '22', '4', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('23', '3', 'What is your budget range for this project?', 'select', '[\"Under $20,000\", \"$20,000 - $40,000\", \"$40,000 - $60,000\", \"$60,000 - $100,000\", \"Over $100,000\", \"Not sure\"]', '{\"es\": \"¿Cuál es su rango de presupuesto para este proyecto?\", \"fr\": \"Quel est votre fourchette budgétaire pour ce projet?\"}', '1', '23', '4', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('24', '4', 'What is the approximate size of the patio area? (square feet)', 'number', null, '{\"es\": \"¿Cuál es el tamaño aproximado del área del patio? (pies cuadrados)\", \"fr\": \"Quelle est la taille approximative de la zone de patio? (pieds carrés)\"}', '1', '24', '2', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('25', '4', 'What type of patio are you interested in?', 'radio', '[\"Concrete patio\", \"Pavers or interlocking stones\", \"Natural stone\", \"Wood deck\", \"Composite decking\"]', '{\"es\": \"¿Qué tipo de patio le interesa?\", \"fr\": \"Quel type de patio vous intéresse?\"}', '1', '25', '2', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('26', '4', 'What features would you like to include?', 'checkbox', '[\"Covered area or pergola\", \"Outdoor kitchen or BBQ area\", \"Fire pit or fireplace\", \"Lighting\", \"Privacy screens or fencing\", \"Drainage system\"]', '{\"es\": \"¿Qué características le gustaría incluir?\", \"fr\": \"Quelles fonctionnalités souhaitez-vous inclure?\"}', '0', '26', '3', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('27', '4', 'Do you need any additional features?', 'checkbox', '[\"Retaining walls\", \"Steps or stairs\", \"Pathways\", \"Landscaping\", \"Irrigation system\"]', '{\"es\": \"¿Necesita características adicionales?\", \"fr\": \"Avez-vous besoin de fonctionnalités supplémentaires?\"}', '0', '27', '3', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');
INSERT INTO `questions` VALUES ('28', '4', 'What is your budget range for this project?', 'select', '[\"Under $5,000\", \"$5,000 - $10,000\", \"$10,000 - $20,000\", \"$20,000 - $40,000\", \"Over $40,000\", \"Not sure\"]', '{\"es\": \"¿Cuál es su rango de presupuesto para este proyecto?\", \"fr\": \"Quel est votre fourchette budgétaire pour ce projet?\"}', '1', '28', '4', '0', '2026-01-23 05:35:02', '2026-01-30 05:58:32');

-- ----------------------------
-- Table structure for quotes
-- ----------------------------
DROP TABLE IF EXISTS `quotes`;
CREATE TABLE `quotes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) unsigned NOT NULL,
  `quote_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','pending','sent','accepted','rejected','expired') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `total_amount` decimal(10,2) DEFAULT '0.00',
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT 'MXN',
  `valid_until` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL COMMENT 'Datos adicionales del formulario',
  `created_by` int(11) unsigned DEFAULT NULL COMMENT 'Usuario que creó la cotización',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quote_number` (`quote_number`),
  UNIQUE KEY `uk_quote_number` (`quote_number`),
  KEY `idx_client_id` (`client_id`),
  KEY `idx_status` (`status`),
  KEY `idx_quote_number` (`quote_number`),
  KEY `idx_created_at` (`created_at`),
  KEY `created_by` (`created_by`),
  KEY `idx_quotes_client_status` (`client_id`,`status`),
  KEY `idx_quotes_created_status` (`created_at`,`status`),
  CONSTRAINT `quotes_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `quotes_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of quotes
-- ----------------------------
INSERT INTO `quotes` VALUES ('1', '1', 'QT-20250115-0001', 'sent', '42000.00', 'CAD', '2026-02-25', 'Renovación completa de cocina con estilo contemporáneo. Incluye gabinetes nuevos, encimeras de cuarzo y electrodomésticos modernos.', '{\"style\": \"contemporary\", \"urgency\": \"medium\", \"service_type\": \"Kitchen Renovation\", \"preferred_start\": \"2025-02-20\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('2', '2', 'QT-20250115-0002', 'sent', '48000.00', 'CAD', '2026-02-25', 'Remodelación de baño principal con acabados de lujo. Incluye ducha tipo spa, bañera independiente y pisos con calefacción.', '{\"urgency\": \"medium\", \"finish_level\": \"luxury\", \"service_type\": \"Bathroom Remodel\", \"preferred_start\": \"2025-02-15\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('3', '5', 'QT-20250116-0001', 'sent', '38000.00', 'CAD', '2026-02-25', 'Renovación de cocina con isla central. Gabinetes semi-personalizados y encimeras de cuarzo.', '{\"urgency\": \"low\", \"features\": [\"island\", \"quartz_countertops\"], \"service_type\": \"Kitchen Renovation\", \"preferred_start\": \"2025-03-01\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('4', '9', 'QT-20250117-0001', 'sent', '72000.00', 'CAD', '2026-02-25', 'Renovación completa de cocina con gabinetes personalizados y electrodomésticos de alta gama.', '{\"urgency\": \"medium\", \"cabinet_type\": \"custom\", \"service_type\": \"Kitchen Renovation\", \"preferred_start\": \"2025-03-10\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('5', '13', 'QT-20250118-0001', 'sent', '68000.00', 'CAD', '2026-02-25', 'Renovación de cocina estilo moderno con electrodomésticos de alta gama y acabados premium.', '{\"urgency\": \"medium\", \"service_type\": \"Kitchen Renovation\", \"appliance_level\": \"high-end\", \"preferred_start\": \"2025-03-15\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('6', '3', 'QT-20250115-0003', 'pending', '52000.00', 'CAD', '2026-02-25', 'Terminación de sótano para área de entretenimiento con home theater. Área total: 900 sq ft.', '{\"urgency\": \"low\", \"features\": [\"home_theater\"], \"square_feet\": 900, \"service_type\": \"Basement Finishing\", \"preferred_start\": \"2025-02-25\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('7', '6', 'QT-20250116-0002', 'pending', '35000.00', 'CAD', '2026-02-25', 'Remodelación de dos baños. Prioridad en durabilidad y materiales de calidad.', '{\"urgency\": \"low\", \"service_type\": \"Bathroom Remodel\", \"bathroom_count\": 2, \"preferred_start\": \"2025-03-05\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('8', '4', 'QT-20250117-0002', 'pending', '28000.00', 'CAD', '2026-02-25', 'Construcción de patio con cocina exterior y área de fuego. Área: 450 sq ft.', '{\"urgency\": \"low\", \"features\": [\"outdoor_kitchen\", \"fire_pit\"], \"square_feet\": 450, \"service_type\": \"Patio Construction\", \"preferred_start\": \"2025-04-15\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('9', '8', 'QT-20250118-0002', 'pending', '22000.00', 'CAD', '2026-02-25', 'Patio con pergola y sistema de iluminación LED. Área: 350 sq ft.', '{\"urgency\": \"low\", \"features\": [\"pergola\", \"led_lighting\"], \"square_feet\": 350, \"service_type\": \"Patio Construction\", \"preferred_start\": \"2025-05-01\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('10', '10', 'QT-20250119-0001', 'pending', '45000.00', 'CAD', '2026-02-25', 'Baño principal con características tipo spa. Incluye pisos con calefacción, ducha tipo lluvia y bañera independiente.', '{\"urgency\": \"medium\", \"features\": [\"heated_floors\", \"rain_shower\", \"freestanding_tub\"], \"service_type\": \"Bathroom Remodel\", \"preferred_start\": \"2025-03-20\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('11', '7', 'QT-20250110-0001', 'accepted', '55000.00', 'CAD', '2026-02-25', 'Terminación de sótano para oficina en casa y gimnasio. Área: 750 sq ft.', '{\"urgency\": \"high\", \"features\": [\"home_office\", \"gym\"], \"square_feet\": 750, \"service_type\": \"Basement Finishing\", \"preferred_start\": \"2025-02-01\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('12', '11', 'QT-20250112-0001', 'accepted', '48000.00', 'CAD', '2026-02-25', 'Sótano terminado con dormitorio, baño y área de lavandería. Área: 850 sq ft.', '{\"urgency\": \"medium\", \"features\": [\"bedroom\", \"bathroom\", \"laundry\"], \"square_feet\": 850, \"service_type\": \"Basement Finishing\", \"preferred_start\": \"2025-02-10\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('13', '12', 'QT-20250113-0001', 'accepted', '35000.00', 'CAD', '2026-02-25', 'Patio con cocina exterior completa y área de comedor. Área: 500 sq ft.', '{\"urgency\": \"low\", \"features\": [\"outdoor_kitchen\", \"dining_area\"], \"square_feet\": 500, \"service_type\": \"Patio Construction\", \"preferred_start\": \"2025-04-20\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('14', '14', 'QT-20250114-0001', 'accepted', '42000.00', 'CAD', '2026-02-25', 'Remodelación de baño con ducha tipo lluvia y bañera independiente. Acabados premium.', '{\"urgency\": \"medium\", \"features\": [\"rain_shower\", \"freestanding_tub\", \"premium_finishes\"], \"service_type\": \"Bathroom Remodel\", \"preferred_start\": \"2025-02-28\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('15', '15', 'QT-20250114-0002', 'accepted', '62000.00', 'CAD', '2026-02-25', 'Terminación de sótano para suite de invitados completa. Incluye dormitorio, baño y área de estar.', '{\"urgency\": \"medium\", \"features\": [\"guest_suite\", \"bedroom\", \"bathroom\", \"living_area\"], \"square_feet\": 950, \"service_type\": \"Basement Finishing\", \"preferred_start\": \"2025-02-15\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('16', '1', 'QT-20250120-0001', 'draft', '15000.00', 'CAD', '2026-02-25', 'Actualización menor de cocina. Solo gabinetes y pintura.', '{\"scope\": \"minor_update\", \"urgency\": \"low\", \"service_type\": \"Kitchen Renovation\", \"preferred_start\": \"2025-04-01\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('17', '3', 'QT-20250120-0002', 'draft', '18000.00', 'CAD', '2026-02-25', 'Remodelación básica de baño. Presupuesto ajustado.', '{\"urgency\": \"low\", \"budget_level\": \"basic\", \"service_type\": \"Bathroom Remodel\", \"preferred_start\": \"2025-04-10\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('18', '5', 'QT-20250105-0001', 'rejected', '50000.00', 'CAD', '2026-01-16', 'Cliente decidió posponer el proyecto. Presupuesto fuera de rango.', '{\"postponed\": true, \"service_type\": \"Kitchen Renovation\", \"rejection_reason\": \"budget_out_of_range\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('19', '6', 'QT-20250108-0001', 'rejected', '32000.00', 'CAD', '2026-01-19', 'Cliente eligió otro contratista.', '{\"service_type\": \"Bathroom Remodel\", \"rejection_reason\": \"chose_other_contractor\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('20', '4', 'QT-20241220-0001', 'expired', '24000.00', 'CAD', '2026-01-11', 'Cotización expirada. Cliente no respondió a tiempo.', '{\"expired\": true, \"service_type\": \"Patio Construction\", \"follow_up_needed\": true}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');
INSERT INTO `quotes` VALUES ('21', '8', 'QT-20241225-0001', 'expired', '19000.00', 'CAD', '2026-01-06', 'Cotización expirada sin respuesta del cliente.', '{\"expired\": true, \"service_type\": \"Patio Construction\"}', '1', '2026-01-26 22:24:43', '2026-01-26 22:24:43');

-- ----------------------------
-- Table structure for quote_answers
-- ----------------------------
DROP TABLE IF EXISTS `quote_answers`;
CREATE TABLE `quote_answers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `quote_id` int(11) unsigned NOT NULL,
  `question_id` int(11) unsigned NOT NULL,
  `answer_text` text COLLATE utf8mb4_unicode_ci,
  `answer_value` decimal(10,2) DEFAULT NULL,
  `answer_json` json DEFAULT NULL COMMENT 'Para respuestas complejas (arrays, objetos)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_quote_question` (`quote_id`,`question_id`),
  KEY `idx_quote_id` (`quote_id`),
  KEY `idx_question_id` (`question_id`),
  CONSTRAINT `quote_answers_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `quote_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of quote_answers
-- ----------------------------

-- ----------------------------
-- Table structure for services
-- ----------------------------
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `idx_name` (`name`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of services
-- ----------------------------
INSERT INTO `services` VALUES ('1', 'Kitchen Renovation', 'Complete kitchen remodeling and renovation services', '1', '2026-01-23 05:35:02', '2026-01-23 05:35:02');
INSERT INTO `services` VALUES ('2', 'Bathroom Remodel', 'Bathroom transformation and remodeling services', '1', '2026-01-23 05:35:02', '2026-01-23 05:35:02');
INSERT INTO `services` VALUES ('3', 'Basement Finishing', 'Basement finishing and conversion services', '1', '2026-01-23 05:35:02', '2026-01-29 07:39:40');
INSERT INTO `services` VALUES ('4', 'Patio Construction', 'Custom patio construction and outdoor living spaces', '1', '2026-01-23 05:35:02', '2026-01-29 07:57:55');

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `last_activity` int(11) unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_last_activity` (`last_activity`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sessions
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','manager','viewer') COLLATE utf8mb4_unicode_ci DEFAULT 'viewer',
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', 'admin@hacrenovation.com', '$2y$10$35objgHdpKx.lnZFbwyPou5OkcA2cHdDnnwjkNoqfCDP2nIzP9x/G', 'Administrador', 'admin', '1', '2026-01-26 21:54:37', '2026-01-23 05:35:02', '2026-01-26 22:54:37');

-- ----------------------------
-- View structure for v_projects_with_details
-- ----------------------------
DROP VIEW IF EXISTS `v_projects_with_details`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`  VIEW `v_projects_with_details` AS SELECT 
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
LEFT JOIN users u ON p.assigned_to = u.id ;

-- ----------------------------
-- View structure for v_quotes_with_client
-- ----------------------------
DROP VIEW IF EXISTS `v_quotes_with_client`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`  VIEW `v_quotes_with_client` AS SELECT 
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
INNER JOIN clients c ON q.client_id = c.id ;

-- ----------------------------
-- Procedure structure for sp_generate_project_number
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_generate_project_number`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_generate_project_number`(OUT project_number VARCHAR(50))
BEGIN
    DECLARE new_number VARCHAR(50);
    DECLARE counter INT DEFAULT 1;
    
    SET new_number = CONCAT('PRJ-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(counter, 4, '0'));
    
    WHILE EXISTS(SELECT 1 FROM projects WHERE project_number = new_number) DO
        SET counter = counter + 1;
        SET new_number = CONCAT('PRJ-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(counter, 4, '0'));
    END WHILE;
    
    SET project_number = new_number;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_generate_quote_number
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_generate_quote_number`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_generate_quote_number`(OUT quote_number VARCHAR(50))
BEGIN
    DECLARE new_number VARCHAR(50);
    DECLARE counter INT DEFAULT 1;
    
    SET new_number = CONCAT('QT-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(counter, 4, '0'));
    
    WHILE EXISTS(SELECT 1 FROM quotes WHERE quote_number = new_number) DO
        SET counter = counter + 1;
        SET new_number = CONCAT('QT-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(counter, 4, '0'));
    END WHILE;
    
    SET quote_number = new_number;
END
;;
DELIMITER ;
