-- ClubesPadel Enhanced Sample Data
-- This file contains extensive sample data for testing and demonstrations
-- Execute this after running schema.sql

USE `clubespadel`;

-- Clean existing data (for fresh start)
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE activity_log;
TRUNCATE TABLE club_settings;
TRUNCATE TABLE tournament_matches;
TRUNCATE TABLE tournament_participants;
TRUNCATE TABLE tournaments;
TRUNCATE TABLE expense_transactions;
TRUNCATE TABLE expense_categories;
TRUNCATE TABLE income_transactions;
TRUNCATE TABLE income_categories;
TRUNCATE TABLE reservations;
TRUNCATE TABLE court_schedules;
TRUNCATE TABLE courts;
TRUNCATE TABLE users;
TRUNCATE TABLE club_payments;
TRUNCATE TABLE clubs;
TRUNCATE TABLE subscription_plans;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- SUBSCRIPTION PLANS
-- ============================================

INSERT INTO `subscription_plans` (`id`, `name`, `description`, `price_monthly`, `price_yearly`, `max_users`, `max_courts`, `max_tournaments`, `max_storage_mb`, `features`, `is_active`) VALUES
(1, 'Básico', 'Plan ideal para clubes pequeños que están empezando', 299.00, 2990.00, 50, 4, 5, 1024, 'Gestión de canchas, Reservaciones básicas, 50 usuarios máximo, 4 canchas, 5 torneos al año, Soporte por email', 1),
(2, 'Profesional', 'Plan para clubes en crecimiento con necesidades avanzadas', 599.00, 5990.00, 150, 10, 20, 5120, 'Todo lo del plan Básico, 150 usuarios, 10 canchas, Módulo financiero completo, 20 torneos al año, Notificaciones WhatsApp, Estadísticas avanzadas, Soporte prioritario', 1),
(3, 'Premium', 'Plan completo para grandes clubes con máximo rendimiento', 999.00, 9990.00, NULL, NULL, NULL, 10240, 'Todo lo del plan Profesional, Usuarios ilimitados, Canchas ilimitadas, Torneos ilimitados, API personalizada, Aplicación móvil, Soporte 24/7, Capacitación incluida, Integración de pagos', 1);

-- ============================================
-- CLUBS (TENANTS) - Multiple clubs for testing
-- ============================================

INSERT INTO `clubs` (`id`, `subdomain`, `name`, `email`, `phone`, `address`, `city`, `state`, `country`, `subscription_plan_id`, `subscription_status`, `trial_ends_at`, `subscription_starts_at`, `subscription_ends_at`, `is_active`, `created_at`) VALUES
(1, 'demo', 'Club Demo Padel', 'demo@clubespadel.com', '555-0100', 'Av. Reforma 123', 'Ciudad de México', 'CDMX', 'México', 2, 'active', DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 14 DAY), DATE_ADD(NOW(), INTERVAL 350 DAY), 1, DATE_SUB(NOW(), INTERVAL 180 DAY)),
(2, 'padelmax', 'Padel Max Club', 'info@padelmax.com', '555-0200', 'Blvd. Miguel de Cervantes 456', 'Guadalajara', 'Jalisco', 'México', 2, 'active', NULL, DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_ADD(NOW(), INTERVAL 305 DAY), 1, DATE_SUB(NOW(), INTERVAL 150 DAY)),
(3, 'elitpadel', 'Elite Padel Center', 'contacto@elitpadel.com', '555-0300', 'Av. Universidad 789', 'Monterrey', 'Nuevo León', 'México', 3, 'active', NULL, DATE_SUB(NOW(), INTERVAL 90 DAY), DATE_ADD(NOW(), INTERVAL 275 DAY), 1, DATE_SUB(NOW(), INTERVAL 120 DAY)),
(4, 'sportclub', 'Sport Club Padel', 'info@sportclub.com', '555-0400', 'Calle Deportiva 321', 'Puebla', 'Puebla', 'México', 1, 'trial', DATE_ADD(NOW(), INTERVAL 20 DAY), NULL, NULL, 1, DATE_SUB(NOW(), INTERVAL 10 DAY)),
(5, 'padelcenter', 'Padel Center 2024', 'contacto@padelcenter.com', '555-0500', 'Av. Principal 555', 'Querétaro', 'Querétaro', 'México', 1, 'trial', DATE_ADD(NOW(), INTERVAL 25 DAY), NULL, NULL, 1, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(6, 'winners', 'Winners Padel Club', 'info@winnerspadel.com', '555-0600', 'Zona Industrial 888', 'León', 'Guanajuato', 'México', 2, 'active', NULL, DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_ADD(NOW(), INTERVAL 320 DAY), 1, DATE_SUB(NOW(), INTERVAL 90 DAY)),
(7, 'champions', 'Champions Padel Academy', 'academy@champions.com', '555-0700', 'Parque Deportivo 111', 'Tijuana', 'Baja California', 'México', 1, 'suspended', NULL, DATE_SUB(NOW(), INTERVAL 200 DAY), DATE_SUB(NOW(), INTERVAL 30 DAY), 1, DATE_SUB(NOW(), INTERVAL 240 DAY)),
(8, 'golden', 'Golden Padel Resort', 'info@golden.com', '555-0800', 'Blvd. Turístico 222', 'Cancún', 'Quintana Roo', 'México', 3, 'active', NULL, DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_ADD(NOW(), INTERVAL 335 DAY), 1, DATE_SUB(NOW(), INTERVAL 60 DAY));

-- ============================================
-- USERS - Multiple users per club
-- ============================================

-- SuperAdmin (Password: admin123)
INSERT INTO `users` (`id`, `club_id`, `role`, `email`, `password`, `first_name`, `last_name`, `phone`, `skill_level`, `is_active`, `created_at`) VALUES
(1, NULL, 'superadmin', 'superadmin@clubespadel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super', 'Admin', '555-9999', NULL, 1, DATE_SUB(NOW(), INTERVAL 365 DAY));

-- Club Demo Users (Password: demo123)
INSERT INTO `users` (`club_id`, `role`, `email`, `password`, `first_name`, `last_name`, `phone`, `skill_level`, `is_active`, `created_at`) VALUES
(1, 'admin', 'admin@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos', 'Administrador', '555-1001', 'advanced', 1, DATE_SUB(NOW(), INTERVAL 180 DAY)),
(1, 'receptionist', 'recepcion@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María', 'Recepcionista', '555-1002', 'intermediate', 1, DATE_SUB(NOW(), INTERVAL 180 DAY)),
(1, 'trainer', 'entrenador@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Luis', 'Entrenador', '555-1003', 'professional', 1, DATE_SUB(NOW(), INTERVAL 180 DAY)),
(1, 'player', 'jugador1@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana', 'García', '555-1004', 'intermediate', 1, DATE_SUB(NOW(), INTERVAL 150 DAY)),
(1, 'player', 'jugador2@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pedro', 'Martínez', '555-1005', 'advanced', 1, DATE_SUB(NOW(), INTERVAL 140 DAY)),
(1, 'player', 'jugador3@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Laura', 'López', '555-1006', 'beginner', 1, DATE_SUB(NOW(), INTERVAL 130 DAY)),
(1, 'player', 'sofia.rodriguez@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sofía', 'Rodríguez', '555-1007', 'intermediate', 1, DATE_SUB(NOW(), INTERVAL 120 DAY)),
(1, 'player', 'miguel.torres@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Miguel', 'Torres', '555-1008', 'advanced', 1, DATE_SUB(NOW(), INTERVAL 110 DAY)),
(1, 'player', 'carolina.diaz@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carolina', 'Díaz', '555-1009', 'beginner', 1, DATE_SUB(NOW(), INTERVAL 100 DAY)),
(1, 'player', 'roberto.sanchez@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Roberto', 'Sánchez', '555-1010', 'intermediate', 1, DATE_SUB(NOW(), INTERVAL 90 DAY)),
(1, 'player', 'isabel.ramirez@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Isabel', 'Ramírez', '555-1011', 'advanced', 1, DATE_SUB(NOW(), INTERVAL 80 DAY)),
(1, 'player', 'fernando.castro@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Fernando', 'Castro', '555-1012', 'professional', 1, DATE_SUB(NOW(), INTERVAL 70 DAY)),
(1, 'player', 'patricia.gomez@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patricia', 'Gómez', '555-1013', 'intermediate', 1, DATE_SUB(NOW(), INTERVAL 60 DAY)),
(1, 'player', 'jose.hernandez@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'José', 'Hernández', '555-1014', 'beginner', 1, DATE_SUB(NOW(), INTERVAL 50 DAY)),
(1, 'player', 'elena.vargas@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Elena', 'Vargas', '555-1015', 'advanced', 1, DATE_SUB(NOW(), INTERVAL 40 DAY));

-- Padel Max Users
INSERT INTO `users` (`club_id`, `role`, `email`, `password`, `first_name`, `last_name`, `phone`, `skill_level`, `is_active`) VALUES
(2, 'admin', 'admin@padelmax.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Roberto', 'Sánchez', '555-2001', 'advanced', 1),
(2, 'player', 'diana.fernandez@padelmax.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Diana', 'Fernández', '555-2002', 'intermediate', 1),
(2, 'player', 'mario.lopez@padelmax.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mario', 'López', '555-2003', 'advanced', 1),
(2, 'player', 'andrea.morales@padelmax.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Andrea', 'Morales', '555-2004', 'beginner', 1),
(2, 'player', 'carlos.ruiz@padelmax.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos', 'Ruiz', '555-2005', 'intermediate', 1);

-- Elite Padel Users
INSERT INTO `users` (`club_id`, `role`, `email`, `password`, `first_name`, `last_name`, `phone`, `skill_level`, `is_active`) VALUES
(3, 'admin', 'admin@elitpadel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jorge', 'Ramírez', '555-3001', 'professional', 1),
(3, 'player', 'sofia.torres@elitpadel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sofia', 'Torres', '555-3002', 'advanced', 1),
(3, 'player', 'diego.mendoza@elitpadel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Diego', 'Mendoza', '555-3003', 'professional', 1);

-- Other clubs users
INSERT INTO `users` (`club_id`, `role`, `email`, `password`, `first_name`, `last_name`, `is_active`) VALUES
(4, 'admin', 'admin@sportclub.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan', 'Pérez', 1),
(5, 'admin', 'admin@padelcenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María', 'González', 1),
(6, 'admin', 'admin@winnerspadel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Luis', 'Moreno', 1),
(7, 'admin', 'admin@champions.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carmen', 'Silva', 1),
(8, 'admin', 'admin@golden.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ricardo', 'Navarro', 1);

-- ============================================
-- COURTS - Multiple courts per club
-- ============================================

INSERT INTO `courts` (`club_id`, `name`, `court_type`, `surface`, `has_lighting`, `hourly_price`, `status`, `description`) VALUES
-- Club Demo (8 courts)
(1, 'Cancha Central', 'outdoor', 'Césped sintético premium', 1, 200.00, 'available', 'Cancha principal con iluminación LED y gradas'),
(1, 'Cancha 1', 'outdoor', 'Césped sintético', 1, 150.00, 'available', 'Cancha techada con césped de alta calidad'),
(1, 'Cancha 2', 'outdoor', 'Césped sintético', 1, 150.00, 'available', 'Cancha al aire libre con iluminación'),
(1, 'Cancha 3', 'indoor', 'Cristal', 0, 200.00, 'available', 'Cancha cubierta con cristal panorámico'),
(1, 'Cancha 4', 'indoor', 'Cristal', 0, 200.00, 'available', 'Cancha cubierta climatizada'),
(1, 'Cancha VIP 1', 'indoor', 'Cristal premium', 1, 300.00, 'available', 'Cancha premium con servicio VIP'),
(1, 'Cancha VIP 2', 'indoor', 'Cristal premium', 1, 300.00, 'available', 'Cancha premium climatizada'),
(1, 'Cancha de Entrenamiento', 'outdoor', 'Césped sintético', 1, 120.00, 'available', 'Cancha para prácticas y entrenamiento'),

-- Padel Max (5 courts)
(2, 'Court Central', 'outdoor', 'Césped sintético', 1, 180.00, 'available', 'Cancha principal iluminada'),
(2, 'Court 1', 'outdoor', 'Césped sintético', 1, 160.00, 'available', 'Cancha techada'),
(2, 'Court 2', 'outdoor', 'Césped sintético', 1, 160.00, 'available', 'Cancha al aire libre'),
(2, 'Court VIP', 'indoor', 'Cristal', 1, 250.00, 'available', 'Cancha VIP climatizada'),
(2, 'Court Junior', 'outdoor', 'Césped sintético', 0, 100.00, 'available', 'Cancha para niños y principiantes'),

-- Elite Padel (6 courts)
(3, 'Cancha Elite 1', 'indoor', 'Cristal premium', 1, 300.00, 'available', 'Cancha profesional con sistema de video'),
(3, 'Cancha Elite 2', 'indoor', 'Cristal premium', 1, 300.00, 'available', 'Cancha profesional climatizada'),
(3, 'Cancha Elite 3', 'indoor', 'Cristal premium', 1, 300.00, 'available', 'Cancha profesional con gradas'),
(3, 'Cancha Tournament', 'indoor', 'Cristal championship', 1, 400.00, 'available', 'Cancha para torneos oficiales'),
(3, 'Cancha Practice 1', 'outdoor', 'Césped sintético', 1, 200.00, 'available', 'Cancha de práctica'),
(3, 'Cancha Practice 2', 'outdoor', 'Césped sintético', 1, 200.00, 'available', 'Cancha de práctica');

-- ============================================
-- COURT SCHEDULES - All courts Monday-Sunday
-- ============================================

INSERT INTO `court_schedules` (`court_id`, `day_of_week`, `open_time`, `close_time`)
SELECT id, 1, '06:00:00', '23:00:00' FROM courts WHERE club_id <= 3
UNION ALL
SELECT id, 2, '06:00:00', '23:00:00' FROM courts WHERE club_id <= 3
UNION ALL
SELECT id, 3, '06:00:00', '23:00:00' FROM courts WHERE club_id <= 3
UNION ALL
SELECT id, 4, '06:00:00', '23:00:00' FROM courts WHERE club_id <= 3
UNION ALL
SELECT id, 5, '06:00:00', '23:00:00' FROM courts WHERE club_id <= 3
UNION ALL
SELECT id, 6, '07:00:00', '22:00:00' FROM courts WHERE club_id <= 3
UNION ALL
SELECT id, 0, '07:00:00', '22:00:00' FROM courts WHERE club_id <= 3;

-- ============================================
-- RESERVATIONS - Many reservations for charts
-- ============================================

-- Last 6 months of reservations for Club Demo
INSERT INTO `reservations` (`club_id`, `court_id`, `user_id`, `reservation_date`, `start_time`, `end_time`, `duration_hours`, `total_price`, `status`, `payment_status`, `created_at`) VALUES
-- Current week
(1, 1, 5, CURDATE(), '08:00:00', '09:30:00', 1.5, 300.00, 'confirmed', 'paid', NOW()),
(1, 2, 6, CURDATE(), '09:00:00', '10:30:00', 1.5, 225.00, 'confirmed', 'paid', NOW()),
(1, 3, 7, CURDATE(), '10:00:00', '11:30:00', 1.5, 300.00, 'confirmed', 'paid', NOW()),
(1, 4, 8, CURDATE(), '11:00:00', '12:30:00', 1.5, 300.00, 'confirmed', 'paid', NOW()),
(1, 5, 9, CURDATE(), '16:00:00', '17:30:00', 1.5, 300.00, 'confirmed', 'paid', NOW()),
(1, 6, 10, CURDATE(), '17:00:00', '18:30:00', 1.5, 450.00, 'confirmed', 'paid', NOW()),
(1, 1, 11, CURDATE(), '18:00:00', '19:30:00', 1.5, 300.00, 'confirmed', 'paid', NOW()),
(1, 2, 12, CURDATE(), '19:00:00', '20:30:00', 1.5, 225.00, 'confirmed', 'paid', NOW()),
(1, 3, 13, CURDATE(), '20:00:00', '21:30:00', 1.5, 300.00, 'pending', 'unpaid', NOW()),
(1, 1, 5, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '17:00:00', '18:30:00', 1.5, 300.00, 'confirmed', 'paid', NOW()),
(1, 2, 6, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '18:00:00', '19:30:00', 1.5, 225.00, 'confirmed', 'paid', NOW()),
(1, 3, 7, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '17:00:00', '18:30:00', 1.5, 300.00, 'pending', 'unpaid', NOW()),
(1, 1, 8, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '18:00:00', '19:30:00', 1.5, 300.00, 'confirmed', 'paid', NOW()),
(1, 4, 9, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '19:00:00', '20:30:00', 1.5, 300.00, 'confirmed', 'paid', NOW());

-- Previous months data (for charts)
INSERT INTO `reservations` (`club_id`, `court_id`, `user_id`, `reservation_date`, `start_time`, `end_time`, `duration_hours`, `total_price`, `status`, `payment_status`, `created_at`)
SELECT 
    1,
    (5 + (FLOOR(RAND() * 13))) as court_id,
    (5 + (FLOOR(RAND() * 11))) as user_id,
    DATE_SUB(CURDATE(), INTERVAL day_offset DAY) as reservation_date,
    CONCAT(LPAD(FLOOR(8 + RAND() * 14), 2, '0'), ':00:00') as start_time,
    CONCAT(LPAD(FLOOR(9 + RAND() * 14) + 1, 2, '0'), ':30:00') as end_time,
    1.5 as duration_hours,
    (150.00 + (FLOOR(RAND() * 6) * 50)) as total_price,
    'completed' as status,
    'paid' as payment_status,
    DATE_SUB(NOW(), INTERVAL day_offset DAY) as created_at
FROM (
    SELECT 1 as day_offset UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION 
    SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION
    SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION
    SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 UNION SELECT 20 UNION
    SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24 UNION SELECT 25 UNION
    SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29 UNION SELECT 30 UNION
    SELECT 35 UNION SELECT 40 UNION SELECT 45 UNION SELECT 50 UNION SELECT 55 UNION
    SELECT 60 UNION SELECT 65 UNION SELECT 70 UNION SELECT 75 UNION SELECT 80 UNION
    SELECT 85 UNION SELECT 90 UNION SELECT 95 UNION SELECT 100 UNION SELECT 105 UNION
    SELECT 110 UNION SELECT 115 UNION SELECT 120 UNION SELECT 125 UNION SELECT 130 UNION
    SELECT 135 UNION SELECT 140 UNION SELECT 145 UNION SELECT 150 UNION SELECT 155 UNION
    SELECT 160 UNION SELECT 165 UNION SELECT 170 UNION SELECT 175 UNION SELECT 180
) days;

-- ============================================
-- FINANCIAL CATEGORIES
-- ============================================

-- Income Categories
INSERT INTO `income_categories` (`club_id`, `name`, `description`) VALUES
(1, 'Reservaciones', 'Ingresos por reserva de canchas'),
(1, 'Membresías', 'Cuotas de membresías mensuales/anuales'),
(1, 'Torneos', 'Inscripciones a torneos y eventos'),
(1, 'Tienda', 'Venta de productos (raquetas, pelotas, accesorios)'),
(1, 'Clases', 'Clases particulares y grupales'),
(1, 'Bar/Cafetería', 'Ventas de alimentos y bebidas'),
(1, 'Publicidad', 'Ingresos por publicidad y patrocinios'),
(1, 'Otros', 'Otros ingresos diversos');

-- Expense Categories
INSERT INTO `expense_categories` (`club_id`, `name`, `description`) VALUES
(1, 'Nómina', 'Sueldos y salarios del personal'),
(1, 'Servicios', 'Luz, agua, internet, teléfono'),
(1, 'Mantenimiento', 'Reparaciones y mantenimiento de canchas'),
(1, 'Insumos', 'Material deportivo y consumibles'),
(1, 'Marketing', 'Publicidad, promoción y redes sociales'),
(1, 'Seguros', 'Seguros de instalaciones y responsabilidad civil'),
(1, 'Impuestos', 'Impuestos y contribuciones'),
(1, 'Otros', 'Gastos diversos');

-- ============================================
-- INCOME TRANSACTIONS - Last 6 months
-- ============================================

-- Current month
INSERT INTO `income_transactions` (`club_id`, `category_id`, `amount`, `description`, `transaction_date`, `payment_method`, `created_by`, `created_at`) VALUES
(1, 1, 300.00, 'Reserva Cancha Central - Ana García', CURDATE(), 'Tarjeta', 3, NOW()),
(1, 1, 225.00, 'Reserva Cancha 2 - Pedro Martínez', CURDATE(), 'Efectivo', 3, NOW()),
(1, 1, 300.00, 'Reserva Cancha 3 - Laura López', CURDATE(), 'Transferencia', 3, NOW()),
(1, 1, 300.00, 'Reserva Cancha 4 - Sofía Rodríguez', CURDATE(), 'Tarjeta', 3, NOW()),
(1, 1, 300.00, 'Reserva Cancha 5 - Miguel Torres', CURDATE(), 'Efectivo', 3, NOW()),
(1, 2, 1500.00, 'Membresía mensual - Ana García', CURDATE(), 'Transferencia', 2, NOW()),
(1, 2, 1500.00, 'Membresía mensual - Pedro Martínez', CURDATE(), 'Tarjeta', 2, NOW()),
(1, 4, 2500.00, 'Venta raquetas Wilson', CURDATE(), 'Tarjeta', 2, NOW()),
(1, 5, 800.00, 'Clase grupal - 5 personas', CURDATE(), 'Efectivo', 4, NOW()),
(1, 6, 450.00, 'Ventas cafetería', CURDATE(), 'Mixto', 3, NOW());

-- Previous months - generated data
INSERT INTO `income_transactions` (`club_id`, `category_id`, `amount`, `description`, `transaction_date`, `payment_method`, `created_by`, `created_at`)
SELECT 
    1,
    (1 + (FLOOR(RAND() * 8))) as category_id,
    (FLOOR(150 + RAND() * 2850)) as amount,
    CONCAT('Transacción - ', DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL day_offset DAY), '%d/%m/%Y')) as description,
    DATE_SUB(CURDATE(), INTERVAL day_offset DAY) as transaction_date,
    CASE FLOOR(RAND() * 3)
        WHEN 0 THEN 'Efectivo'
        WHEN 1 THEN 'Tarjeta'
        ELSE 'Transferencia'
    END as payment_method,
    2 as created_by,
    DATE_SUB(NOW(), INTERVAL day_offset DAY) as created_at
FROM (
    SELECT 1 as day_offset UNION SELECT 3 UNION SELECT 5 UNION SELECT 7 UNION SELECT 9 UNION
    SELECT 12 UNION SELECT 15 UNION SELECT 18 UNION SELECT 21 UNION SELECT 24 UNION
    SELECT 27 UNION SELECT 30 UNION SELECT 35 UNION SELECT 40 UNION SELECT 45 UNION
    SELECT 50 UNION SELECT 55 UNION SELECT 60 UNION SELECT 65 UNION SELECT 70 UNION
    SELECT 75 UNION SELECT 80 UNION SELECT 85 UNION SELECT 90 UNION SELECT 95 UNION
    SELECT 100 UNION SELECT 105 UNION SELECT 110 UNION SELECT 115 UNION SELECT 120 UNION
    SELECT 125 UNION SELECT 130 UNION SELECT 135 UNION SELECT 140 UNION SELECT 145 UNION
    SELECT 150 UNION SELECT 155 UNION SELECT 160 UNION SELECT 165 UNION SELECT 170 UNION
    SELECT 175 UNION SELECT 180
) days;

-- ============================================
-- EXPENSE TRANSACTIONS - Last 6 months
-- ============================================

INSERT INTO `expense_transactions` (`club_id`, `category_id`, `amount`, `description`, `transaction_date`, `payment_method`, `created_by`, `created_at`) VALUES
(1, 2, 3500.00, 'Pago de electricidad del mes', CURDATE(), 'Transferencia', 2, NOW()),
(1, 2, 1200.00, 'Servicio de agua', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Transferencia', 2, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(1, 3, 2500.00, 'Reparación de red Cancha 2', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Efectivo', 2, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(1, 4, 800.00, 'Compra de pelotas de padel', DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Tarjeta', 2, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(1, 1, 45000.00, 'Nómina del mes', DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Transferencia', 2, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 5, 3000.00, 'Campaña de Facebook Ads', DATE_SUB(CURDATE(), INTERVAL 7 DAY), 'Tarjeta', 2, DATE_SUB(NOW(), INTERVAL 7 DAY));

-- Previous months expenses
INSERT INTO `expense_transactions` (`club_id`, `category_id`, `amount`, `description`, `transaction_date`, `payment_method`, `created_by`, `created_at`)
SELECT 
    1,
    (1 + (FLOOR(RAND() * 8))) as category_id,
    (FLOOR(500 + RAND() * 4500)) as amount,
    CONCAT('Gasto - ', DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL day_offset DAY), '%d/%m/%Y')) as description,
    DATE_SUB(CURDATE(), INTERVAL day_offset DAY) as transaction_date,
    CASE FLOOR(RAND() * 3)
        WHEN 0 THEN 'Efectivo'
        WHEN 1 THEN 'Tarjeta'
        ELSE 'Transferencia'
    END as payment_method,
    2 as created_by,
    DATE_SUB(NOW(), INTERVAL day_offset DAY) as created_at
FROM (
    SELECT 15 as day_offset UNION SELECT 30 UNION SELECT 45 UNION SELECT 60 UNION 
    SELECT 75 UNION SELECT 90 UNION SELECT 105 UNION SELECT 120 UNION 
    SELECT 135 UNION SELECT 150 UNION SELECT 165 UNION SELECT 180
) days;

-- ============================================
-- TOURNAMENTS
-- ============================================

INSERT INTO `tournaments` (`club_id`, `name`, `description`, `tournament_type`, `format`, `registration_fee`, `max_participants`, `start_date`, `end_date`, `registration_deadline`, `status`, `created_by`, `created_at`) VALUES
(1, 'Torneo Primavera 2024', 'Gran torneo de primavera para todas las categorías', 'doubles', 'elimination', 500.00, 32, DATE_ADD(CURDATE(), INTERVAL 15 DAY), DATE_ADD(CURDATE(), INTERVAL 17 DAY), DATE_ADD(CURDATE(), INTERVAL 10 DAY), 'registration_open', 2, DATE_SUB(NOW(), INTERVAL 30 DAY)),
(1, 'Copa Demo Club', 'Torneo interno exclusivo para socios del club', 'doubles', 'round_robin', 300.00, 16, DATE_ADD(CURDATE(), INTERVAL 30 DAY), DATE_ADD(CURDATE(), INTERVAL 31 DAY), DATE_ADD(CURDATE(), INTERVAL 25 DAY), 'upcoming', 2, DATE_SUB(NOW(), INTERVAL 20 DAY)),
(1, 'Torneo Navideño 2023', 'Torneo especial de fin de año', 'doubles', 'elimination', 400.00, 24, DATE_SUB(CURDATE(), INTERVAL 45 DAY), DATE_SUB(CURDATE(), INTERVAL 43 DAY), DATE_SUB(CURDATE(), INTERVAL 50 DAY), 'completed', 2, DATE_SUB(NOW(), INTERVAL 60 DAY)),
(1, 'Championship Amateur', 'Campeonato para jugadores amateur', 'doubles', 'group_stage', 600.00, 40, DATE_ADD(CURDATE(), INTERVAL 60 DAY), DATE_ADD(CURDATE(), INTERVAL 65 DAY), DATE_ADD(CURDATE(), INTERVAL 50 DAY), 'upcoming', 2, DATE_SUB(NOW(), INTERVAL 10 DAY)),
(1, 'Torneo Juvenil', 'Torneo especial para menores de 18 años', 'doubles', 'elimination', 200.00, 16, DATE_ADD(CURDATE(), INTERVAL 45 DAY), DATE_ADD(CURDATE(), INTERVAL 46 DAY), DATE_ADD(CURDATE(), INTERVAL 40 DAY), 'registration_open', 2, DATE_SUB(NOW(), INTERVAL 5 DAY));

-- ============================================
-- TOURNAMENT PARTICIPANTS
-- ============================================

INSERT INTO `tournament_participants` (`tournament_id`, `user_id`, `partner_id`, `registration_date`, `payment_status`, `status`) VALUES
(1, 5, 6, DATE_SUB(NOW(), INTERVAL 25 DAY), 'paid', 'confirmed'),
(1, 7, 8, DATE_SUB(NOW(), INTERVAL 24 DAY), 'paid', 'confirmed'),
(1, 9, 10, DATE_SUB(NOW(), INTERVAL 23 DAY), 'paid', 'confirmed'),
(1, 11, 12, DATE_SUB(NOW(), INTERVAL 22 DAY), 'unpaid', 'registered'),
(1, 13, 14, DATE_SUB(NOW(), INTERVAL 21 DAY), 'paid', 'confirmed'),
(5, 5, 7, DATE_SUB(NOW(), INTERVAL 3 DAY), 'paid', 'confirmed'),
(5, 9, 11, DATE_SUB(NOW(), INTERVAL 2 DAY), 'paid', 'confirmed');

-- ============================================
-- CLUB PAYMENTS - Subscription payments
-- ============================================

INSERT INTO `club_payments` (`club_id`, `amount`, `currency`, `payment_method`, `transaction_id`, `status`, `payment_date`, `period_start`, `period_end`, `created_at`) VALUES
-- Club Demo payments
(1, 599.00, 'MXN', 'Tarjeta', 'TXN-2024-001', 'completed', CURDATE(), DATE_FORMAT(CURDATE(), '%Y-%m-01'), LAST_DAY(CURDATE()), NOW()),
(1, 599.00, 'MXN', 'Tarjeta', 'TXN-2024-002', 'completed', DATE_SUB(CURDATE(), INTERVAL 1 MONTH), DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m-01'), LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), DATE_SUB(NOW(), INTERVAL 1 MONTH)),
(1, 599.00, 'MXN', 'Tarjeta', 'TXN-2024-003', 'completed', DATE_SUB(CURDATE(), INTERVAL 2 MONTH), DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH), '%Y-%m-01'), LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 2 MONTH)), DATE_SUB(NOW(), INTERVAL 2 MONTH)),
(1, 599.00, 'MXN', 'Tarjeta', 'TXN-2024-004', 'completed', DATE_SUB(CURDATE(), INTERVAL 3 MONTH), DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH), '%Y-%m-01'), LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 3 MONTH)), DATE_SUB(NOW(), INTERVAL 3 MONTH)),
(1, 599.00, 'MXN', 'Tarjeta', 'TXN-2024-005', 'completed', DATE_SUB(CURDATE(), INTERVAL 4 MONTH), DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 4 MONTH), '%Y-%m-01'), LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 4 MONTH)), DATE_SUB(NOW(), INTERVAL 4 MONTH)),
(1, 599.00, 'MXN', 'Tarjeta', 'TXN-2024-006', 'completed', DATE_SUB(CURDATE(), INTERVAL 5 MONTH), DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), '%Y-%m-01'), LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 5 MONTH)), DATE_SUB(NOW(), INTERVAL 5 MONTH)),

-- Padel Max payments
(2, 599.00, 'MXN', 'Transferencia', 'TXN-2024-101', 'completed', CURDATE(), DATE_FORMAT(CURDATE(), '%Y-%m-01'), LAST_DAY(CURDATE()), NOW()),
(2, 599.00, 'MXN', 'Transferencia', 'TXN-2024-102', 'completed', DATE_SUB(CURDATE(), INTERVAL 1 MONTH), DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m-01'), LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), DATE_SUB(NOW(), INTERVAL 1 MONTH)),

-- Elite Padel payments
(3, 999.00, 'MXN', 'Tarjeta', 'TXN-2024-201', 'completed', CURDATE(), DATE_FORMAT(CURDATE(), '%Y-%m-01'), LAST_DAY(CURDATE()), NOW()),
(3, 999.00, 'MXN', 'Tarjeta', 'TXN-2024-202', 'completed', DATE_SUB(CURDATE(), INTERVAL 1 MONTH), DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m-01'), LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), DATE_SUB(NOW(), INTERVAL 1 MONTH)),
(3, 999.00, 'MXN', 'Tarjeta', 'TXN-2024-203', 'completed', DATE_SUB(CURDATE(), INTERVAL 2 MONTH), DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH), '%Y-%m-01'), LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 2 MONTH)), DATE_SUB(NOW(), INTERVAL 2 MONTH)),

-- Winners Padel payments
(6, 599.00, 'MXN', 'Tarjeta', 'TXN-2024-301', 'completed', CURDATE(), DATE_FORMAT(CURDATE(), '%Y-%m-01'), LAST_DAY(CURDATE()), NOW()),

-- Golden Padel payments
(8, 999.00, 'MXN', 'Tarjeta', 'TXN-2024-401', 'completed', CURDATE(), DATE_FORMAT(CURDATE(), '%Y-%m-01'), LAST_DAY(CURDATE()), NOW());

-- ============================================
-- CLUB SETTINGS
-- ============================================

INSERT INTO `club_settings` (`club_id`, `setting_key`, `setting_value`) VALUES
(1, 'business_hours', '{"monday":"06:00-23:00","tuesday":"06:00-23:00","wednesday":"06:00-23:00","thursday":"06:00-23:00","friday":"06:00-23:00","saturday":"07:00-22:00","sunday":"07:00-22:00"}'),
(1, 'currency', 'MXN'),
(1, 'timezone', 'America/Mexico_City'),
(1, 'booking_advance_days', '30'),
(1, 'cancellation_hours', '24'),
(1, 'notification_email', '1'),
(1, 'notification_whatsapp', '1'),
(1, 'allow_online_payments', '1'),
(1, 'require_deposit', '0');

-- ============================================
-- ACTIVITY LOG - Sample activity
-- ============================================

INSERT INTO `activity_log` (`club_id`, `user_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(1, 2, 'user_login', 'Inicio de sesión de admin@demo.com', '127.0.0.1', NOW()),
(1, 3, 'reservation_created', 'Nueva reserva creada para Cancha Central', '127.0.0.1', NOW()),
(1, 2, 'court_updated', 'Actualización de Cancha 3', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(NULL, 1, 'club_created', 'Nuevo club registrado: Sport Club Padel', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 10 DAY)),
(NULL, 1, 'club_created', 'Nuevo club registrado: Padel Center 2024', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 5, 'user_login', 'Inicio de sesión de jugador1@demo.com', '192.168.1.100', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(1, 6, 'user_login', 'Inicio de sesión de jugador2@demo.com', '192.168.1.101', DATE_SUB(NOW(), INTERVAL 3 HOUR)),
(1, 2, 'tournament_created', 'Nuevo torneo: Torneo Juvenil', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 3, 'income_registered', 'Ingreso registrado: $300.00', '127.0.0.1', NOW()),
(2, 16, 'user_login', 'Inicio de sesión de admin@padelmax.com', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 1 HOUR));

-- ============================================
-- NOTIFICATIONS
-- ============================================

INSERT INTO `notifications` (`club_id`, `title`, `message`, `type`, `target_role`, `status`, `sent_at`, `created_by`, `created_at`) VALUES
(1, 'Nuevo Torneo: Torneo Primavera 2024', 'Se abrieron las inscripciones para el Torneo Primavera 2024. ¡No te lo pierdas!', 'all', 'player', 'sent', DATE_SUB(NOW(), INTERVAL 30 DAY), 2, DATE_SUB(NOW(), INTERVAL 30 DAY)),
(1, 'Recordatorio de Pago', 'Tu membresía vence en 5 días. Por favor realiza tu pago para continuar disfrutando.', 'email', 'player', 'sent', DATE_SUB(NOW(), INTERVAL 5 DAY), 2, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 'Mantenimiento Programado', 'La Cancha 2 estará en mantenimiento del 20 al 22 de este mes.', 'email', NULL, 'sent', DATE_SUB(NOW(), INTERVAL 10 DAY), 2, DATE_SUB(NOW(), INTERVAL 10 DAY));

-- ============================================
-- Summary Statistics
-- ============================================

SELECT 'Data Import Complete!' as Status;
SELECT COUNT(*) as 'Total Clubs' FROM clubs;
SELECT COUNT(*) as 'Total Users' FROM users;
SELECT COUNT(*) as 'Total Courts' FROM courts;
SELECT COUNT(*) as 'Total Reservations' FROM reservations;
SELECT COUNT(*) as 'Total Tournaments' FROM tournaments;
SELECT CONCAT('$', FORMAT(SUM(amount), 2)) as 'Total Income' FROM income_transactions WHERE club_id = 1;
SELECT CONCAT('$', FORMAT(SUM(amount), 2)) as 'Total Expenses' FROM expense_transactions WHERE club_id = 1;
SELECT CONCAT('$', FORMAT(SUM(amount), 2)) as 'Total Revenue (Subscriptions)' FROM club_payments WHERE status = 'completed';

