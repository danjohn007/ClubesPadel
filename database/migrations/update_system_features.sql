-- ClubesPadel System Updates
-- Migration to support new features
-- Date: 2025-10-15

USE clubespadel;

-- ============================================
-- 1. ADD TERMS AND CONDITIONS ACCEPTANCE
-- ============================================
-- Si alguna columna ya existe, ignora el error y sigue.
ALTER TABLE users ADD COLUMN terms_accepted TINYINT(1) DEFAULT 1 AFTER is_active;
ALTER TABLE users ADD COLUMN terms_accepted_at TIMESTAMP NULL AFTER terms_accepted;

-- ============================================
-- 2. SYSTEM CONFIGURATIONS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS system_configurations (
  id INT(11) NOT NULL AUTO_INCREMENT,
  config_key VARCHAR(100) NOT NULL,
  config_value TEXT,
  config_type ENUM('text', 'number', 'boolean', 'json') DEFAULT 'text',
  description TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY config_key (config_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO system_configurations (config_key, config_value, config_type, description) VALUES
('paypal_email', '', 'text', 'Cuenta principal de PayPal del sistema'),
('system_email', 'noreply@clubespadel.com', 'text', 'Correo electrónico que envía los mensajes del sistema'),
('currency_symbol', '$', 'text', 'Símbolo de la moneda'),
('tax_rate', '16', 'number', 'Porcentaje de tasa de impuesto'),
('site_name', 'ClubesPadel', 'text', 'Nombre del sitio público'),
('site_logo', '', 'text', 'Logo del sitio'),
('site_description', 'Sistema de Administración de Clubes de Padel', 'text', 'Descripción del sitio'),
('terms_and_conditions', 'Al registrarte en ClubesPadel, aceptas nuestros términos y condiciones de uso.', 'text', 'Mensaje de términos y condiciones'),
('whatsapp_chatbot', '', 'text', 'Número de WhatsApp del Chatbot del sistema'),
('bank_account_info', '', 'json', 'Datos de cuentas bancarias para depósitos'),
('contact_phones', '', 'json', 'Teléfonos de contacto'),
('business_hours', '', 'json', 'Horarios de atención');

-- ============================================
-- 3. INCOME AND EXPENSE CATEGORIES
-- ============================================
CREATE TABLE IF NOT EXISTS income_categories (
  id INT(11) NOT NULL AUTO_INCREMENT,
  club_id INT(11) NULL,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  is_system TINYINT(1) DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY club_id (club_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS expense_categories (
  id INT(11) NOT NULL AUTO_INCREMENT,
  club_id INT(11) NULL,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  is_system TINYINT(1) DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY club_id (club_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO income_categories (club_id, name, description, is_system, is_active) VALUES
(1, 'Reservaciones', 'Ingresos por reservaciones de canchas', 1, 1),
(1, 'Membresías', 'Ingresos por membresías de jugadores', 1, 1),
(1, 'Torneos', 'Ingresos por inscripciones a torneos', 1, 1),
(1, 'Tienda', 'Ingresos por venta de productos', 1, 1),
(1, 'Clases', 'Ingresos por clases y entrenamientos', 1, 1),
(1, 'Otros', 'Otros ingresos', 1, 1);

INSERT IGNORE INTO expense_categories (club_id, name, description, is_system, is_active) VALUES
(1, 'Mantenimiento', 'Gastos de mantenimiento de instalaciones', 1, 1),
(1, 'Servicios', 'Gastos de servicios (luz, agua, etc)', 1, 1),
(1, 'Nómina', 'Gastos de nómina de empleados', 1, 1),
(1, 'Materiales', 'Compra de materiales y equipamiento', 1, 1),
(1, 'Publicidad', 'Gastos de marketing y publicidad', 1, 1),
(1, 'Otros', 'Otros gastos', 1, 1);

-- ============================================
-- 4. BUDGET MODULE
-- ============================================
CREATE TABLE IF NOT EXISTS budgets (
  id INT(11) NOT NULL AUTO_INCREMENT,
  club_id INT(11) NOT NULL,
  year INT(4) NOT NULL,
  month INT(2) NOT NULL,
  estimated_income DECIMAL(10,2) DEFAULT 0,
  estimated_expenses DECIMAL(10,2) DEFAULT 0,
  actual_income DECIMAL(10,2) DEFAULT 0,
  actual_expenses DECIMAL(10,2) DEFAULT 0,
  notes TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY club_year_month (club_id, year, month)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. USER IMPORT HISTORY
-- ============================================
CREATE TABLE IF NOT EXISTS import_history (
  id INT(11) NOT NULL AUTO_INCREMENT,
  club_id INT(11) NOT NULL,
  user_id INT(11) NOT NULL,
  import_type ENUM('users', 'reservations', 'tournaments', 'finances') NOT NULL,
  file_name VARCHAR(255),
  file_type VARCHAR(50),
  total_records INT(11) DEFAULT 0,
  successful_records INT(11) DEFAULT 0,
  failed_records INT(11) DEFAULT 0,
  error_log TEXT,
  status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  completed_at TIMESTAMP NULL,
  PRIMARY KEY (id),
  KEY club_id (club_id),
  KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. MENU ADMINISTRATION MODULE
-- ============================================
CREATE TABLE IF NOT EXISTS menu_items (
  id INT(11) NOT NULL AUTO_INCREMENT,
  club_id INT(11) NOT NULL,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  category VARCHAR(50),
  image VARCHAR(255),
  is_available TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY club_id (club_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
  id INT(11) NOT NULL AUTO_INCREMENT,
  club_id INT(11) NOT NULL,
  user_id INT(11) NULL,
  order_number VARCHAR(50) NOT NULL,
  table_number VARCHAR(20),
  total_amount DECIMAL(10,2) NOT NULL,
  status ENUM('pending', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
  notes TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY order_number (order_number),
  KEY club_id (club_id),
  KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS order_items (
  id INT(11) NOT NULL AUTO_INCREMENT,
  order_id INT(11) NOT NULL,
  menu_item_id INT(11) NOT NULL,
  quantity INT(11) NOT NULL DEFAULT 1,
  unit_price DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  notes TEXT,
  PRIMARY KEY (id),
  KEY order_id (order_id),
  KEY menu_item_id (menu_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 7. CASH REGISTER (CORTE DE CAJA)
-- ============================================
CREATE TABLE IF NOT EXISTS cash_register_sessions (
  id INT(11) NOT NULL AUTO_INCREMENT,
  club_id INT(11) NOT NULL,
  user_id INT(11) NOT NULL,
  opening_balance DECIMAL(10,2) NOT NULL DEFAULT 0,
  closing_balance DECIMAL(10,2) DEFAULT NULL,
  expected_balance DECIMAL(10,2) DEFAULT NULL,
  difference DECIMAL(10,2) DEFAULT NULL,
  notes TEXT,
  opened_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  closed_at TIMESTAMP NULL,
  status ENUM('open', 'closed') DEFAULT 'open',
  PRIMARY KEY (id),
  KEY club_id (club_id),
  KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 8. ENSURE SUBDOMAIN CAN BE NULL (for existing clubs)
-- ============================================
ALTER TABLE clubs MODIFY COLUMN subdomain VARCHAR(50) NULL;

UPDATE clubs 
SET subdomain = LOWER(REPLACE(REPLACE(REPLACE(name, ' ', '-'), 'á', 'a'), 'é', 'e'))
WHERE subdomain IS NULL OR subdomain = '';

-- ============================================
-- 9. ADD PAYMENT HISTORY FIELDS TO USERS
-- ============================================
-- Agrega las columnas antes de actualizar
ALTER TABLE users ADD COLUMN payment_history JSON NULL AFTER membership_ends_at;
ALTER TABLE users ADD COLUMN registration_date DATE NULL AFTER created_at;

UPDATE users 
SET registration_date = DATE(created_at)
WHERE registration_date IS NULL;

-- ============================================
-- INDEXES FOR PERFORMANCE
-- ============================================
-- Crea los índices (si ya existen, ignora el error y sigue)
CREATE INDEX idx_reservations_date ON reservations(reservation_date);
CREATE INDEX idx_income_date ON income_transactions(transaction_date);
CREATE INDEX idx_expense_date ON expense_transactions(transaction_date);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_clubs_status ON clubs(subscription_status);

-- ============================================
-- SUMMARY
-- ============================================
SELECT 'Migration completed successfully!' as status;
