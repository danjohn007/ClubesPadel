-- ClubesPadel System Updates
-- Migration to support new features
-- Date: 2025-10-15

USE clubespadel;

-- ============================================
-- 1. ADD TERMS AND CONDITIONS ACCEPTANCE
-- ============================================

-- Add terms acceptance to users table
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS terms_accepted TINYINT(1) DEFAULT 1 AFTER is_active,
ADD COLUMN IF NOT EXISTS terms_accepted_at TIMESTAMP NULL AFTER terms_accepted;

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

-- Insert default system configurations
INSERT INTO system_configurations (config_key, config_value, config_type, description) VALUES
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
('business_hours', '', 'json', 'Horarios de atención')
ON DUPLICATE KEY UPDATE config_value=VALUES(config_value);

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
  KEY club_id (club_id),
  CONSTRAINT fk_income_categories_club FOREIGN KEY (club_id) REFERENCES clubs (id) ON DELETE CASCADE
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
  KEY club_id (club_id),
  CONSTRAINT fk_expense_categories_club FOREIGN KEY (club_id) REFERENCES clubs (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default system categories
INSERT INTO income_categories (club_id, name, description, is_system) VALUES
(NULL, 'Reservaciones', 'Ingresos por reservaciones de canchas', 1),
(NULL, 'Membresías', 'Ingresos por membresías de jugadores', 1),
(NULL, 'Torneos', 'Ingresos por inscripciones a torneos', 1),
(NULL, 'Tienda', 'Ingresos por venta de productos', 1),
(NULL, 'Clases', 'Ingresos por clases y entrenamientos', 1),
(NULL, 'Otros', 'Otros ingresos', 1);

INSERT INTO expense_categories (club_id, name, description, is_system) VALUES
(NULL, 'Mantenimiento', 'Gastos de mantenimiento de instalaciones', 1),
(NULL, 'Servicios', 'Gastos de servicios (luz, agua, etc)', 1),
(NULL, 'Nómina', 'Gastos de nómina de empleados', 1),
(NULL, 'Materiales', 'Compra de materiales y equipamiento', 1),
(NULL, 'Publicidad', 'Gastos de marketing y publicidad', 1),
(NULL, 'Otros', 'Otros gastos', 1);

-- Add category reference to income and expense transactions
ALTER TABLE income_transactions 
ADD COLUMN IF NOT EXISTS category_id INT(11) NULL AFTER club_id,
ADD KEY category_id (category_id),
ADD CONSTRAINT fk_income_category FOREIGN KEY (category_id) REFERENCES income_categories (id) ON DELETE SET NULL;

ALTER TABLE expense_transactions 
ADD COLUMN IF NOT EXISTS category_id INT(11) NULL AFTER club_id,
ADD KEY category_id (category_id),
ADD CONSTRAINT fk_expense_category FOREIGN KEY (category_id) REFERENCES expense_categories (id) ON DELETE SET NULL;

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
  UNIQUE KEY club_year_month (club_id, year, month),
  CONSTRAINT fk_budgets_club FOREIGN KEY (club_id) REFERENCES clubs (id) ON DELETE CASCADE
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
  KEY user_id (user_id),
  CONSTRAINT fk_import_history_club FOREIGN KEY (club_id) REFERENCES clubs (id) ON DELETE CASCADE,
  CONSTRAINT fk_import_history_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
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
  KEY club_id (club_id),
  CONSTRAINT fk_menu_items_club FOREIGN KEY (club_id) REFERENCES clubs (id) ON DELETE CASCADE
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
  KEY user_id (user_id),
  CONSTRAINT fk_orders_club FOREIGN KEY (club_id) REFERENCES clubs (id) ON DELETE CASCADE,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
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
  KEY menu_item_id (menu_item_id),
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_menu_item FOREIGN KEY (menu_item_id) REFERENCES menu_items (id)
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
  KEY user_id (user_id),
  CONSTRAINT fk_cash_register_club FOREIGN KEY (club_id) REFERENCES clubs (id) ON DELETE CASCADE,
  CONSTRAINT fk_cash_register_user FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 8. ENSURE SUBDOMAIN CAN BE NULL (for existing clubs)
-- ============================================

ALTER TABLE clubs 
MODIFY COLUMN subdomain VARCHAR(50) NULL;

-- Update existing clubs without subdomain
UPDATE clubs 
SET subdomain = LOWER(REPLACE(REPLACE(REPLACE(name, ' ', '-'), 'á', 'a'), 'é', 'e'))
WHERE subdomain IS NULL OR subdomain = '';

-- ============================================
-- 9. ADD PAYMENT HISTORY FIELDS TO USERS
-- ============================================

ALTER TABLE users 
ADD COLUMN IF NOT EXISTS payment_history JSON NULL AFTER membership_ends_at,
ADD COLUMN IF NOT EXISTS registration_date DATE NULL AFTER created_at;

-- Set registration_date from created_at for existing users
UPDATE users 
SET registration_date = DATE(created_at)
WHERE registration_date IS NULL;

-- ============================================
-- INDEXES FOR PERFORMANCE
-- ============================================

-- Add indexes for better query performance
CREATE INDEX IF NOT EXISTS idx_reservations_date ON reservations(reservation_date);
CREATE INDEX IF NOT EXISTS idx_income_date ON income_transactions(transaction_date);
CREATE INDEX IF NOT EXISTS idx_expense_date ON expense_transactions(transaction_date);
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_clubs_status ON clubs(subscription_status);

-- ============================================
-- SUMMARY
-- ============================================
-- This migration adds:
-- 1. Terms and conditions acceptance tracking
-- 2. System configurations table
-- 3. Income and expense categories
-- 4. Budget module
-- 5. Import history tracking
-- 6. Menu administration (orders and menu items)
-- 7. Cash register sessions
-- 8. Fixes subdomain requirement
-- 9. Payment history for users
-- 10. Performance indexes

SELECT 'Migration completed successfully!' as status;
