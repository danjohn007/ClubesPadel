-- ClubesPadel Sample Data
-- Insert example data for testing

USE `clubespadel`;

-- ============================================
-- SUBSCRIPTION PLANS
-- ============================================

INSERT INTO `subscription_plans` (`name`, `description`, `price_monthly`, `price_yearly`, `max_users`, `max_courts`, `max_tournaments`, `max_storage_mb`, `features`, `is_active`) VALUES
('Básico', 'Plan ideal para clubes pequeños', 299.00, 2990.00, 50, 4, 5, 1024, 'Gestión de canchas, Reservaciones básicas, 50 usuarios, 4 canchas, Soporte por email', 1),
('Profesional', 'Plan para clubes en crecimiento', 599.00, 5990.00, 150, 10, 20, 5120, 'Todo lo del plan Básico, 150 usuarios, 10 canchas, Módulo financiero completo, Torneos ilimitados, Notificaciones WhatsApp, Soporte prioritario', 1),
('Premium', 'Plan completo para grandes clubes', 999.00, 9990.00, NULL, NULL, NULL, 10240, 'Todo lo del plan Profesional, Usuarios ilimitados, Canchas ilimitadas, API personalizada, Soporte 24/7, Capacitación incluida', 1);

-- ============================================
-- CLUBS (TENANTS)
-- ============================================

INSERT INTO `clubs` (`subdomain`, `name`, `email`, `phone`, `address`, `city`, `state`, `country`, `subscription_plan_id`, `subscription_status`, `trial_ends_at`, `is_active`) VALUES
('demo', 'Club Demo Padel', 'demo@clubespadel.com', '555-0100', 'Av. Reforma 123', 'Ciudad de México', 'CDMX', 'México', 2, 'trial', DATE_ADD(NOW(), INTERVAL 30 DAY), 1),
('padelmax', 'Padel Max Club', 'info@padelmax.com', '555-0200', 'Blvd. Miguel de Cervantes 456', 'Guadalajara', 'Jalisco', 'México', 2, 'active', NULL, 1),
('elitpadel', 'Elite Padel Center', 'contacto@elitpadel.com', '555-0300', 'Av. Universidad 789', 'Monterrey', 'Nuevo León', 'México', 3, 'active', NULL, 1);

-- ============================================
-- USERS
-- ============================================

-- SuperAdmin (Password: admin123)
INSERT INTO `users` (`club_id`, `role`, `email`, `password`, `first_name`, `last_name`, `is_active`) VALUES
(NULL, 'superadmin', 'superadmin@clubespadel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super', 'Admin', 1);

-- Club Demo Users (Password: demo123)
INSERT INTO `users` (`club_id`, `role`, `email`, `password`, `first_name`, `last_name`, `phone`, `is_active`) VALUES
(1, 'admin', 'admin@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos', 'Administrador', '555-1001', 1),
(1, 'receptionist', 'recepcion@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María', 'Recepcionista', '555-1002', 1),
(1, 'trainer', 'entrenador@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Luis', 'Entrenador', '555-1003', 1),
(1, 'player', 'jugador1@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana', 'García', '555-1004', 1),
(1, 'player', 'jugador2@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pedro', 'Martínez', '555-1005', 1),
(1, 'player', 'jugador3@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Laura', 'López', '555-1006', 1);

-- Padel Max Users
INSERT INTO `users` (`club_id`, `role`, `email`, `password`, `first_name`, `last_name`, `is_active`) VALUES
(2, 'admin', 'admin@padelmax.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Roberto', 'Sánchez', 1),
(2, 'player', 'jugador@padelmax.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Diana', 'Fernández', 1);

-- Elite Padel Users
INSERT INTO `users` (`club_id`, `role`, `email`, `password`, `first_name`, `last_name`, `is_active`) VALUES
(3, 'admin', 'admin@elitpadel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jorge', 'Ramírez', 1),
(3, 'player', 'jugador@elitpadel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sofia', 'Torres', 1);

-- ============================================
-- COURTS
-- ============================================

INSERT INTO `courts` (`club_id`, `name`, `court_type`, `surface`, `has_lighting`, `hourly_price`, `status`) VALUES
(1, 'Cancha 1', 'outdoor', 'Césped sintético', 1, 150.00, 'available'),
(1, 'Cancha 2', 'outdoor', 'Césped sintético', 1, 150.00, 'available'),
(1, 'Cancha 3', 'indoor', 'Cristal', 0, 200.00, 'available'),
(1, 'Cancha 4', 'indoor', 'Cristal', 0, 200.00, 'available'),
(2, 'Court Central', 'outdoor', 'Césped sintético', 1, 180.00, 'available'),
(2, 'Court 2', 'outdoor', 'Césped sintético', 1, 180.00, 'available'),
(2, 'Court VIP', 'indoor', 'Cristal', 1, 250.00, 'available'),
(3, 'Cancha Elite 1', 'indoor', 'Cristal premium', 1, 300.00, 'available'),
(3, 'Cancha Elite 2', 'indoor', 'Cristal premium', 1, 300.00, 'available');

-- ============================================
-- COURT SCHEDULES
-- ============================================

-- Schedule for all courts: Monday to Friday 6:00-23:00, Saturday-Sunday 7:00-22:00
INSERT INTO `court_schedules` (`court_id`, `day_of_week`, `open_time`, `close_time`)
SELECT id, 1, '06:00:00', '23:00:00' FROM courts
UNION ALL
SELECT id, 2, '06:00:00', '23:00:00' FROM courts
UNION ALL
SELECT id, 3, '06:00:00', '23:00:00' FROM courts
UNION ALL
SELECT id, 4, '06:00:00', '23:00:00' FROM courts
UNION ALL
SELECT id, 5, '06:00:00', '23:00:00' FROM courts
UNION ALL
SELECT id, 6, '07:00:00', '22:00:00' FROM courts
UNION ALL
SELECT id, 0, '07:00:00', '22:00:00' FROM courts;

-- ============================================
-- RESERVATIONS (Sample for this week)
-- ============================================

INSERT INTO `reservations` (`club_id`, `court_id`, `user_id`, `reservation_date`, `start_time`, `end_time`, `duration_hours`, `total_price`, `status`, `payment_status`) VALUES
(1, 1, 5, CURDATE(), '18:00:00', '19:30:00', 1.5, 225.00, 'confirmed', 'paid'),
(1, 2, 6, CURDATE(), '19:00:00', '20:30:00', 1.5, 225.00, 'confirmed', 'paid'),
(1, 3, 7, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '17:00:00', '18:30:00', 1.5, 300.00, 'pending', 'unpaid'),
(1, 1, 5, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '18:00:00', '19:30:00', 1.5, 225.00, 'confirmed', 'paid');

-- ============================================
-- FINANCIAL CATEGORIES
-- ============================================

-- Income Categories
INSERT INTO `income_categories` (`club_id`, `name`, `description`) VALUES
(1, 'Reservaciones', 'Ingresos por reserva de canchas'),
(1, 'Membresías', 'Cuotas de membresías mensuales/anuales'),
(1, 'Torneos', 'Inscripciones a torneos'),
(1, 'Tienda', 'Venta de productos (raquetas, pelotas, etc.)'),
(1, 'Otros', 'Otros ingresos diversos');

-- Expense Categories
INSERT INTO `expense_categories` (`club_id`, `name`, `description`) VALUES
(1, 'Nómina', 'Sueldos del personal'),
(1, 'Servicios', 'Luz, agua, internet, etc.'),
(1, 'Mantenimiento', 'Reparaciones y mantenimiento de canchas'),
(1, 'Insumos', 'Material deportivo y consumibles'),
(1, 'Marketing', 'Publicidad y promoción'),
(1, 'Otros', 'Gastos diversos');

-- ============================================
-- INCOME TRANSACTIONS (Sample)
-- ============================================

INSERT INTO `income_transactions` (`club_id`, `category_id`, `amount`, `description`, `transaction_date`, `payment_method`, `created_by`) VALUES
(1, 1, 225.00, 'Reserva Cancha 1 - Ana García', CURDATE(), 'Tarjeta', 3),
(1, 1, 225.00, 'Reserva Cancha 2 - Pedro Martínez', CURDATE(), 'Efectivo', 3),
(1, 2, 1500.00, 'Membresía mensual - Ana García', CURDATE(), 'Transferencia', 2),
(1, 1, 225.00, 'Reserva Cancha 1 - Ana García', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Tarjeta', 3),
(1, 1, 300.00, 'Reserva Cancha 3 - Laura López', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Efectivo', 3);

-- ============================================
-- EXPENSE TRANSACTIONS (Sample)
-- ============================================

INSERT INTO `expense_transactions` (`club_id`, `category_id`, `amount`, `description`, `transaction_date`, `payment_method`, `created_by`) VALUES
(1, 2, 3500.00, 'Pago de electricidad', CURDATE(), 'Transferencia', 2),
(1, 3, 1200.00, 'Reparación de red Cancha 2', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Efectivo', 2),
(1, 4, 800.00, 'Compra de pelotas de padel', DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Tarjeta', 2);

-- ============================================
-- TOURNAMENTS
-- ============================================

INSERT INTO `tournaments` (`club_id`, `name`, `description`, `tournament_type`, `format`, `registration_fee`, `max_participants`, `start_date`, `end_date`, `registration_deadline`, `status`, `created_by`) VALUES
(1, 'Torneo Primavera 2024', 'Torneo abierto para todas las categorías', 'doubles', 'elimination', 500.00, 32, DATE_ADD(CURDATE(), INTERVAL 15 DAY), DATE_ADD(CURDATE(), INTERVAL 17 DAY), DATE_ADD(CURDATE(), INTERVAL 10 DAY), 'registration_open', 2),
(1, 'Copa Demo Club', 'Torneo interno del club', 'doubles', 'round_robin', 300.00, 16, DATE_ADD(CURDATE(), INTERVAL 30 DAY), DATE_ADD(CURDATE(), INTERVAL 31 DAY), DATE_ADD(CURDATE(), INTERVAL 25 DAY), 'upcoming', 2);

-- ============================================
-- TOURNAMENT PARTICIPANTS
-- ============================================

INSERT INTO `tournament_participants` (`tournament_id`, `user_id`, `partner_id`, `registration_date`, `payment_status`, `status`) VALUES
(1, 5, 6, NOW(), 'paid', 'confirmed'),
(1, 7, 5, NOW(), 'unpaid', 'registered');

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
(1, 'notification_whatsapp', '0');

-- ============================================
-- ACTIVITY LOG (Sample)
-- ============================================

INSERT INTO `activity_log` (`club_id`, `user_id`, `action`, `description`, `ip_address`) VALUES
(1, 2, 'user_login', 'Inicio de sesión de admin@demo.com', '127.0.0.1'),
(1, 3, 'reservation_created', 'Nueva reserva creada para Cancha 1', '127.0.0.1'),
(1, 2, 'court_updated', 'Actualización de Cancha 3', '127.0.0.1'),
(NULL, 1, 'club_created', 'Nuevo club registrado: Club Demo Padel', '127.0.0.1');
