-- =====================================================
-- H.A.C. Renovation - Fake Data
-- Datos de prueba para desarrollo y testing
-- =====================================================

USE `hac_renovation`;

-- =====================================================
-- CLIENTES DE PRUEBA (15 clientes adicionales)
-- =====================================================

INSERT INTO `clients` (`first_name`, `last_name`, `email`, `phone`, `address`, `city`, `state`, `zip_code`, `country`, `notes`) VALUES
('Carlos', 'Rodríguez', 'carlos.rodriguez@email.com', '(514) 555-0201', '1590 Rue Saint-Laurent', 'Montreal', 'Quebec', 'H2X 2T9', 'Canada', 'Cliente interesado en renovación completa de cocina. Prefiere estilo contemporáneo.'),
('Patricia', 'Lefebvre', 'patricia.lefebvre@email.com', '(514) 555-0202', '2345 Boulevard Saint-Joseph', 'Montreal', 'Quebec', 'H2J 1M8', 'Canada', 'Remodelación de baño principal. Busca acabados de lujo.'),
('Thomas', 'Anderson', 'thomas.anderson@email.com', '(514) 555-0203', '3456 Avenue Mont-Royal', 'Montreal', 'Quebec', 'H2R 1X7', 'Canada', 'Terminación de sótano para área de entretenimiento. Necesita home theater.'),
('Élise', 'Bouchard', 'elise.bouchard@email.com', '(514) 555-0204', '4567 Rue Beaubien', 'Montreal', 'Quebec', 'H2G 1C6', 'Canada', 'Construcción de patio con cocina exterior y área de fuego.'),
('Marc', 'Pelletier', 'marc.pelletier@email.com', '(514) 555-0205', '5678 Rue Jean-Talon', 'Montreal', 'Quebec', 'H2R 1V8', 'Canada', 'Renovación de cocina con isla central. Presupuesto medio-alto.'),
('Sandra', 'Morin', 'sandra.morin@email.com', '(514) 555-0206', '6789 Boulevard Décarie', 'Montreal', 'Quebec', 'H3X 2J9', 'Canada', 'Remodelación de dos baños. Prioridad en durabilidad.'),
('Jean', 'Fortin', 'jean.fortin@email.com', '(514) 555-0207', '7890 Rue Masson', 'Montreal', 'Quebec', 'H1X 3K1', 'Canada', 'Terminación de sótano para oficina en casa y gimnasio.'),
('Marie-Claire', 'Bergeron', 'marieclaire.bergeron@email.com', '(514) 555-0208', '8901 Avenue du Parc', 'Montreal', 'Quebec', 'H2X 4L2', 'Canada', 'Patio con pergola y sistema de iluminación LED.'),
('Daniel', 'Côté', 'daniel.cote@email.com', '(514) 555-0209', '9012 Rue Saint-Denis', 'Montreal', 'Quebec', 'H2X 3M3', 'Canada', 'Renovación completa de cocina. Necesita gabinetes personalizados.'),
('Louise', 'Girard', 'louise.girard@email.com', '(514) 555-0210', '1234 Rue Sherbrooke Ouest', 'Montreal', 'Quebec', 'H3G 1N4', 'Canada', 'Baño principal con características tipo spa. Pisos con calefacción.'),
('Pierre', 'Beaulieu', 'pierre.beaulieu@email.com', '(514) 555-0211', '2345 Avenue Papineau', 'Montreal', 'Quebec', 'H2K 4O5', 'Canada', 'Sótano terminado con dormitorio, baño y área de lavandería.'),
('Françoise', 'Desjardins', 'francoise.desjardins@email.com', '(514) 555-0212', '3456 Rue Rachel', 'Montreal', 'Quebec', 'H2J 2P6', 'Canada', 'Patio con cocina exterior completa y área de comedor.'),
('Michel', 'Lapointe', 'michel.lapointe@email.com', '(514) 555-0213', '4567 Rue Ontario Est', 'Montreal', 'Quebec', 'H2L 1Q7', 'Canada', 'Renovación de cocina estilo moderno. Electrodomésticos de alta gama.'),
('Chantal', 'Simard', 'chantal.simard@email.com', '(514) 555-0214', '5678 Boulevard Rosemont', 'Montreal', 'Quebec', 'H1X 2R8', 'Canada', 'Remodelación de baño con ducha tipo lluvia y bañera independiente.'),
('André', 'Bélanger', 'andre.belanger@email.com', '(514) 555-0215', '6789 Rue Saint-Hubert', 'Montreal', 'Quebec', 'H2N 1S9', 'Canada', 'Terminación de sótano para suite de invitados completa.')
ON DUPLICATE KEY UPDATE `email`=`email`;

-- =====================================================
-- COTIZACIONES DE PRUEBA (25 cotizaciones adicionales)
-- =====================================================

INSERT INTO `quotes` (`client_id`, `quote_number`, `status`, `total_amount`, `currency`, `valid_until`, `notes`, `metadata`, `created_by`) VALUES
-- Cotizaciones SENT (enviadas)
((SELECT id FROM `clients` WHERE `email` = 'carlos.rodriguez@email.com' LIMIT 1), 
 'QT-20250115-0001', 'sent', 42000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Renovación completa de cocina con estilo contemporáneo. Incluye gabinetes nuevos, encimeras de cuarzo y electrodomésticos modernos.',
 '{"service_type": "Kitchen Renovation", "preferred_start": "2025-02-20", "urgency": "medium", "style": "contemporary"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'patricia.lefebvre@email.com' LIMIT 1), 
 'QT-20250115-0002', 'sent', 48000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Remodelación de baño principal con acabados de lujo. Incluye ducha tipo spa, bañera independiente y pisos con calefacción.',
 '{"service_type": "Bathroom Remodel", "preferred_start": "2025-02-15", "urgency": "medium", "finish_level": "luxury"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'marc.pelletier@email.com' LIMIT 1), 
 'QT-20250116-0001', 'sent', 38000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Renovación de cocina con isla central. Gabinetes semi-personalizados y encimeras de cuarzo.',
 '{"service_type": "Kitchen Renovation", "preferred_start": "2025-03-01", "urgency": "low", "features": ["island", "quartz_countertops"]}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'daniel.cote@email.com' LIMIT 1), 
 'QT-20250117-0001', 'sent', 72000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Renovación completa de cocina con gabinetes personalizados y electrodomésticos de alta gama.',
 '{"service_type": "Kitchen Renovation", "preferred_start": "2025-03-10", "urgency": "medium", "cabinet_type": "custom"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'michel.lapointe@email.com' LIMIT 1), 
 'QT-20250118-0001', 'sent', 68000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Renovación de cocina estilo moderno con electrodomésticos de alta gama y acabados premium.',
 '{"service_type": "Kitchen Renovation", "preferred_start": "2025-03-15", "urgency": "medium", "appliance_level": "high-end"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

-- Cotizaciones PENDING (pendientes)
((SELECT id FROM `clients` WHERE `email` = 'thomas.anderson@email.com' LIMIT 1), 
 'QT-20250115-0003', 'pending', 52000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Terminación de sótano para área de entretenimiento con home theater. Área total: 900 sq ft.',
 '{"service_type": "Basement Finishing", "preferred_start": "2025-02-25", "urgency": "low", "square_feet": 900, "features": ["home_theater"]}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'sandra.morin@email.com' LIMIT 1), 
 'QT-20250116-0002', 'pending', 35000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Remodelación de dos baños. Prioridad en durabilidad y materiales de calidad.',
 '{"service_type": "Bathroom Remodel", "preferred_start": "2025-03-05", "urgency": "low", "bathroom_count": 2}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'elise.bouchard@email.com' LIMIT 1), 
 'QT-20250117-0002', 'pending', 28000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Construcción de patio con cocina exterior y área de fuego. Área: 450 sq ft.',
 '{"service_type": "Patio Construction", "preferred_start": "2025-04-15", "urgency": "low", "square_feet": 450, "features": ["outdoor_kitchen", "fire_pit"]}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'marieclaire.bergeron@email.com' LIMIT 1), 
 'QT-20250118-0002', 'pending', 22000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Patio con pergola y sistema de iluminación LED. Área: 350 sq ft.',
 '{"service_type": "Patio Construction", "preferred_start": "2025-05-01", "urgency": "low", "square_feet": 350, "features": ["pergola", "led_lighting"]}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'louise.girard@email.com' LIMIT 1), 
 'QT-20250119-0001', 'pending', 45000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Baño principal con características tipo spa. Incluye pisos con calefacción, ducha tipo lluvia y bañera independiente.',
 '{"service_type": "Bathroom Remodel", "preferred_start": "2025-03-20", "urgency": "medium", "features": ["heated_floors", "rain_shower", "freestanding_tub"]}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

-- Cotizaciones ACCEPTED (aceptadas - para crear proyectos)
((SELECT id FROM `clients` WHERE `email` = 'jean.fortin@email.com' LIMIT 1), 
 'QT-20250110-0001', 'accepted', 55000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Terminación de sótano para oficina en casa y gimnasio. Área: 750 sq ft.',
 '{"service_type": "Basement Finishing", "preferred_start": "2025-02-01", "urgency": "high", "square_feet": 750, "features": ["home_office", "gym"]}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'pierre.beaulieu@email.com' LIMIT 1), 
 'QT-20250112-0001', 'accepted', 48000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Sótano terminado con dormitorio, baño y área de lavandería. Área: 850 sq ft.',
 '{"service_type": "Basement Finishing", "preferred_start": "2025-02-10", "urgency": "medium", "square_feet": 850, "features": ["bedroom", "bathroom", "laundry"]}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'francoise.desjardins@email.com' LIMIT 1), 
 'QT-20250113-0001', 'accepted', 35000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Patio con cocina exterior completa y área de comedor. Área: 500 sq ft.',
 '{"service_type": "Patio Construction", "preferred_start": "2025-04-20", "urgency": "low", "square_feet": 500, "features": ["outdoor_kitchen", "dining_area"]}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'chantal.simard@email.com' LIMIT 1), 
 'QT-20250114-0001', 'accepted', 42000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Remodelación de baño con ducha tipo lluvia y bañera independiente. Acabados premium.',
 '{"service_type": "Bathroom Remodel", "preferred_start": "2025-02-28", "urgency": "medium", "features": ["rain_shower", "freestanding_tub", "premium_finishes"]}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'andre.belanger@email.com' LIMIT 1), 
 'QT-20250114-0002', 'accepted', 62000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Terminación de sótano para suite de invitados completa. Incluye dormitorio, baño y área de estar.',
 '{"service_type": "Basement Finishing", "preferred_start": "2025-02-15", "urgency": "medium", "square_feet": 950, "features": ["guest_suite", "bedroom", "bathroom", "living_area"]}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

-- Cotizaciones DRAFT (borradores)
((SELECT id FROM `clients` WHERE `email` = 'carlos.rodriguez@email.com' LIMIT 1), 
 'QT-20250120-0001', 'draft', 15000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Actualización menor de cocina. Solo gabinetes y pintura.',
 '{"service_type": "Kitchen Renovation", "preferred_start": "2025-04-01", "urgency": "low", "scope": "minor_update"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'thomas.anderson@email.com' LIMIT 1), 
 'QT-20250120-0002', 'draft', 18000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 
 'Remodelación básica de baño. Presupuesto ajustado.',
 '{"service_type": "Bathroom Remodel", "preferred_start": "2025-04-10", "urgency": "low", "budget_level": "basic"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

-- Cotizaciones REJECTED (rechazadas)
((SELECT id FROM `clients` WHERE `email` = 'marc.pelletier@email.com' LIMIT 1), 
 'QT-20250105-0001', 'rejected', 50000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL -10 DAY), 
 'Cliente decidió posponer el proyecto. Presupuesto fuera de rango.',
 '{"service_type": "Kitchen Renovation", "rejection_reason": "budget_out_of_range", "postponed": true}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'sandra.morin@email.com' LIMIT 1), 
 'QT-20250108-0001', 'rejected', 32000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL -7 DAY), 
 'Cliente eligió otro contratista.',
 '{"service_type": "Bathroom Remodel", "rejection_reason": "chose_other_contractor"}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

-- Cotizaciones EXPIRED (expiradas)
((SELECT id FROM `clients` WHERE `email` = 'elise.bouchard@email.com' LIMIT 1), 
 'QT-20241220-0001', 'expired', 24000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL -15 DAY), 
 'Cotización expirada. Cliente no respondió a tiempo.',
 '{"service_type": "Patio Construction", "expired": true, "follow_up_needed": true}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `clients` WHERE `email` = 'marieclaire.bergeron@email.com' LIMIT 1), 
 'QT-20241225-0001', 'expired', 19000.00, 'CAD', DATE_ADD(CURDATE(), INTERVAL -20 DAY), 
 'Cotización expirada sin respuesta del cliente.',
 '{"service_type": "Patio Construction", "expired": true}',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1))
ON DUPLICATE KEY UPDATE `quote_number`=`quote_number`;

-- =====================================================
-- PROYECTOS DE PRUEBA (15 proyectos adicionales)
-- =====================================================

INSERT INTO `projects` (`quote_id`, `client_id`, `project_number`, `name`, `description`, `status`, `start_date`, `end_date`, `budget`, `actual_cost`, `progress`, `notes`, `assigned_to`) VALUES
-- Proyectos IN_PROGRESS (en progreso)
((SELECT id FROM `quotes` WHERE `quote_number` = 'QT-20250110-0001' LIMIT 1),
 (SELECT client_id FROM `quotes` WHERE `quote_number` = 'QT-20250110-0001' LIMIT 1),
 'PRJ-20250110-0001', 'Sótano - Oficina y Gimnasio - Fortin', 
 'Terminación de sótano para oficina en casa y gimnasio. Área total: 750 sq ft. Incluye iluminación, electricidad y acabados modernos.',
 'in_progress', '2025-02-01', '2025-04-15', 55000.00, 32000.00, 58,
 'Proyecto en progreso. Framing completado. Actualmente trabajando en instalación eléctrica y plomería.',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `quotes` WHERE `quote_number` = 'QT-20250112-0001' LIMIT 1),
 (SELECT client_id FROM `quotes` WHERE `quote_number` = 'QT-20250112-0001' LIMIT 1),
 'PRJ-20250112-0001', 'Sótano - Suite Completa - Beaulieu', 
 'Sótano terminado con dormitorio, baño y área de lavandería. Área total: 850 sq ft.',
 'in_progress', '2025-02-10', '2025-05-01', 48000.00, 28000.00, 58,
 'Proyecto avanzando bien. Drywall instalado. Próximo paso: instalación de pisos y acabados.',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `quotes` WHERE `quote_number` = 'QT-20250114-0001' LIMIT 1),
 (SELECT client_id FROM `quotes` WHERE `quote_number` = 'QT-20250114-0001' LIMIT 1),
 'PRJ-20250114-0001', 'Remodelación Baño - Simard', 
 'Remodelación de baño con ducha tipo lluvia y bañera independiente. Acabados premium.',
 'in_progress', '2025-02-28', '2025-04-10', 42000.00, 25000.00, 60,
 'Demolición y plomería completados. Instalando azulejos y accesorios premium.',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

-- Proyectos PLANNING (en planificación)
((SELECT id FROM `quotes` WHERE `quote_number` = 'QT-20250113-0001' LIMIT 1),
 (SELECT client_id FROM `quotes` WHERE `quote_number` = 'QT-20250113-0001' LIMIT 1),
 'PRJ-20250113-0001', 'Patio con Cocina Exterior - Desjardins', 
 'Patio con cocina exterior completa y área de comedor. Área: 500 sq ft.',
 'planning', '2025-04-20', '2025-06-15', 35000.00, NULL, 0,
 'Proyecto en fase de planificación. Esperando aprobación de permisos municipales.',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

((SELECT id FROM `quotes` WHERE `quote_number` = 'QT-20250114-0002' LIMIT 1),
 (SELECT client_id FROM `quotes` WHERE `quote_number` = 'QT-20250114-0002' LIMIT 1),
 'PRJ-20250114-0002', 'Sótano - Suite de Invitados - Bélanger', 
 'Terminación de sótano para suite de invitados completa. Incluye dormitorio, baño y área de estar. Área: 950 sq ft.',
 'planning', '2025-02-15', '2025-05-30', 62000.00, NULL, 0,
 'Proyecto en planificación. Cliente revisando selecciones finales de materiales.',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

-- Proyectos COMPLETED (completados)
((SELECT id FROM `quotes` WHERE `quote_number` = 'QT-20241202-0001' LIMIT 1),
 (SELECT client_id FROM `quotes` WHERE `quote_number` = 'QT-20241202-0001' LIMIT 1),
 'PRJ-20241202-0001', 'Terminación Sótano - Tremblay', 
 'Terminación completa de sótano con dormitorio, baño y oficina en casa. Área: 800 sq ft.',
 'completed', '2025-01-05', '2025-03-15', 45000.00, 43500.00, 100,
 'Proyecto completado exitosamente. Cliente muy satisfecho con el resultado final.',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

-- Proyectos ON_HOLD (en pausa)
((SELECT id FROM `quotes` WHERE `quote_number` = 'QT-20241204-0001' LIMIT 1),
 (SELECT client_id FROM `quotes` WHERE `quote_number` = 'QT-20241204-0001' LIMIT 1),
 'PRJ-20241204-0001', 'Sótano Entretenimiento - Brown', 
 'Terminación de sótano para área de entretenimiento con home theater. Área: 600 sq ft.',
 'on_hold', '2025-01-15', '2025-04-01', 38000.00, 12000.00, 32,
 'Proyecto en pausa. Cliente solicitó esperar por decisión sobre equipos de audio/video.',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1)),

-- Proyectos CANCELLED (cancelados)
((SELECT id FROM `quotes` WHERE `quote_number` = 'QT-20241205-0002' LIMIT 1),
 (SELECT client_id FROM `quotes` WHERE `quote_number` = 'QT-20241205-0002' LIMIT 1),
 'PRJ-20241205-0001', 'Remodelación Baño - Roy', 
 'Remodelación de baño con mejoras. Cliente decidió posponer indefinidamente.',
 'cancelled', NULL, NULL, 28000.00, 5000.00, 18,
 'Proyecto cancelado por solicitud del cliente. Trabajo inicial completado (demolición).',
 (SELECT id FROM `users` WHERE `username` = 'admin' LIMIT 1))
ON DUPLICATE KEY UPDATE `project_number`=`project_number`;

-- =====================================================
-- FIN DE DATOS DE PRUEBA
-- =====================================================
