-- ============================================
-- Sample Data for CRM and Loyalty System Modules
-- Date: 2025-10-22
-- Description: Sample data for testing and demonstration
-- ============================================

USE `clubespadel`;

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- 1. SAMPLE DATA - CRM DESARROLLOS
-- ============================================

-- Insert sample real estate developments
INSERT INTO `developments` 
(`name`, `description`, `developer_company`, `location_address`, `location_city`, `location_state`, 
`location_country`, `postal_code`, `total_units`, `sports_facilities`, `amenities`, 
`contact_name`, `contact_email`, `contact_phone`, `website`, `status`, `start_date`, 
`completion_date`, `investment_amount`, `is_active`)
VALUES
('Residencial Padel Park', 
'Complejo residencial con enfoque deportivo, incluye 6 canchas de pádel profesionales', 
'Desarrollos Deportivos SA', 
'Av. Principal 1234', 
'Monterrey', 
'Nuevo León', 
'México', 
'64000', 
150, 
'["padel", "tennis", "swimming", "gym", "running_track"]', 
'["club_house", "restaurant", "spa", "parking", "security_24h", "kids_area"]',
'Juan Pérez', 
'jperez@desarrollosdeportivos.com', 
'+52-81-1234-5678', 
'https://www.padelpark.com', 
'operational', 
'2022-01-15', 
'2023-12-30', 
25000000.00, 
1),

('Club Deportivo Vista Hermosa', 
'Desarrollo premium con instalaciones deportivas de clase mundial', 
'Vista Hermosa Properties', 
'Boulevard del Valle 5678', 
'Guadalajara', 
'Jalisco', 
'México', 
'44100', 
200, 
'["padel", "tennis", "squash", "basketball", "soccer"]', 
'["restaurant", "cafe", "pro_shop", "locker_rooms", "sauna"]',
'María González', 
'mgonzalez@vistahermosa.com', 
'+52-33-9876-5432', 
'https://www.vistahermosa.com', 
'construction', 
'2024-03-01', 
'2025-06-30', 
35000000.00, 
1),

('Centro Deportivo Las Palmas', 
'Instalaciones deportivas comunitarias con enfoque en pádel y tenis', 
'Inmobiliaria Las Palmas', 
'Calle del Deporte 890', 
'Querétaro', 
'Querétaro', 
'México', 
'76000', 
80, 
'["padel", "tennis", "volleyball"]', 
'["parking", "cafeteria", "pro_shop"]',
'Carlos Rodríguez', 
'crodriguez@laspalmas.com', 
'+52-442-123-4567', 
'https://www.laspalmas.com.mx', 
'operational', 
'2021-06-01', 
'2022-09-15', 
12000000.00, 
1),

('Proyecto Padel City', 
'Mega complejo deportivo en planificación con 12 canchas de pádel', 
'Grupo Constructor del Norte', 
'Zona Industrial Norte', 
'Tijuana', 
'Baja California', 
'México', 
'22000', 
300, 
'["padel", "tennis", "gym", "indoor_soccer"]', 
'["restaurant", "hotel", "conference_rooms", "parking"]',
'Ana Martínez', 
'amartinez@constructordelnorte.com', 
'+52-664-555-1234', 
'https://www.padelcity.mx', 
'planning', 
'2025-01-01', 
'2026-12-31', 
50000000.00, 
1);

-- Link some developments to clubs (assuming clubs with IDs 1-3 exist)
-- This establishes relationships between developments and operating clubs
INSERT INTO `development_clubs` 
(`development_id`, `club_id`, `relationship_type`, `contract_start_date`, `is_active`)
VALUES
(1, 1, 'owned', '2023-01-01', 1),
(2, 2, 'managed', '2024-06-01', 1),
(3, 3, 'affiliated', '2022-10-01', 1);

-- ============================================
-- 2. SAMPLE DATA - CRM DEPORTIVAS
-- ============================================

-- Insert sample sports organizations
INSERT INTO `sports_organizations` 
(`name`, `acronym`, `organization_type`, `sport_types`, `description`, `country`, `region`,
`contact_name`, `contact_email`, `contact_phone`, `website`, `member_count`, 
`affiliated_clubs_count`, `status`, `founded_date`, `is_active`)
VALUES
('Federación Mexicana de Pádel', 
'FMP', 
'federation', 
'["padel"]', 
'Organismo rector del pádel en México, afiliado a la Federación Internacional de Pádel', 
'México', 
'Nacional', 
'Roberto Sánchez', 
'contacto@fmpadel.mx', 
'+52-55-1234-5678', 
'https://www.fmpadel.mx', 
5000, 
150, 
'active', 
'2010-03-15', 
1),

('Asociación de Clubes de Pádel del Norte', 
'ACPN', 
'association', 
'["padel", "tennis"]', 
'Asociación regional que agrupa clubes del norte de México', 
'México', 
'Norte', 
'Laura Hernández', 
'info@acpnorte.com', 
'+52-81-9876-5432', 
'https://www.acpnorte.com', 
2500, 
45, 
'active', 
'2015-09-20', 
1),

('Liga Nacional de Pádel Profesional', 
'LNPP', 
'league', 
'["padel"]', 
'Liga profesional de pádel con torneos en todo el país', 
'México', 
'Nacional', 
'Miguel Torres', 
'contacto@lnpp.mx', 
'+52-55-5555-1234', 
'https://www.lnpp.mx', 
300, 
80, 
'active', 
'2018-01-10', 
1),

('Red de Clubes Deportivos Jalisco', 
'RCDJ', 
'club_network', 
'["padel", "tennis", "squash"]', 
'Red de clubes deportivos del estado de Jalisco', 
'México', 
'Jalisco', 
'Patricia López', 
'info@rcdjalisco.com', 
'+52-33-1234-9876', 
'https://www.rcdjalisco.com', 
1200, 
30, 
'active', 
'2012-06-01', 
1),

('Confederación Latinoamericana de Pádel', 
'COLAPAD', 
'federation', 
'["padel"]', 
'Confederación que agrupa las federaciones de pádel de América Latina', 
'México', 
'Internacional', 
'Fernando Ruiz', 
'secretaria@colapad.org', 
'+52-55-9999-8888', 
'https://www.colapad.org', 
50000, 
500, 
'active', 
'2008-11-30', 
1);

-- Link organizations to clubs (assuming clubs with IDs 1-4 exist)
INSERT INTO `organization_clubs` 
(`organization_id`, `club_id`, `membership_type`, `membership_start_date`, `annual_fee`, `status`)
VALUES
(1, 1, 'full', '2023-01-01', 5000.00, 'active'),
(1, 2, 'full', '2023-01-01', 5000.00, 'active'),
(1, 3, 'associate', '2023-06-01', 3000.00, 'active'),
(2, 1, 'affiliate', '2023-01-01', 2000.00, 'active'),
(3, 1, 'full', '2024-01-01', 8000.00, 'active'),
(3, 2, 'full', '2024-01-01', 8000.00, 'active'),
(4, 2, 'affiliate', '2023-09-01', 1500.00, 'active');

-- ============================================
-- 3. SAMPLE DATA - PATROCINADORES
-- ============================================

-- Insert sample sponsors
INSERT INTO `sponsors` 
(`company_name`, `business_type`, `industry`, `description`, `contact_name`, `contact_email`, 
`contact_phone`, `website`, `city`, `state`, `country`, `sponsorship_level`, 
`contract_start_date`, `contract_end_date`, `total_investment`, `status`, `is_active`)
VALUES
('Deportes Pro MX', 
'Retail', 
'Sports Equipment', 
'Cadena líder de tiendas de artículos deportivos en México', 
'Roberto Méndez', 
'rmendez@deportespro.com', 
'+52-55-1111-2222', 
'https://www.deportespro.com', 
'Ciudad de México', 
'CDMX', 
'México', 
'platinum', 
'2024-01-01', 
'2024-12-31', 
500000.00, 
'active', 
1),

('Bebidas RefreshCo', 
'Manufacturing', 
'Beverages', 
'Fabricante de bebidas deportivas y energéticas', 
'Sandra García', 
'sgarcia@refreshco.com', 
'+52-33-2222-3333', 
'https://www.refreshco.com', 
'Guadalajara', 
'Jalisco', 
'México', 
'gold', 
'2024-01-01', 
'2024-12-31', 
300000.00, 
'active', 
1),

('Seguros Protección Total', 
'Insurance', 
'Financial Services', 
'Compañía de seguros con enfoque en deportes y actividades recreativas', 
'Luis Fernández', 
'lfernandez@protecciontotal.com', 
'+52-81-3333-4444', 
'https://www.protecciontotal.com', 
'Monterrey', 
'Nuevo León', 
'México', 
'silver', 
'2024-03-01', 
'2025-02-28', 
150000.00, 
'active', 
1),

('TechSport Wearables', 
'Technology', 
'Consumer Electronics', 
'Fabricante de dispositivos wearables para deportistas', 
'Carmen Jiménez', 
'cjimenez@techsport.com', 
'+52-55-4444-5555', 
'https://www.techsport.com', 
'Ciudad de México', 
'CDMX', 
'México', 
'bronze', 
'2024-06-01', 
'2025-05-31', 
80000.00, 
'active', 
1),

('Nutrición Atleta Pro', 
'Retail', 
'Health & Nutrition', 
'Tienda especializada en suplementos y nutrición deportiva', 
'Daniel Castro', 
'dcastro@atletapro.com', 
'+52-442-5555-6666', 
'https://www.atletapro.com', 
'Querétaro', 
'Querétaro', 
'México', 
'basic', 
'2024-07-01', 
'2024-12-31', 
40000.00, 
'active', 
1);

-- Link sponsors to clubs (assuming clubs with IDs 1-4 exist)
INSERT INTO `sponsor_clubs` 
(`sponsor_id`, `club_id`, `sponsorship_amount`, `sponsorship_type`, `start_date`, `end_date`, `status`)
VALUES
(1, 1, 150000.00, 'annual', '2024-01-01', '2024-12-31', 'active'),
(1, 2, 120000.00, 'annual', '2024-01-01', '2024-12-31', 'active'),
(2, 1, 100000.00, 'annual', '2024-01-01', '2024-12-31', 'active'),
(2, 3, 80000.00, 'annual', '2024-01-01', '2024-12-31', 'active'),
(3, 1, 50000.00, 'annual', '2024-03-01', '2025-02-28', 'active'),
(4, 2, 30000.00, 'seasonal', '2024-06-01', '2024-12-31', 'active'),
(5, 3, 20000.00, 'event', '2024-07-01', '2024-09-30', 'completed');

-- Sample sponsor payments
INSERT INTO `sponsor_payments` 
(`sponsor_club_id`, `amount`, `payment_date`, `payment_method`, `invoice_number`, `status`)
VALUES
(1, 75000.00, '2024-01-15', 'Wire Transfer', 'INV-2024-001', 'completed'),
(1, 75000.00, '2024-07-15', 'Wire Transfer', 'INV-2024-002', 'completed'),
(2, 60000.00, '2024-01-20', 'Check', 'INV-2024-003', 'completed'),
(2, 60000.00, '2024-07-20', 'Check', 'INV-2024-004', 'pending'),
(3, 50000.00, '2024-02-01', 'Wire Transfer', 'INV-2024-005', 'completed'),
(3, 50000.00, '2024-08-01', 'Wire Transfer', 'INV-2024-006', 'completed'),
(4, 40000.00, '2024-01-25', 'Credit Card', 'INV-2024-007', 'completed'),
(5, 50000.00, '2024-03-15', 'Wire Transfer', 'INV-2024-008', 'completed'),
(6, 30000.00, '2024-06-15', 'Check', 'INV-2024-009', 'completed'),
(7, 20000.00, '2024-07-10', 'Wire Transfer', 'INV-2024-010', 'completed');

-- ============================================
-- 4. SAMPLE DATA - SISTEMA DE LEALTAD
-- ============================================

-- Global loyalty program already inserted in migration
-- Let's add a club-specific loyalty program

INSERT INTO `loyalty_programs` 
(`club_id`, `name`, `description`, `program_type`, `points_per_currency`, `currency_per_point`,
`welcome_bonus_points`, `referral_bonus_points`, `birthday_bonus_points`, `point_expiry_days`,
`min_points_redemption`, `is_active`)
VALUES
(1, 'Club Demo Rewards', 
'Programa exclusivo de recompensas del Club Demo', 
'hybrid', 
2.00, 
0.02, 
200, 
500, 
100, 
365, 
50, 
1);

-- Add tiers for the club-specific program
INSERT INTO `loyalty_tiers` 
(`program_id`, `name`, `tier_level`, `min_points_required`, `points_multiplier`, `discount_percentage`)
VALUES
(2, 'Iniciante', 4, 0, 1.00, 0.00),
(2, 'Regular', 3, 500, 1.20, 5.00),
(2, 'Premium', 2, 2000, 1.50, 10.00),
(2, 'Elite', 1, 5000, 2.00, 15.00);

-- Sample loyalty rewards for global program
INSERT INTO `loyalty_rewards` 
(`program_id`, `name`, `description`, `reward_type`, `points_cost`, `monetary_value`, 
`discount_percentage`, `stock_quantity`, `valid_from`, `valid_until`, `is_active`, `is_featured`)
VALUES
(1, 'Descuento 10% en Reservación', 
'Obtén 10% de descuento en tu próxima reservación', 
'discount', 
200, 
50.00, 
10.00, 
NULL, 
'2024-01-01', 
'2024-12-31', 
1, 
1),

(1, 'Hora Gratis de Cancha', 
'Una hora gratis de cancha en horario matutino', 
'free_service', 
500, 
150.00, 
NULL, 
100, 
'2024-01-01', 
'2024-12-31', 
1, 
1),

(1, 'Camiseta ClubesPadel', 
'Camiseta oficial de la red ClubesPadel', 
'merchandise', 
1000, 
350.00, 
NULL, 
50, 
'2024-01-01', 
'2024-12-31', 
1, 
0),

(1, 'Upgrade a Cancha Premium', 
'Upgrade gratuito a cancha premium en tu próxima reservación', 
'upgrade', 
300, 
100.00, 
NULL, 
NULL, 
'2024-01-01', 
'2024-12-31', 
1, 
1),

(1, 'Clase con Entrenador', 
'Una clase de 1 hora con entrenador certificado', 
'free_service', 
800, 
400.00, 
NULL, 
30, 
'2024-01-01', 
'2024-12-31', 
1, 
0);

-- Sample rewards for club-specific program
INSERT INTO `loyalty_rewards` 
(`program_id`, `name`, `description`, `reward_type`, `points_cost`, `monetary_value`, 
`discount_percentage`, `valid_from`, `valid_until`, `is_active`)
VALUES
(2, 'Descuento 15% Club Demo', 
'15% de descuento exclusivo para miembros del Club Demo', 
'discount', 
150, 
75.00, 
15.00, 
'2024-01-01', 
'2024-12-31', 
1),

(2, 'Pase Mensual 2x1', 
'Compra un pase mensual y lleva otro gratis', 
'voucher', 
2000, 
1500.00, 
NULL, 
'2024-01-01', 
'2024-12-31', 
1);

-- Sample loyalty memberships (assuming users with IDs 4-10 exist as players)
-- These would be player users from the sample data
INSERT INTO `loyalty_memberships` 
(`program_id`, `user_id`, `tier_id`, `current_points`, `lifetime_points`, `points_redeemed`,
`membership_number`, `joined_at`, `tier_achieved_at`, `status`, `is_active`)
VALUES
(1, 4, 2, 1250, 2500, 1250, 'LP-2024-0001', '2024-01-15 10:00:00', '2024-03-20 14:30:00', 'active', 1),
(1, 5, 1, 5800, 8000, 2200, 'LP-2024-0002', '2024-01-20 11:30:00', '2024-05-15 16:00:00', 'active', 1),
(1, 6, 3, 450, 450, 0, 'LP-2024-0003', '2024-02-10 09:00:00', '2024-02-10 09:00:00', 'active', 1),
(1, 7, 3, 200, 700, 500, 'LP-2024-0004', '2024-03-05 13:00:00', '2024-03-05 13:00:00', 'active', 1);

-- Sample loyalty transactions (earn points)
INSERT INTO `loyalty_transactions` 
(`membership_id`, `transaction_type`, `points`, `description`, `reference_type`, `reference_id`,
`balance_before`, `balance_after`, `created_at`)
VALUES
-- Member 1 transactions
(1, 'bonus', 100, 'Bono de bienvenida', 'registration', 4, 0, 100, '2024-01-15 10:00:00'),
(1, 'earn', 150, 'Reservación completada', 'reservation', 1, 100, 250, '2024-01-20 18:00:00'),
(1, 'earn', 200, 'Pago de mensualidad', 'payment', 1, 250, 450, '2024-02-01 10:00:00'),
(1, 'redeem', -200, 'Canje: Descuento 10%', 'redemption', 1, 450, 250, '2024-02-15 14:00:00'),
(1, 'earn', 300, 'Referido nuevo miembro', 'referral', 5, 250, 550, '2024-03-01 11:00:00'),

-- Member 2 transactions
(2, 'bonus', 100, 'Bono de bienvenida', 'registration', 5, 0, 100, '2024-01-20 11:30:00'),
(2, 'earn', 500, 'Reservaciones múltiples', 'reservation', 2, 100, 600, '2024-02-10 19:00:00'),
(2, 'earn', 400, 'Inscripción torneo', 'tournament', 1, 600, 1000, '2024-03-15 10:00:00'),
(2, 'bonus', 100, 'Bono de cumpleaños', 'birthday', 5, 1000, 1100, '2024-04-10 00:00:00'),
(2, 'earn', 600, 'Compra en pro shop', 'purchase', 1, 1100, 1700, '2024-05-05 15:00:00'),

-- Member 3 transactions
(3, 'bonus', 100, 'Bono de bienvenida', 'registration', 6, 0, 100, '2024-02-10 09:00:00'),
(3, 'earn', 150, 'Primera reservación', 'reservation', 3, 100, 250, '2024-02-15 17:00:00'),
(3, 'earn', 100, 'Reservación fin de semana', 'reservation', 4, 250, 350, '2024-03-02 16:00:00'),

-- Member 4 transactions
(4, 'bonus', 100, 'Bono de bienvenida', 'registration', 7, 0, 100, '2024-03-05 13:00:00'),
(4, 'earn', 100, 'Reservación', 'reservation', 5, 100, 200, '2024-03-10 18:00:00'),
(4, 'redeem', -500, 'Canje: Hora gratis', 'redemption', 2, 700, 200, '2024-04-01 10:00:00');

-- Sample loyalty redemptions
INSERT INTO `loyalty_redemptions` 
(`membership_id`, `reward_id`, `points_spent`, `redemption_code`, `status`, 
`approved_at`, `redeemed_at`, `expires_at`)
VALUES
(1, 1, 200, 'RED-2024-0001', 'redeemed', 
'2024-02-15 14:05:00', '2024-02-20 10:30:00', '2024-03-15 23:59:59'),

(4, 2, 500, 'RED-2024-0002', 'redeemed', 
'2024-04-01 10:05:00', '2024-04-05 17:00:00', '2024-04-30 23:59:59'),

(2, 4, 300, 'RED-2024-0003', 'approved', 
'2024-06-01 09:00:00', NULL, '2024-06-30 23:59:59');

-- ============================================
-- 5. UPDATE STATISTICS
-- ============================================

-- Update affiliated clubs count in sports organizations
UPDATE `sports_organizations` so
SET affiliated_clubs_count = (
    SELECT COUNT(*) FROM organization_clubs oc 
    WHERE oc.organization_id = so.id AND oc.status = 'active'
);

-- Update last activity for loyalty memberships
UPDATE `loyalty_memberships` lm
SET last_activity_at = (
    SELECT MAX(created_at) FROM loyalty_transactions lt 
    WHERE lt.membership_id = lm.id
);

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- SAMPLE DATA LOAD COMPLETE
-- ============================================
-- Summary:
--   - 4 Real Estate Developments
--   - 3 Development-Club relationships
--   - 5 Sports Organizations
--   - 7 Organization-Club memberships
--   - 5 Sponsors
--   - 7 Sponsor-Club relationships
--   - 10 Sponsor payments
--   - 2 Loyalty Programs (1 global, 1 club-specific)
--   - 7 Loyalty Tiers
--   - 7 Loyalty Rewards
--   - 4 Loyalty Memberships
--   - 16 Loyalty Transactions
--   - 3 Loyalty Redemptions
-- ============================================
