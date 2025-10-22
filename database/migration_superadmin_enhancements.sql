-- ============================================
-- SQL Migration: SuperAdmin Module Enhancements (import-ready)
-- Date: 2025-10-22
-- Description: Updates to support new CRM modules and enhanced club management
-- Compatibility notes:
--  - Uses information_schema checks and PREPARE/EXECUTE to run ALTER only when needed
--  - Does NOT use AFTER clauses when adding columns (avoids errors if referenced columns don't exist)
--  - For index creation additionally checks that referenced columns exist
-- ============================================

USE `clubespadel`;

-- ============================================
-- 1. DEVELOPMENTS MODULE (CRM Desarrollos)
-- ============================================

-- Table for real estate developments
CREATE TABLE IF NOT EXISTS `developments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text,
  `developer_company` varchar(200),
  `location_address` text,
  `location_city` varchar(100),
  `location_state` varchar(100),
  `location_country` varchar(100) DEFAULT 'México',
  `total_units` int(11),
  `sports_facilities` text COMMENT 'JSON array of sports facilities',
  `amenities` text COMMENT 'JSON array of amenities',
  `contact_name` varchar(200),
  `contact_email` varchar(150),
  `contact_phone` varchar(20),
  `website` varchar(255),
  `status` enum('planning','construction','operational','completed') DEFAULT 'planning',
  `start_date` date,
  `completion_date` date,
  `investment_amount` decimal(15,2),
  `notes` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `location_city` (`location_city`),
  KEY `location_state` (`location_state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Link developments to clubs
CREATE TABLE IF NOT EXISTS `development_clubs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `development_id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `relationship_type` enum('owned','managed','affiliated') DEFAULT 'affiliated',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `development_club` (`development_id`,`club_id`),
  KEY `development_id` (`development_id`),
  KEY `club_id` (`club_id`),
  CONSTRAINT `fk_development_clubs_development` FOREIGN KEY (`development_id`) REFERENCES `developments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_development_clubs_club` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 2. SPORTS ORGANIZATIONS MODULE (CRM Deportivas)
-- ============================================

-- Table for sports organizations and associations
CREATE TABLE IF NOT EXISTS `sports_organizations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `acronym` varchar(20),
  `organization_type` enum('federation','association','league','club_network') DEFAULT 'association',
  `sport_types` text COMMENT 'JSON array of sports (padel, tennis, etc.)',
  `description` text,
  `country` varchar(100) DEFAULT 'México',
  `region` varchar(100),
  `contact_name` varchar(200),
  `contact_email` varchar(150),
  `contact_phone` varchar(20),
  `website` varchar(255),
  `logo` varchar(255),
  `member_count` int(11),
  `affiliated_clubs_count` int(11),
  `status` enum('active','inactive','pending') DEFAULT 'active',
  `founded_date` date,
  `notes` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `organization_type` (`organization_type`),
  KEY `country` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Link sports organizations to clubs
CREATE TABLE IF NOT EXISTS `organization_clubs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `membership_type` enum('full','associate','affiliate') DEFAULT 'affiliate',
  `membership_start_date` date,
  `membership_end_date` date,
  `membership_fee` decimal(10,2),
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `organization_club` (`organization_id`,`club_id`),
  KEY `organization_id` (`organization_id`),
  KEY `club_id` (`club_id`),
  CONSTRAINT `fk_organization_clubs_organization` FOREIGN KEY (`organization_id`) REFERENCES `sports_organizations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_organization_clubs_club` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. SPONSORS MODULE (Patrocinadores)
-- ============================================

-- Table for sponsors and partners
CREATE TABLE IF NOT EXISTS `sponsors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(200) NOT NULL,
  `business_type` varchar(100),
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
  `sponsorship_level` enum('platinum','gold','silver','bronze','basic') DEFAULT 'basic',
  `contract_start_date` date,
  `contract_end_date` date,
  `annual_investment` decimal(12,2),
  `benefits` text COMMENT 'JSON array of sponsor benefits',
  `status` enum('active','inactive','pending','expired') DEFAULT 'pending',
  `notes` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sponsorship_level` (`sponsorship_level`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Link sponsors to clubs
CREATE TABLE IF NOT EXISTS `sponsor_clubs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sponsor_id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `sponsorship_amount` decimal(10,2),
  `sponsorship_type` enum('event','seasonal','annual','permanent') DEFAULT 'annual',
  `start_date` date,
  `end_date` date,
  `benefits_provided` text,
  `status` enum('active','inactive','completed') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sponsor_id` (`sponsor_id`),
  KEY `club_id` (`club_id`),
  CONSTRAINT `fk_sponsor_clubs_sponsor` FOREIGN KEY (`sponsor_id`) REFERENCES `sponsors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_sponsor_clubs_club` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. LOYALTY PROGRAM MODULE (Sistema de Lealtad)
-- ============================================

-- Table for loyalty programs
CREATE TABLE IF NOT EXISTS `loyalty_programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `club_id` int(11) DEFAULT NULL COMMENT 'NULL for global programs',
  `name` varchar(200) NOT NULL,
  `description` text,
  `program_type` enum('points','tiers','cashback','hybrid') DEFAULT 'points',
  `points_per_currency` decimal(10,2) DEFAULT '1.00' COMMENT 'Points earned per currency unit spent',
  `currency_per_point` decimal(10,4) DEFAULT '0.01' COMMENT 'Currency value of each point',
  `welcome_bonus_points` int(11) DEFAULT '0',
  `referral_bonus_points` int(11) DEFAULT '0',
  `birthday_bonus_points` int(11) DEFAULT '0',
  `point_expiry_days` int(11) DEFAULT NULL COMMENT 'NULL for no expiry',
  `min_points_redemption` int(11) DEFAULT '100',
  `rules` text COMMENT 'JSON object with program rules',
  `benefits` text COMMENT 'JSON array of member benefits',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `club_id` (`club_id`),
  CONSTRAINT `fk_loyalty_programs_club` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for loyalty program tiers/levels
CREATE TABLE IF NOT EXISTS `loyalty_tiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `tier_level` int(11) NOT NULL COMMENT 'Lower number = higher tier',
  `min_points_required` int(11) NOT NULL,
  `points_multiplier` decimal(4,2) DEFAULT '1.00',
  `benefits` text COMMENT 'JSON array of tier-specific benefits',
  `badge_icon` varchar(255),
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `program_id` (`program_id`),
  CONSTRAINT `fk_loyalty_tiers_program` FOREIGN KEY (`program_id`) REFERENCES `loyalty_programs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for user loyalty membership
CREATE TABLE IF NOT EXISTS `loyalty_memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tier_id` int(11) DEFAULT NULL,
  `current_points` int(11) NOT NULL DEFAULT '0',
  `lifetime_points` int(11) NOT NULL DEFAULT '0',
  `points_redeemed` int(11) NOT NULL DEFAULT '0',
  `membership_number` varchar(50),
  `joined_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `program_user` (`program_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `tier_id` (`tier_id`),
  CONSTRAINT `fk_loyalty_memberships_program` FOREIGN KEY (`program_id`) REFERENCES `loyalty_programs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_loyalty_memberships_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_loyalty_memberships_tier` FOREIGN KEY (`tier_id`) REFERENCES `loyalty_tiers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for loyalty points transactions
CREATE TABLE IF NOT EXISTS `loyalty_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membership_id` int(11) NOT NULL,
  `transaction_type` enum('earn','redeem','expire','bonus','adjustment') NOT NULL,
  `points` int(11) NOT NULL,
  `description` varchar(255),
  `reference_type` varchar(50) COMMENT 'reservation, payment, etc.',
  `reference_id` int(11),
  `expires_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `membership_id` (`membership_id`),
  KEY `transaction_type` (`transaction_type`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `fk_loyalty_transactions_membership` FOREIGN KEY (`membership_id`) REFERENCES `loyalty_memberships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for loyalty rewards catalog
CREATE TABLE IF NOT EXISTS `loyalty_rewards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text,
  `reward_type` enum('discount','free_service','merchandise','upgrade','other') DEFAULT 'discount',
  `points_cost` int(11) NOT NULL,
  `monetary_value` decimal(10,2),
  `image` varchar(255),
  `stock_quantity` int(11) DEFAULT NULL COMMENT 'NULL for unlimited',
  `max_per_user` int(11) DEFAULT NULL,
  `valid_from` date,
  `valid_until` date,
  `terms_conditions` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `program_id` (`program_id`),
  CONSTRAINT `fk_loyalty_rewards_program` FOREIGN KEY (`program_id`) REFERENCES `loyalty_programs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for loyalty reward redemptions
CREATE TABLE IF NOT EXISTS `loyalty_redemptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membership_id` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `points_spent` int(11) NOT NULL,
  `redemption_code` varchar(50),
  `status` enum('pending','approved','redeemed','cancelled','expired') DEFAULT 'pending',
  `redeemed_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `membership_id` (`membership_id`),
  KEY `reward_id` (`reward_id`),
  KEY `redemption_code` (`redemption_code`),
  CONSTRAINT `fk_loyalty_redemptions_membership` FOREIGN KEY (`membership_id`) REFERENCES `loyalty_memberships` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_loyalty_redemptions_reward` FOREIGN KEY (`reward_id`) REFERENCES `loyalty_rewards` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. ENHANCED CLUB MANAGEMENT (compatibility-safe)
-- ============================================

-- We'll use the current database as schema name
SET @schema_name = DATABASE();

-- Add postal_code if missing (no AFTER to avoid reference errors)
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'postal_code';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `postal_code` varchar(10) DEFAULT NULL;',
  'SELECT \"column postal_code already exists\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- latitude
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'latitude';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `latitude` decimal(10,8) DEFAULT NULL;',
  'SELECT \"column latitude already exists\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- longitude
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'longitude';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `longitude` decimal(11,8) DEFAULT NULL;',
  'SELECT \"column longitude already exists\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- timezone
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'timezone';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `timezone` varchar(50) DEFAULT ''America/Mexico_City'';',
  'SELECT \"column timezone already exists\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- social media: facebook_url
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'facebook_url';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `facebook_url` varchar(255) DEFAULT NULL;',
  'SELECT \"column facebook_url already exists\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- instagram_url
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'instagram_url';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `instagram_url` varchar(255) DEFAULT NULL;',
  'SELECT \"column instagram_url already exists\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- twitter_url
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'twitter_url';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `twitter_url` varchar(255) DEFAULT NULL;',
  'SELECT \"column twitter_url already exists\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- business_name
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'business_name';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `business_name` varchar(255) DEFAULT NULL;',
  'SELECT \"column business_name already exists\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- tax_id
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'tax_id';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `tax_id` varchar(50) DEFAULT NULL;',
  'SELECT \"column tax_id already exists\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- business_type (enum)
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'business_type';
SET @stmt = IF(@col_exists = 0,
  'ALTER TABLE `clubs` ADD COLUMN `business_type` enum(''individual'',''corporation'',''llc'',''partnership'',''other'') DEFAULT NULL;',
  'SELECT \"column business_type already exists\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- ============================================
-- 6. INDEXES FOR BETTER PERFORMANCE (compatibility-safe)
-- ============================================
-- For each index, check index existence AND check referenced columns exist before creating index.

-- clubs.idx_subscription_status (requires column subscription_status)
SELECT COUNT(*) INTO @idx_exists FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND INDEX_NAME = 'idx_subscription_status';
SELECT COUNT(*) INTO @col_exists FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'subscription_status';
SET @stmt = IF(@idx_exists = 0 AND @col_exists > 0,
  'ALTER TABLE `clubs` ADD INDEX `idx_subscription_status` (`subscription_status`);',
  'SELECT \"index idx_subscription_status already exists or column missing\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- clubs.idx_city_state (requires columns city and state)
SELECT COUNT(*) INTO @idx_exists FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND INDEX_NAME = 'idx_city_state';
SELECT COUNT(*) INTO @col_city FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'city';
SELECT COUNT(*) INTO @col_state FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'clubs' AND COLUMN_NAME = 'state';
SET @stmt = IF(@idx_exists = 0 AND @col_city > 0 AND @col_state > 0,
  'ALTER TABLE `clubs` ADD INDEX `idx_city_state` (`city`, `state`);',
  'SELECT \"index idx_city_state already exists or required columns missing\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- users.idx_club_role (requires columns club_id and role)
SELECT COUNT(*) INTO @idx_exists FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'users' AND INDEX_NAME = 'idx_club_role';
SELECT COUNT(*) INTO @col_club_id FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'users' AND COLUMN_NAME = 'club_id';
SELECT COUNT(*) INTO @col_role FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'users' AND COLUMN_NAME = 'role';
SET @stmt = IF(@idx_exists = 0 AND @col_club_id > 0 AND @col_role > 0,
  'ALTER TABLE `users` ADD INDEX `idx_club_role` (`club_id`, `role`);',
  'SELECT \"index idx_club_role already exists or required columns missing\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- club_payments.idx_payment_date (requires payment_date)
SELECT COUNT(*) INTO @idx_exists FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'club_payments' AND INDEX_NAME = 'idx_payment_date';
SELECT COUNT(*) INTO @col_payment_date FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'club_payments' AND COLUMN_NAME = 'payment_date';
SET @stmt = IF(@idx_exists = 0 AND @col_payment_date > 0,
  'ALTER TABLE `club_payments` ADD INDEX `idx_payment_date` (`payment_date`);',
  'SELECT \"index idx_payment_date already exists or column missing\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- club_payments.idx_status (requires status)
SELECT COUNT(*) INTO @idx_exists FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'club_payments' AND INDEX_NAME = 'idx_status';
SELECT COUNT(*) INTO @col_status FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = @schema_name AND TABLE_NAME = 'club_payments' AND COLUMN_NAME = 'status';
SET @stmt = IF(@idx_exists = 0 AND @col_status > 0,
  'ALTER TABLE `club_payments` ADD INDEX `idx_status` (`status`);',
  'SELECT \"index idx_status already exists or column missing\";');
PREPARE ps FROM @stmt; EXECUTE ps; DEALLOCATE PREPARE ps;

-- ============================================
-- 7. SAMPLE DATA FOR NEW MODULES (Optional)
-- ============================================

-- Insert a default global loyalty program
INSERT IGNORE INTO `loyalty_programs` 
(`id`, `club_id`, `name`, `description`, `program_type`, `points_per_currency`, `welcome_bonus_points`, `is_active`)
VALUES 
(1, NULL, 'Programa Global ClubesPadel', 'Programa de lealtad global para todos los clubes', 'points', 1.00, 100, 1);

-- Insert loyalty tiers for the global program
INSERT IGNORE INTO `loyalty_tiers` 
(`program_id`, `name`, `tier_level`, `min_points_required`, `points_multiplier`)
VALUES 
(1, 'Bronce', 3, 0, 1.00),
(1, 'Plata', 2, 1000, 1.25),
(1, 'Oro', 1, 5000, 1.50);

-- ============================================
-- END OF MIGRATION
-- ============================================
