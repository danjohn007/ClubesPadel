-- ClubesPadel System Improvements Migration
-- This migration adds support for:
-- 1. System-wide settings for SuperAdmin
-- 2. Terms acceptance tracking
-- 3. Additional phone validation

USE `clubespadel`;

-- ============================================
-- SYSTEM SETTINGS (SuperAdmin Level)
-- ============================================

-- Create system_settings table for global configuration
CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  `setting_group` varchar(50) DEFAULT 'general',
  `description` text,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default system settings
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `setting_group`, `description`) VALUES
-- PayPal Configuration
('paypal_mode', 'sandbox', 'payment', 'PayPal mode: sandbox or live'),
('paypal_client_id', '', 'payment', 'PayPal client ID'),
('paypal_secret', '', 'payment', 'PayPal secret key'),

-- Email Configuration
('email_from_address', 'noreply@clubespadel.com', 'email', 'System email from address'),
('email_from_name', 'ClubesPadel', 'email', 'System email from name'),
('smtp_host', '', 'email', 'SMTP server host'),
('smtp_port', '587', 'email', 'SMTP server port'),
('smtp_username', '', 'email', 'SMTP username'),
('smtp_password', '', 'email', 'SMTP password'),
('smtp_encryption', 'tls', 'email', 'SMTP encryption: tls or ssl'),

-- Currency and Tax
('currency_symbol', '$', 'financial', 'Currency symbol'),
('currency_code', 'MXN', 'financial', 'Currency code (ISO 4217)'),
('tax_rate', '16', 'financial', 'Tax rate percentage'),

-- Public Site Settings
('site_name', 'ClubesPadel', 'general', 'Public site name'),
('site_logo', '', 'general', 'Public site logo URL'),
('site_description', 'Sistema de Administración de Clubes de Padel', 'general', 'Public site description'),
('site_keywords', 'padel, clubs, reservaciones, torneos', 'general', 'SEO keywords'),

-- Terms and Conditions
('terms_and_conditions', 'Términos y condiciones del sistema...', 'legal', 'Terms and conditions text'),
('privacy_policy', 'Política de privacidad...', 'legal', 'Privacy policy text'),

-- WhatsApp Chatbot
('whatsapp_chatbot_number', '', 'communication', 'WhatsApp chatbot number'),
('whatsapp_enabled', '0', 'communication', 'Enable WhatsApp integration'),

-- Bank Account Details
('bank_account_info', '', 'payment', 'Bank account information for deposits (JSON format)'),

-- Contact Information
('contact_phone_1', '', 'contact', 'Primary contact phone'),
('contact_phone_2', '', 'contact', 'Secondary contact phone'),
('business_hours', 'Lunes a Viernes: 9:00 AM - 9:00 PM\nSábados: 9:00 AM - 6:00 PM\nDomingos: 10:00 AM - 2:00 PM', 'contact', 'Business hours'),
('contact_email', 'info@clubespadel.com', 'contact', 'Contact email'),
('support_email', 'soporte@clubespadel.com', 'contact', 'Support email'),

-- General System Settings
('maintenance_mode', '0', 'general', 'Enable maintenance mode'),
('allow_registration', '1', 'general', 'Allow new user registrations'),
('default_language', 'es', 'general', 'Default system language'),
('timezone', 'America/Mexico_City', 'general', 'System timezone'),
('date_format', 'd/m/Y', 'general', 'Date format'),
('time_format', 'H:i', 'general', 'Time format');

-- ============================================
-- USER TABLE UPDATES
-- ============================================

-- Add terms_accepted field to users table (if not exists)
ALTER TABLE `users` 
ADD COLUMN IF NOT EXISTS `terms_accepted_at` datetime DEFAULT NULL 
AFTER `is_active`;

-- ============================================
-- CLUBS TABLE UPDATES
-- ============================================

-- Make subdomain nullable (will be auto-generated if not provided)
ALTER TABLE `clubs` 
MODIFY COLUMN `subdomain` varchar(50) NULL;

-- Update existing clubs without subdomain
UPDATE `clubs` 
SET `subdomain` = CONCAT('club-', `id`) 
WHERE `subdomain` IS NULL OR `subdomain` = '';

-- Add back the unique constraint
ALTER TABLE `clubs` 
ADD UNIQUE KEY IF NOT EXISTS `unique_subdomain` (`subdomain`);

-- ============================================
-- INDEXES FOR PERFORMANCE
-- ============================================

-- Add index to system_settings for faster lookups
ALTER TABLE `system_settings` 
ADD INDEX IF NOT EXISTS `idx_setting_group` (`setting_group`);

-- Ensure phone fields have proper indexes where needed
ALTER TABLE `users` 
ADD INDEX IF NOT EXISTS `idx_phone` (`phone`);

ALTER TABLE `clubs` 
ADD INDEX IF NOT EXISTS `idx_phone` (`phone`);

-- ============================================
-- COMMENTS AND DOCUMENTATION
-- ============================================

ALTER TABLE `system_settings` 
COMMENT = 'Global system-wide settings managed by SuperAdmin';

ALTER TABLE `users` 
MODIFY COLUMN `terms_accepted_at` datetime DEFAULT NULL 
COMMENT 'Timestamp when user accepted terms and conditions';
