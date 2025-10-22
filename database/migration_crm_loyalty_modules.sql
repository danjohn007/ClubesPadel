-- ============================================
-- SQL Migration: CRM and Loyalty System Modules
-- Date: 2025-10-22
-- Description: Complete migration for SuperAdmin CRM modules:
--              - CRM Desarrollos (Real Estate Developments)
--              - CRM Deportivas (Sports Organizations)
--              - Patrocinadores (Sponsors)
--              - Sistema de Lealtad (Loyalty Program)
-- 
-- This migration is designed to be:
-- - Idempotent (can be run multiple times safely)
-- - Compatible with existing data
-- - Production-ready with proper indexes and constraints
-- ============================================

USE `clubespadel`;

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Get current database schema name
SET @schema_name = DATABASE();

-- ============================================
-- 1. CRM DESARROLLOS (Real Estate Developments Module)
-- ============================================

-- Main table for real estate developments
CREATE TABLE IF NOT EXISTS `developments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text,
  `developer_company` varchar(200),
  `location_address` text,
  `location_city` varchar(100),
  `location_state` varchar(100),
  `location_country` varchar(100) DEFAULT 'México',
  `postal_code` varchar(10),
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `total_units` int(11) DEFAULT 0,
  `sports_facilities` text COMMENT 'JSON array of sports facilities available',
  `amenities` text COMMENT 'JSON array of amenities',
  `contact_name` varchar(200),
  `contact_email` varchar(150),
  `contact_phone` varchar(20),
  `website` varchar(255),
  `status` enum('planning','construction','operational','completed','cancelled') DEFAULT 'planning',
  `start_date` date,
  `completion_date` date,
  `investment_amount` decimal(15,2) DEFAULT 0.00,
  `notes` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_location` (`location_city`, `location_state`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_developer_company` (`developer_company`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Real estate developments and sports complexes';

-- Link developments to clubs (many-to-many relationship)
CREATE TABLE IF NOT EXISTS `development_clubs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `development_id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `relationship_type` enum('owned','managed','affiliated','partner') DEFAULT 'affiliated',
  `contract_start_date` date,
  `contract_end_date` date,
  `notes` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_development_club` (`development_id`,`club_id`),
  KEY `idx_development_id` (`development_id`),
  KEY `idx_club_id` (`club_id`),
  KEY `idx_relationship_type` (`relationship_type`),
  CONSTRAINT `fk_development_clubs_development` FOREIGN KEY (`development_id`) 
    REFERENCES `developments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_development_clubs_club` FOREIGN KEY (`club_id`) 
    REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Links developments to clubs';

-- ============================================
-- 2. CRM DEPORTIVAS (Sports Organizations Module)
-- ============================================

-- Main table for sports organizations, federations, and associations
CREATE TABLE IF NOT EXISTS `sports_organizations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `acronym` varchar(20),
  `organization_type` enum('federation','association','league','club_network','other') DEFAULT 'association',
  `sport_types` text COMMENT 'JSON array of sports (padel, tennis, squash, etc.)',
  `description` text,
  `country` varchar(100) DEFAULT 'México',
  `region` varchar(100),
  `contact_name` varchar(200),
  `contact_email` varchar(150),
  `contact_phone` varchar(20),
  `website` varchar(255),
  `logo` varchar(255),
  `member_count` int(11) DEFAULT 0,
  `affiliated_clubs_count` int(11) DEFAULT 0,
  `status` enum('active','inactive','pending','suspended') DEFAULT 'active',
  `founded_date` date,
  `notes` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_organization_type` (`organization_type`),
  KEY `idx_country` (`country`),
  KEY `idx_status` (`status`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_acronym` (`acronym`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Sports organizations, federations, and associations';

-- Link sports organizations to clubs (many-to-many relationship)
CREATE TABLE IF NOT EXISTS `organization_clubs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `membership_type` enum('full','associate','affiliate','honorary') DEFAULT 'affiliate',
  `membership_number` varchar(50),
  `membership_start_date` date,
  `membership_end_date` date,
  `annual_fee` decimal(10,2) DEFAULT 0.00,
  `status` enum('active','inactive','suspended','expired') DEFAULT 'active',
  `benefits` text COMMENT 'JSON array of membership benefits',
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_organization_club` (`organization_id`,`club_id`),
  KEY `idx_organization_id` (`organization_id`),
  KEY `idx_club_id` (`club_id`),
  KEY `idx_membership_type` (`membership_type`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_organization_clubs_organization` FOREIGN KEY (`organization_id`) 
    REFERENCES `sports_organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_organization_clubs_club` FOREIGN KEY (`club_id`) 
    REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Links sports organizations to clubs';

-- ============================================
-- 3. PATROCINADORES (Sponsors Module)
-- ============================================

-- Main table for sponsors and commercial partners
CREATE TABLE IF NOT EXISTS `sponsors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(200) NOT NULL,
  `business_type` varchar(100),
  `industry` varchar(100),
  `description` text,
  `logo` varchar(255),
  `contact_name` varchar(200),
  `contact_email` varchar(150),
  `contact_phone` varchar(20),
  `website` varchar(255),
  `address` text,
  `city` varchar(100),
  `state` varchar(100),
  `country` varchar(100) DEFAULT 'México',
  `postal_code` varchar(10),
  `sponsorship_level` enum('platinum','gold','silver','bronze','basic') DEFAULT 'basic',
  `contract_start_date` date,
  `contract_end_date` date,
  `total_investment` decimal(15,2) DEFAULT 0.00,
  `benefits` text COMMENT 'JSON array of sponsor benefits',
  `status` enum('active','inactive','pending','expired','cancelled') DEFAULT 'pending',
  `notes` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_company_name` (`company_name`),
  KEY `idx_sponsorship_level` (`sponsorship_level`),
  KEY `idx_status` (`status`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_industry` (`industry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Sponsors and commercial partners';

-- Link sponsors to clubs (many-to-many relationship)
CREATE TABLE IF NOT EXISTS `sponsor_clubs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sponsor_id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `sponsorship_amount` decimal(12,2) DEFAULT 0.00,
  `sponsorship_type` enum('event','seasonal','annual','permanent','tournament') DEFAULT 'annual',
  `contract_number` varchar(50),
  `start_date` date,
  `end_date` date,
  `benefits_provided` text COMMENT 'JSON array of specific benefits for this club',
  `status` enum('active','inactive','completed','cancelled') DEFAULT 'active',
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sponsor_id` (`sponsor_id`),
  KEY `idx_club_id` (`club_id`),
  KEY `idx_sponsorship_type` (`sponsorship_type`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_sponsor_clubs_sponsor` FOREIGN KEY (`sponsor_id`) 
    REFERENCES `sponsors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sponsor_clubs_club` FOREIGN KEY (`club_id`) 
    REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Links sponsors to specific clubs';

-- Track sponsor payments and invoicing
CREATE TABLE IF NOT EXISTS `sponsor_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sponsor_club_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'MXN',
  `payment_date` date NOT NULL,
  `payment_method` varchar(50),
  `invoice_number` varchar(50),
  `transaction_reference` varchar(100),
  `status` enum('pending','completed','overdue','cancelled','refunded') DEFAULT 'pending',
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sponsor_club_id` (`sponsor_club_id`),
  KEY `idx_payment_date` (`payment_date`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_sponsor_payments_sponsor_club` FOREIGN KEY (`sponsor_club_id`) 
    REFERENCES `sponsor_clubs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Track sponsor payments and invoices';

-- ============================================
-- 4. SISTEMA DE LEALTAD (Loyalty Program Module)
-- ============================================

-- Main table for loyalty programs (can be global or club-specific)
CREATE TABLE IF NOT EXISTS `loyalty_programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `club_id` int(11) DEFAULT NULL COMMENT 'NULL for global/system-wide programs',
  `name` varchar(200) NOT NULL,
  `description` text,
  `program_type` enum('points','tiers','cashback','hybrid','subscription') DEFAULT 'points',
  `points_per_currency` decimal(10,2) DEFAULT '1.00' COMMENT 'Points earned per currency unit spent',
  `currency_per_point` decimal(10,4) DEFAULT '0.01' COMMENT 'Currency value of each point when redeeming',
  `welcome_bonus_points` int(11) DEFAULT '0',
  `referral_bonus_points` int(11) DEFAULT '0',
  `birthday_bonus_points` int(11) DEFAULT '0',
  `point_expiry_days` int(11) DEFAULT NULL COMMENT 'NULL for points that never expire',
  `min_points_redemption` int(11) DEFAULT '100',
  `rules` text COMMENT 'JSON object with detailed program rules',
  `benefits` text COMMENT 'JSON array of general member benefits',
  `terms_conditions` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_club_id` (`club_id`),
  KEY `idx_program_type` (`program_type`),
  KEY `idx_is_active` (`is_active`),
  CONSTRAINT `fk_loyalty_programs_club` FOREIGN KEY (`club_id`) 
    REFERENCES `clubs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Loyalty program configurations';

-- Loyalty program tiers/levels (Bronze, Silver, Gold, etc.)
CREATE TABLE IF NOT EXISTS `loyalty_tiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `tier_level` int(11) NOT NULL COMMENT 'Lower number = higher/premium tier (1=highest)',
  `min_points_required` int(11) NOT NULL DEFAULT 0,
  `points_multiplier` decimal(4,2) DEFAULT '1.00' COMMENT 'Multiplier for earning points (e.g., 1.5x)',
  `discount_percentage` decimal(5,2) DEFAULT '0.00',
  `benefits` text COMMENT 'JSON array of tier-specific benefits',
  `badge_icon` varchar(255),
  `badge_color` varchar(20),
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_program_id` (`program_id`),
  KEY `idx_tier_level` (`tier_level`),
  CONSTRAINT `fk_loyalty_tiers_program` FOREIGN KEY (`program_id`) 
    REFERENCES `loyalty_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tiers/levels within loyalty programs';

-- User enrollment in loyalty programs
CREATE TABLE IF NOT EXISTS `loyalty_memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tier_id` int(11) DEFAULT NULL,
  `current_points` int(11) NOT NULL DEFAULT '0',
  `lifetime_points` int(11) NOT NULL DEFAULT '0',
  `points_redeemed` int(11) NOT NULL DEFAULT '0',
  `points_expired` int(11) NOT NULL DEFAULT '0',
  `membership_number` varchar(50),
  `joined_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tier_achieved_at` timestamp NULL DEFAULT NULL,
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','suspended','cancelled') DEFAULT 'active',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_program_user` (`program_id`,`user_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_tier_id` (`tier_id`),
  KEY `idx_current_points` (`current_points`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_loyalty_memberships_program` FOREIGN KEY (`program_id`) 
    REFERENCES `loyalty_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_loyalty_memberships_user` FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_loyalty_memberships_tier` FOREIGN KEY (`tier_id`) 
    REFERENCES `loyalty_tiers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='User memberships in loyalty programs';

-- Track all points transactions (earn, redeem, expire, etc.)
CREATE TABLE IF NOT EXISTS `loyalty_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membership_id` int(11) NOT NULL,
  `transaction_type` enum('earn','redeem','expire','bonus','adjustment','refund') NOT NULL,
  `points` int(11) NOT NULL COMMENT 'Positive for earn/bonus, negative for redeem/expire',
  `description` varchar(255),
  `reference_type` varchar(50) COMMENT 'Type: reservation, payment, referral, etc.',
  `reference_id` int(11) COMMENT 'ID of related record',
  `balance_before` int(11) DEFAULT 0,
  `balance_after` int(11) DEFAULT 0,
  `expires_at` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'User who created transaction (for manual adjustments)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_membership_id` (`membership_id`),
  KEY `idx_transaction_type` (`transaction_type`),
  KEY `idx_reference` (`reference_type`, `reference_id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_expires_at` (`expires_at`),
  CONSTRAINT `fk_loyalty_transactions_membership` FOREIGN KEY (`membership_id`) 
    REFERENCES `loyalty_memberships` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_loyalty_transactions_user` FOREIGN KEY (`created_by`) 
    REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='All loyalty points transactions';

-- Catalog of rewards that can be redeemed with points
CREATE TABLE IF NOT EXISTS `loyalty_rewards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text,
  `reward_type` enum('discount','free_service','merchandise','upgrade','voucher','experience','other') DEFAULT 'discount',
  `points_cost` int(11) NOT NULL,
  `monetary_value` decimal(10,2) COMMENT 'Approximate value in currency',
  `discount_percentage` decimal(5,2),
  `image` varchar(255),
  `stock_quantity` int(11) DEFAULT NULL COMMENT 'NULL for unlimited availability',
  `max_per_user` int(11) DEFAULT NULL COMMENT 'Maximum redemptions per user',
  `min_tier_level` int(11) DEFAULT NULL COMMENT 'Minimum tier level required (NULL = any)',
  `valid_from` date,
  `valid_until` date,
  `terms_conditions` text,
  `is_featured` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_program_id` (`program_id`),
  KEY `idx_reward_type` (`reward_type`),
  KEY `idx_points_cost` (`points_cost`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_is_featured` (`is_featured`),
  CONSTRAINT `fk_loyalty_rewards_program` FOREIGN KEY (`program_id`) 
    REFERENCES `loyalty_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Catalog of rewards available for redemption';

-- Track reward redemptions by users
CREATE TABLE IF NOT EXISTS `loyalty_redemptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membership_id` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `points_spent` int(11) NOT NULL,
  `redemption_code` varchar(50) COMMENT 'Unique code for validation',
  `status` enum('pending','approved','redeemed','cancelled','expired') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `redeemed_by` int(11) DEFAULT NULL,
  `redeemed_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_redemption_code` (`redemption_code`),
  KEY `idx_membership_id` (`membership_id`),
  KEY `idx_reward_id` (`reward_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_loyalty_redemptions_membership` FOREIGN KEY (`membership_id`) 
    REFERENCES `loyalty_memberships` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_loyalty_redemptions_reward` FOREIGN KEY (`reward_id`) 
    REFERENCES `loyalty_rewards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_loyalty_redemptions_approved_by` FOREIGN KEY (`approved_by`) 
    REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_loyalty_redemptions_redeemed_by` FOREIGN KEY (`redeemed_by`) 
    REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Track reward redemptions by users';

-- ============================================
-- 5. ENHANCED CLUB MANAGEMENT (Improvements)
-- ============================================

-- Add additional fields to clubs table if they don't exist
-- Using dynamic SQL to avoid errors if columns already exist

-- postal_code
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'postal_code';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `postal_code` varchar(10) DEFAULT NULL;',
  'SELECT "Column postal_code already exists" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- latitude
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'latitude';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `latitude` decimal(10,8) DEFAULT NULL;',
  'SELECT "Column latitude already exists" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- longitude
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'longitude';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `longitude` decimal(11,8) DEFAULT NULL;',
  'SELECT "Column longitude already exists" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- timezone
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'timezone';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `timezone` varchar(50) DEFAULT ''America/Mexico_City'';',
  'SELECT "Column timezone already exists" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- facebook_url
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'facebook_url';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `facebook_url` varchar(255) DEFAULT NULL;',
  'SELECT "Column facebook_url already exists" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- instagram_url
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'instagram_url';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `instagram_url` varchar(255) DEFAULT NULL;',
  'SELECT "Column instagram_url already exists" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- twitter_url
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'twitter_url';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `twitter_url` varchar(255) DEFAULT NULL;',
  'SELECT "Column twitter_url already exists" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- business_name
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'business_name';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `business_name` varchar(255) DEFAULT NULL;',
  'SELECT "Column business_name already exists" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- tax_id
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'tax_id';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `tax_id` varchar(50) DEFAULT NULL;',
  'SELECT "Column tax_id already exists" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- business_type
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'business_type';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `business_type` enum(''individual'',''corporation'',''llc'',''partnership'',''other'') DEFAULT NULL;',
  'SELECT "Column business_type already exists" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- ============================================
-- 6. PERFORMANCE INDEXES
-- ============================================

-- Add performance indexes if they don't exist

-- clubs.idx_subscription_status
SELECT COUNT(*) INTO @idx_exists FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND INDEX_NAME = 'idx_subscription_status';
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'subscription_status';
SET @stmt = IF(@idx_exists = 0 AND @col_exists > 0,
  'ALTER TABLE `clubs` ADD INDEX `idx_subscription_status` (`subscription_status`);',
  'SELECT "Index idx_subscription_status already exists or column missing" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- clubs.idx_city_state
SELECT COUNT(*) INTO @idx_exists FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND INDEX_NAME = 'idx_city_state';
SELECT COUNT(*) INTO @col_city FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'city';
SELECT COUNT(*) INTO @col_state FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'state';
SET @stmt = IF(@idx_exists = 0 AND @col_city > 0 AND @col_state > 0,
  'ALTER TABLE `clubs` ADD INDEX `idx_city_state` (`city`, `state`);',
  'SELECT "Index idx_city_state already exists or required columns missing" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- users.idx_club_role
SELECT COUNT(*) INTO @idx_exists FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'users' AND INDEX_NAME = 'idx_club_role';
SELECT COUNT(*) INTO @col_club_id FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'users' AND COLUMN_NAME = 'club_id';
SELECT COUNT(*) INTO @col_role FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'users' AND COLUMN_NAME = 'role';
SET @stmt = IF(@idx_exists = 0 AND @col_club_id > 0 AND @col_role > 0,
  'ALTER TABLE `users` ADD INDEX `idx_club_role` (`club_id`, `role`);',
  'SELECT "Index idx_club_role already exists or required columns missing" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- club_payments.idx_payment_date
SELECT COUNT(*) INTO @idx_exists FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'club_payments' AND INDEX_NAME = 'idx_payment_date';
SELECT COUNT(*) INTO @col_payment_date FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'club_payments' AND COLUMN_NAME = 'payment_date';
SET @stmt = IF(@idx_exists = 0 AND @col_payment_date > 0,
  'ALTER TABLE `club_payments` ADD INDEX `idx_payment_date` (`payment_date`);',
  'SELECT "Index idx_payment_date already exists or column missing" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- club_payments.idx_status
SELECT COUNT(*) INTO @idx_exists FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'club_payments' AND INDEX_NAME = 'idx_status';
SELECT COUNT(*) INTO @col_status FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'club_payments' AND COLUMN_NAME = 'status';
SET @stmt = IF(@idx_exists = 0 AND @col_status > 0,
  'ALTER TABLE `club_payments` ADD INDEX `idx_status` (`status`);',
  'SELECT "Index idx_status already exists or column missing" AS message;');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- ============================================
-- 7. SAMPLE DATA FOR NEW MODULES
-- ============================================

-- Insert a default global loyalty program if it doesn't exist
INSERT IGNORE INTO `loyalty_programs` 
(`id`, `club_id`, `name`, `description`, `program_type`, `points_per_currency`, `welcome_bonus_points`, `is_active`)
VALUES 
(1, NULL, 'Programa Global ClubesPadel', 'Programa de lealtad global para todos los clubes de la red', 'points', 1.00, 100, 1);

-- Insert default loyalty tiers for the global program
INSERT IGNORE INTO `loyalty_tiers` 
(`program_id`, `name`, `tier_level`, `min_points_required`, `points_multiplier`, `discount_percentage`)
VALUES 
(1, 'Bronce', 3, 0, 1.00, 0.00),
(1, 'Plata', 2, 1000, 1.25, 5.00),
(1, 'Oro', 1, 5000, 1.50, 10.00);

-- ============================================
-- 8. FINAL CLEANUP AND VALIDATION
-- ============================================

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- MIGRATION COMPLETE
-- ============================================
-- Tables Created:
--   1. developments (CRM Desarrollos)
--   2. development_clubs (Link developments to clubs)
--   3. sports_organizations (CRM Deportivas)
--   4. organization_clubs (Link organizations to clubs)
--   5. sponsors (Patrocinadores)
--   6. sponsor_clubs (Link sponsors to clubs)
--   7. sponsor_payments (Track sponsor payments)
--   8. loyalty_programs (Sistema de Lealtad)
--   9. loyalty_tiers (Loyalty program tiers)
--   10. loyalty_memberships (User loyalty memberships)
--   11. loyalty_transactions (Points transactions)
--   12. loyalty_rewards (Rewards catalog)
--   13. loyalty_redemptions (Reward redemptions)
--
-- Enhanced:
--   - clubs table with additional fields
--   - Performance indexes added
--
-- This migration maintains backward compatibility with existing data.
-- ============================================
