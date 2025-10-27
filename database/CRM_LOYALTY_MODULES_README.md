# CRM and Loyalty System Modules - Database Documentation

## Overview

This document describes the database structure and migration for the four new SuperAdmin modules:
1. **CRM Desarrollos** (Real Estate Developments)
2. **CRM Deportivas** (Sports Organizations)
3. **Patrocinadores** (Sponsors)
4. **Sistema de Lealtad** (Loyalty Program)

## Migration Files

### 1. `migration_crm_loyalty_modules.sql`
**Purpose:** Complete database migration for all four modules.

**Features:**
- ✅ Idempotent (safe to run multiple times)
- ✅ Backward compatible with existing data
- ✅ Production-ready with proper indexes
- ✅ Foreign key constraints for data integrity
- ✅ Automatic column existence checking
- ✅ Sample data initialization

**Tables Created:** 13 new tables
- `developments` and `development_clubs`
- `sports_organizations` and `organization_clubs`
- `sponsors`, `sponsor_clubs`, and `sponsor_payments`
- `loyalty_programs`, `loyalty_tiers`, `loyalty_memberships`, `loyalty_transactions`, `loyalty_rewards`, `loyalty_redemptions`

**Usage:**
```bash
mysql -u root -p clubespadel < migration_crm_loyalty_modules.sql
```

### 2. `sample_data_crm_loyalty.sql`
**Purpose:** Comprehensive sample data for testing and demonstrations.

**Includes:**
- 4 real estate developments with various statuses
- 5 sports organizations (federations, associations, leagues)
- 5 sponsors with different sponsorship levels
- 2 loyalty programs (global and club-specific)
- Sample transactions, redemptions, and relationships

**Usage:**
```bash
# After running the migration
mysql -u root -p clubespadel < sample_data_crm_loyalty.sql
```

---

## Module 1: CRM Desarrollos (Real Estate Developments)

### Purpose
Manage real estate developments and sports complexes that host or are affiliated with clubs.

### Tables

#### `developments`
Stores information about real estate developments and sports complexes.

**Key Fields:**
- `name`: Development name
- `developer_company`: Company managing the development
- `location_*`: Complete location information including coordinates
- `sports_facilities`: JSON array of available sports facilities
- `amenities`: JSON array of amenities
- `status`: planning, construction, operational, completed, cancelled
- `investment_amount`: Total investment value

**Indexes:**
- `idx_status`: Quick filtering by status
- `idx_location`: Location-based queries
- `idx_developer_company`: Group by developer

#### `development_clubs`
Links developments to clubs (many-to-many relationship).

**Key Fields:**
- `development_id`: Reference to development
- `club_id`: Reference to club
- `relationship_type`: owned, managed, affiliated, partner
- `contract_start_date` / `contract_end_date`: Contract period

**Use Cases:**
- Track which clubs operate within a development
- Identify development owners vs. operators
- Manage multi-club complexes

---

## Module 2: CRM Deportivas (Sports Organizations)

### Purpose
Manage sports federations, associations, leagues, and club networks.

### Tables

#### `sports_organizations`
Stores sports organizations and their information.

**Key Fields:**
- `name`: Organization name
- `acronym`: Short name/acronym
- `organization_type`: federation, association, league, club_network, other
- `sport_types`: JSON array of sports covered
- `member_count`: Number of individual members
- `affiliated_clubs_count`: Number of affiliated clubs
- `status`: active, inactive, pending, suspended

**Indexes:**
- `idx_organization_type`: Filter by type
- `idx_country`: Geographic filtering
- `idx_acronym`: Quick search by acronym

#### `organization_clubs`
Links organizations to clubs with membership details.

**Key Fields:**
- `organization_id`: Reference to organization
- `club_id`: Reference to club
- `membership_type`: full, associate, affiliate, honorary
- `membership_number`: Unique membership identifier
- `annual_fee`: Membership cost
- `status`: active, inactive, suspended, expired
- `benefits`: JSON array of membership benefits

**Use Cases:**
- Track federation memberships
- Manage league participations
- Calculate membership fees and benefits
- Generate membership certificates

---

## Module 3: Patrocinadores (Sponsors)

### Purpose
Manage commercial sponsors and partnerships with clubs.

### Tables

#### `sponsors`
Main sponsor information.

**Key Fields:**
- `company_name`: Sponsor company name
- `industry`: Business industry
- `sponsorship_level`: platinum, gold, silver, bronze, basic
- `total_investment`: Total sponsorship value
- `contract_start_date` / `contract_end_date`: Contract period
- `benefits`: JSON array of benefits provided to sponsor
- `status`: active, inactive, pending, expired, cancelled

**Indexes:**
- `idx_sponsorship_level`: Group by tier
- `idx_industry`: Filter by industry
- `idx_status`: Active sponsors

#### `sponsor_clubs`
Links sponsors to specific clubs.

**Key Fields:**
- `sponsor_id`: Reference to sponsor
- `club_id`: Reference to club
- `sponsorship_amount`: Value for this specific club
- `sponsorship_type`: event, seasonal, annual, permanent, tournament
- `contract_number`: Contract reference
- `benefits_provided`: JSON array of club-specific benefits

**Use Cases:**
- Multi-club sponsorship management
- Track sponsorship values per club
- Manage different sponsorship types
- Calculate total sponsorship revenue

#### `sponsor_payments`
Track sponsor payment schedules and status.

**Key Fields:**
- `sponsor_club_id`: Reference to sponsor-club relationship
- `amount`: Payment amount
- `payment_date`: When payment was/is due
- `invoice_number`: Invoice reference
- `status`: pending, completed, overdue, cancelled, refunded

**Use Cases:**
- Payment tracking and reminders
- Invoice generation
- Overdue payment identification
- Financial reporting

---

## Module 4: Sistema de Lealtad (Loyalty Program)

### Purpose
Comprehensive loyalty and rewards program for users across the system.

### Architecture
The loyalty system supports:
- **Global programs**: Across all clubs (club_id = NULL)
- **Club-specific programs**: Individual club programs
- **Multiple tiers**: Bronze, Silver, Gold, etc.
- **Various transaction types**: Earn, redeem, expire, bonus, adjustment

### Tables

#### `loyalty_programs`
Main loyalty program configuration.

**Key Fields:**
- `club_id`: NULL for global, specific ID for club programs
- `name`: Program name
- `program_type`: points, tiers, cashback, hybrid, subscription
- `points_per_currency`: Points earned per $ spent
- `currency_per_point`: Value of each point when redeeming
- `welcome_bonus_points`: New member bonus
- `referral_bonus_points`: Refer-a-friend bonus
- `birthday_bonus_points`: Birthday bonus
- `point_expiry_days`: NULL = never expire
- `min_points_redemption`: Minimum points to redeem

**Use Cases:**
- Configure different earning rates
- Set expiration policies
- Manage multiple programs
- A/B testing different program types

#### `loyalty_tiers`
Tier/level structure within programs.

**Key Fields:**
- `program_id`: Reference to loyalty program
- `name`: Tier name (Bronze, Silver, Gold, etc.)
- `tier_level`: Numeric level (1 = highest)
- `min_points_required`: Points needed to achieve tier
- `points_multiplier`: Earning multiplier (e.g., 1.5x)
- `discount_percentage`: Automatic discount for tier members
- `benefits`: JSON array of tier benefits

**Use Cases:**
- Progressive rewards
- VIP member benefits
- Tiered discounts
- Status-based perks

#### `loyalty_memberships`
User enrollment in loyalty programs.

**Key Fields:**
- `program_id`: Reference to loyalty program
- `user_id`: Reference to user
- `tier_id`: Current tier
- `current_points`: Available points
- `lifetime_points`: Total points ever earned
- `points_redeemed`: Total points spent
- `points_expired`: Total points that expired
- `membership_number`: Unique identifier
- `status`: active, inactive, suspended, cancelled

**Use Cases:**
- Track member status
- Calculate tier eligibility
- Point balance management
- Member reporting

#### `loyalty_transactions`
Complete audit trail of all point movements.

**Key Fields:**
- `membership_id`: Reference to membership
- `transaction_type`: earn, redeem, expire, bonus, adjustment, refund
- `points`: Amount (positive or negative)
- `description`: Human-readable description
- `reference_type` / `reference_id`: Link to source (reservation, payment, etc.)
- `balance_before` / `balance_after`: Audit trail
- `expires_at`: When earned points expire

**Use Cases:**
- Complete transaction history
- Point expiration management
- Audit and compliance
- Dispute resolution
- Analytics and reporting

#### `loyalty_rewards`
Catalog of available rewards.

**Key Fields:**
- `program_id`: Reference to loyalty program
- `name`: Reward name
- `reward_type`: discount, free_service, merchandise, upgrade, voucher, experience
- `points_cost`: Points required to redeem
- `monetary_value`: Equivalent currency value
- `stock_quantity`: NULL for unlimited
- `max_per_user`: Redemption limit per user
- `min_tier_level`: Minimum tier required
- `valid_from` / `valid_until`: Availability window

**Use Cases:**
- Reward catalog management
- Inventory tracking
- Seasonal rewards
- Tier-exclusive rewards
- Limited-time offers

#### `loyalty_redemptions`
Track reward redemptions.

**Key Fields:**
- `membership_id`: Reference to membership
- `reward_id`: Reference to reward
- `points_spent`: Points deducted
- `redemption_code`: Unique validation code
- `status`: pending, approved, redeemed, cancelled, expired
- `approved_by` / `approved_at`: Approval workflow
- `redeemed_by` / `redeemed_at`: Actual redemption
- `expires_at`: Redemption expiration

**Use Cases:**
- Redemption workflow
- Code validation
- Inventory management
- Refund processing
- Usage tracking

---

## Data Relationships

### Foreign Key Constraints

All tables use proper foreign key constraints with cascading rules:
- **CASCADE**: Child records deleted when parent is deleted
- **SET NULL**: Reference set to NULL when parent is deleted
- **UPDATE CASCADE**: Updates propagate to child records

### JSON Fields

Several fields use JSON format for flexibility:
- `sports_facilities`, `amenities` in developments
- `sport_types` in sports organizations
- `benefits` in various tables
- `rules` in loyalty programs

**Example JSON structures:**

```json
// sports_facilities
["padel", "tennis", "swimming", "gym", "running_track"]

// amenities
["club_house", "restaurant", "spa", "parking", "security_24h"]

// benefits
[
  {"type": "discount", "value": 10, "description": "10% off all services"},
  {"type": "access", "value": "vip_lounge", "description": "VIP lounge access"}
]
```

---

## Indexes and Performance

### Strategy
All tables include indexes on:
- Primary keys (automatic)
- Foreign keys (explicit indexes)
- Status fields (for filtering)
- Date fields (for reporting)
- Frequently queried combinations

### Key Indexes
- `idx_status`: Quick status filtering
- `idx_location`: Geographic queries
- `idx_dates`: Date range queries
- `idx_compound`: Multi-column indexes for common queries

---

## Migration Safety Features

### Idempotent Design
The migration can be run multiple times safely:
- `CREATE TABLE IF NOT EXISTS`
- Dynamic column checking before ALTER
- Dynamic index checking before creation
- `INSERT IGNORE` for sample data

### Backward Compatibility
- No destructive changes to existing tables
- Additive-only modifications
- Foreign key constraints respect existing data
- Optional fields use sensible defaults

### Error Handling
- Prepared statements for dynamic SQL
- Information schema queries for existence checks
- Transaction control with FOREIGN_KEY_CHECKS

---

## Usage Examples

### Query Examples

```sql
-- Get all active developments with their clubs
SELECT d.name, d.status, c.name as club_name, dc.relationship_type
FROM developments d
JOIN development_clubs dc ON d.id = dc.development_id
JOIN clubs c ON dc.club_id = c.id
WHERE d.is_active = 1;

-- Calculate total sponsorship revenue by club
SELECT c.name, SUM(sc.sponsorship_amount) as total_sponsorship
FROM clubs c
JOIN sponsor_clubs sc ON c.id = sc.club_id
WHERE sc.status = 'active'
GROUP BY c.id, c.name
ORDER BY total_sponsorship DESC;

-- Get user loyalty point balance and tier
SELECT u.first_name, u.last_name, lm.current_points, 
       lt.name as tier_name, lp.name as program_name
FROM users u
JOIN loyalty_memberships lm ON u.id = lm.user_id
JOIN loyalty_programs lp ON lm.program_id = lp.id
LEFT JOIN loyalty_tiers lt ON lm.tier_id = lt.id
WHERE lm.status = 'active';

-- Get organization membership details for a club
SELECT so.name, so.acronym, oc.membership_type, 
       oc.annual_fee, oc.status
FROM sports_organizations so
JOIN organization_clubs oc ON so.id = oc.organization_id
WHERE oc.club_id = 1 AND oc.status = 'active';

-- Available rewards for a specific tier
SELECT lr.name, lr.points_cost, lr.reward_type, 
       lr.monetary_value, lr.description
FROM loyalty_rewards lr
WHERE lr.program_id = 1 
  AND (lr.min_tier_level IS NULL OR lr.min_tier_level >= 2)
  AND lr.is_active = 1
  AND CURRENT_DATE BETWEEN lr.valid_from AND lr.valid_until
ORDER BY lr.points_cost;
```

---

## Testing Checklist

After running the migration:

- [ ] Verify all 13 tables created successfully
- [ ] Check foreign key constraints exist
- [ ] Confirm indexes are in place
- [ ] Validate sample data loaded correctly
- [ ] Test INSERT operations on each table
- [ ] Test UPDATE operations
- [ ] Verify CASCADE deletes work correctly
- [ ] Check JSON field formatting
- [ ] Run SELECT queries with JOINs
- [ ] Test loyalty point calculations
- [ ] Verify tier upgrade logic

---

## Maintenance

### Regular Tasks

1. **Point Expiration** (Daily)
   ```sql
   -- Mark expired points
   INSERT INTO loyalty_transactions (membership_id, transaction_type, points, description, expires_at)
   SELECT membership_id, 'expire', -points, 'Points expired', expires_at
   FROM loyalty_transactions
   WHERE expires_at < CURRENT_DATE AND transaction_type = 'earn';
   ```

2. **Update Statistics** (Weekly)
   ```sql
   -- Update affiliated clubs count
   UPDATE sports_organizations so
   SET affiliated_clubs_count = (
       SELECT COUNT(*) FROM organization_clubs oc 
       WHERE oc.organization_id = so.id AND oc.status = 'active'
   );
   ```

3. **Payment Reminders** (Daily)
   ```sql
   -- Find overdue sponsor payments
   SELECT sp.*, sc.club_id, s.company_name
   FROM sponsor_payments sp
   JOIN sponsor_clubs sc ON sp.sponsor_club_id = sc.id
   JOIN sponsors s ON sc.sponsor_id = s.id
   WHERE sp.status = 'pending' 
     AND sp.payment_date < CURRENT_DATE;
   ```

---

## Troubleshooting

### Common Issues

**Issue:** Foreign key constraint fails during migration
**Solution:** Ensure the base schema is loaded first with all required tables

**Issue:** Column already exists error
**Solution:** The migration is idempotent; this is just informational

**Issue:** Sample data INSERT fails
**Solution:** Verify club IDs 1-4 exist, or adjust the sample data IDs

**Issue:** JSON field parsing errors
**Solution:** Ensure proper JSON syntax in inserts, use JSON_VALID() to test

---

## Future Enhancements

Potential additions to consider:
- Email notifications for loyalty point expiration
- Automated tier upgrades based on activity
- Sponsor performance dashboards
- Development project timelines
- Organization event calendar
- Bulk redemption processing
- Multi-language support for rewards

---

## Support

For issues or questions about this migration:
1. Check the main README.md
2. Review the TROUBLESHOOTING section
3. Inspect the SQL migration file comments
4. Check database logs for errors

**Note:** This migration is designed for MySQL 5.7+ and MariaDB 10.2+
