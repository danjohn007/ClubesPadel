# Deployment Instructions - ClubesPadel System Improvements

## Overview
This document outlines the deployment steps for the recent system improvements to ClubesPadel.

## Changes Implemented

### 1. Registration System Fixes
- **Fixed:** "Undefined array key 'subdomain'" error in Club.php
- **Added:** Automatic subdomain generation from club name
- **Added:** Terms and conditions acceptance checkbox (required)
- **Updated:** Phone field now required with 10-digit validation
- **Updated:** Enhanced validation in AuthController

### 2. SuperAdmin System Settings
- **New Model:** `SystemSettings.php` for managing global configurations
- **New View:** Settings page with comprehensive configuration options:
  - PayPal payment gateway configuration
  - Email/SMTP system settings
  - Currency and tax rate configuration
  - Public site branding (name, logo, description)
  - Terms and conditions content management
  - WhatsApp chatbot integration
  - Bank account information for deposits
  - Contact information and business hours
  - General system settings

### 3. SuperAdmin Reports Section
- **New View:** Reports page with detailed analytics:
  - Revenue report (last 12 months) with line chart
  - Club growth tracking with bar chart
  - Subscription status distribution with pie chart
  - Plans performance metrics with detailed tables

### 4. Enhanced SuperAdmin Dashboard
- **Added:** 2 new charts
  - Revenue Bar Chart (last 6 months)
  - Subscription Status Polar Area Chart
- **Improved:** Recent Clubs list moved to bottom for better UX
- **Enhanced:** Data visualization with Chart.js

### 5. CRUD Operations for SuperAdmin
- **Clubs Management:**
  - Create new clubs with full details
  - Edit existing club information
  - Modal-based forms for better UX
  
- **Subscription Plans:**
  - Edit plan details (pricing, limits, features)
  - Update plan availability
  - Manage plan features and descriptions
  
- **Club Payments:**
  - Register new payments manually
  - Track payment methods and status
  - Add payment notes and references

### 6. New Models
- `ClubPayment.php` - Manages club payment records
- `SubscriptionPlan.php` - Manages subscription plans
- `SystemSettings.php` - Manages global system settings

## Database Migration

### Step 1: Backup Current Database
```bash
mysqldump -u [username] -p clubespadel > clubespadel_backup_$(date +%Y%m%d_%H%M%S).sql
```

### Step 2: Run Migration Script
```bash
mysql -u [username] -p clubespadel < database/migration_system_improvements.sql
```

### Migration Contents:
The migration script includes:

1. **New Table: system_settings**
   - Stores global configuration values
   - Organized by setting groups (payment, email, financial, etc.)
   - Pre-populated with default values

2. **users Table Updates:**
   - Added `terms_accepted_at` column (datetime, nullable)
   - Added index on `phone` column

3. **clubs Table Updates:**
   - Modified `subdomain` column to be nullable
   - Auto-generates subdomain for existing clubs without one
   - Added index on `phone` column

4. **Performance Indexes:**
   - Added indexes for better query performance
   - Optimized foreign key relationships

### Step 3: Verify Migration
```sql
-- Check system_settings table exists and has data
SELECT COUNT(*) FROM system_settings;

-- Check clubs table structure
DESCRIBE clubs;

-- Check users table structure
DESCRIBE users;
```

## File Changes Summary

### New Files:
- `app/Models/SystemSettings.php`
- `app/Models/ClubPayment.php`
- `app/Models/SubscriptionPlan.php`
- `app/Views/superadmin/settings.php`
- `app/Views/superadmin/reports.php`
- `database/migration_system_improvements.sql`
- `DEPLOYMENT_INSTRUCTIONS.md`

### Modified Files:
- `app/Models/Club.php` - Added subdomain auto-generation
- `app/Controllers/AuthController.php` - Enhanced validation
- `app/Controllers/SuperadminController.php` - Added new methods
- `app/Views/auth/register.php` - Added terms checkbox, phone validation
- `app/Views/superadmin/dashboard.php` - Added charts, reorganized layout
- `app/Views/superadmin/clubs.php` - Added create/edit modals
- `app/Views/superadmin/plans.php` - Added edit modal
- `app/Views/superadmin/payments.php` - Added create modal

## Deployment Steps

### 1. Pre-Deployment Checklist
- [ ] Backup current database
- [ ] Backup current application files
- [ ] Review all changes in staging environment
- [ ] Test registration flow
- [ ] Test SuperAdmin functionalities

### 2. Deploy Code
```bash
# Pull latest changes
git pull origin main

# Or if deploying from branch
git checkout copilot/fix-club-registration-errors-2
git pull origin copilot/fix-club-registration-errors-2
```

### 3. Run Database Migration
```bash
mysql -u [username] -p clubespadel < database/migration_system_improvements.sql
```

### 4. Clear Cache (if applicable)
```bash
# Clear PHP opcache if enabled
# Method depends on your server configuration
```

### 5. Verify Deployment

#### Test Registration:
1. Go to `/auth/register`
2. Try registering as a Player
3. Try registering as a Club
4. Verify phone validation (must be 10 digits)
5. Verify terms checkbox is required
6. Verify subdomain is auto-generated for clubs

#### Test SuperAdmin:
1. Login as superadmin
2. Navigate to Dashboard - verify new charts are visible
3. Navigate to Settings - verify all configuration sections load
4. Navigate to Reports - verify charts and data display correctly
5. Navigate to Clubs - test creating a new club
6. Navigate to Clubs - test editing an existing club
7. Navigate to Plans - test editing a plan
8. Navigate to Payments - test registering a new payment

### 6. Post-Deployment Configuration

#### Configure System Settings:
1. Login as SuperAdmin
2. Go to Settings page
3. Configure the following sections:
   - PayPal credentials (if using PayPal)
   - SMTP settings for email delivery
   - Currency symbol and tax rate
   - Site name, logo, and description
   - Terms and conditions text
   - WhatsApp chatbot number (if using)
   - Bank account information
   - Contact phones and business hours

## Troubleshooting

### Issue: "Undefined array key subdomain"
**Solution:** Run the database migration to make subdomain nullable and add auto-generation logic.

### Issue: Phone validation not working
**Solution:** Ensure HTML5 validation is enabled in browser. Check JavaScript console for errors.

### Issue: Terms checkbox not appearing
**Solution:** Clear browser cache. Ensure register.php view is updated.

### Issue: Charts not displaying in Dashboard/Reports
**Solution:** 
- Check if Chart.js CDN is accessible
- Check browser console for JavaScript errors
- Verify database has data for the charts

### Issue: Settings page not loading
**Solution:**
- Run migration to create system_settings table
- Check PHP error logs for database connection issues
- Verify SystemSettings model is properly loaded

### Issue: Modal forms not opening
**Solution:**
- Verify Bootstrap 5 JavaScript is loaded
- Check for JavaScript console errors
- Clear browser cache

## Security Considerations

1. **Database Credentials:** Ensure database credentials in config are secure
2. **PayPal Settings:** Store PayPal credentials securely, use environment variables if possible
3. **SMTP Passwords:** Consider encrypting SMTP passwords in database
4. **Input Validation:** All forms include server-side validation
5. **SQL Injection:** All database queries use prepared statements
6. **XSS Protection:** All output is escaped with htmlspecialchars()

## Performance Notes

- Added indexes on frequently queried columns (phone, subdomain)
- Chart data queries are optimized with appropriate date ranges
- Settings are cached in session after first load (future enhancement)

## Support

For issues or questions:
1. Check application error logs
2. Review database migration status
3. Verify all required files are deployed
4. Test in staging environment before production

## Rollback Procedure

If issues occur:

```bash
# 1. Restore database backup
mysql -u [username] -p clubespadel < clubespadel_backup_[timestamp].sql

# 2. Revert code changes
git checkout [previous-commit-hash]

# 3. Clear cache
```

## Future Enhancements

Recommended improvements for future releases:
- Settings caching mechanism
- Bulk club import functionality
- Payment gateway integration (PayPal, Stripe)
- Advanced reporting with date range filters
- Email notifications for new registrations
- WhatsApp bot integration
- Mobile app API endpoints
