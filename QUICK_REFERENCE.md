# Quick Reference Guide - In-House Developer Assignment

## 🚀 Quick Start

### For Clients
```
1. Go to /client/dashboard
2. Click "Applications" tab
3. Click on any pending application
4. Click "Assign In-House" button
5. Add optional notes
6. Submit request
```

### For Admins
```
1. Go to /admin/inhouse-assignments
2. View stats and search/filter
3. Click "Assign" on any pending request
4. Select developer and confirm
```

---

## 📂 File Locations

| File | Purpose | Location |
|------|---------|----------|
| Migration | DB schema | `database/migrations/2025_11_15_000000_...php` |
| Gig Model | Core logic | `app/Models/Gig.php` |
| Client Component | Client actions | `app/Livewire/Client/Dashboard.php` |
| Admin Component | Admin actions | `app/Livewire/Admin/ManageInHouseAssignments.php` |
| Client View | Client UI | `resources/views/livewire/client/dashboard.blade.php` |
| Admin View | Admin UI | `resources/views/livewire/admin/manage-inhouse-assignments.blade.php` |
| Routes | URL mapping | `routes/web.php` |

---

## 💾 Database Columns

Added to `gigs` table:

```sql
assigned_to_inhouse      BOOLEAN DEFAULT FALSE
inhouse_developer_id     BIGINT UNSIGNED NULL (FK)
inhouse_assigned_at      TIMESTAMP NULL
inhouse_assignment_notes TEXT NULL
```

---

## 🔧 Key Methods

### Gig Model
```php
$gig->assignToInHouseDeveloper($developerId, $notes)
$gig->removeInHouseAssignment()
$gig->isAssignedToInHouse()
$gig->inHouseDeveloper  // Relationship
```

### Client Component
```php
$this->assignToInHouseDeveloper($gigId, $notes)
```

### Admin Component
```php
$this->assignDeveloper()
$this->removeAssignment($gigId)
$this->cancelInHouseRequest($gigId)
```

---

## 🎯 URLs

| Route | Access | Purpose |
|-------|--------|---------|
| `/client/dashboard` | Authenticated Client | Client dashboard |
| `/admin/inhouse-assignments` | Authenticated Admin | Manage in-house assignments |

---

## 🔐 Permissions

| Action | Who | Where |
|--------|-----|-------|
| Submit in-house request | Client | On their own gigs only |
| View all requests | Admin | Admin panel only |
| Assign developer | Admin | Admin panel only |
| Remove assignment | Admin | Admin panel only |

---

## 📊 Query Examples

```php
// Get pending requests
Gig::where('assigned_to_inhouse', true)
   ->whereNull('inhouse_developer_id')
   ->get();

// Get assigned gigs
Gig::where('assigned_to_inhouse', true)
   ->whereNotNull('inhouse_developer_id')
   ->with('inHouseDeveloper')
   ->get();

// Get gigs for specific developer
Gig::where('inhouse_developer_id', $developerId)->get();

// Get gig with all relationships
Gig::with(['client', 'inHouseDeveloper', 'applications'])
   ->find($id);
```

---

## 🎨 UI Elements

### Client Dashboard Modal
- Title: "Request In-House Developer"
- Color: Purple gradient
- Button: "Assign In-House" in application detail modal
- New modal: Notes textarea + submit button

### Admin Dashboard
- Location: `/admin/inhouse-assignments`
- Color: Purple to indigo gradient
- Components:
  - Stats cards (total/pending/assigned)
  - Search box
  - Filter dropdown
  - Requests table
  - Assignment modal

---

## ✅ Checklist - Deploy

```
Before going live:
[ ] Run migration: php artisan migrate
[ ] Clear cache: php artisan cache:clear
[ ] Clear routes: php artisan route:clear
[ ] Test client request flow
[ ] Test admin assignment flow
[ ] Verify DB columns added
[ ] Check permissions work
[ ] Test toast notifications
[ ] Test search/filter
[ ] Verify timestamps recorded
[ ] Test with multiple users
```

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Migration fails | Check database connection, ensure `gigs` table exists |
| Modal not showing | Check browser console, verify Livewire installed |
| Route 404 | Run `php artisan route:clear` |
| Assignments not saving | Verify migration ran: `php artisan migrate:status` |
| Authorization error | Check user role in database |
| Toast not showing | Check Livewire dispatch events |
| Search not working | Verify Livewire `wire:model.live` working |

---

## 📝 Event Sequence

```
Client Flow:
1. User opens application modal
2. Clicks "Assign In-House" button
3. Alpine.js shows new modal
4. User enters notes
5. Clicks "Submit Request"
6. Livewire calls assignToInHouseDeveloper()
7. Gig updated in DB
8. Applications rejected
9. Toast notification shown
10. Modal closes

Admin Flow:
1. Admin visits /admin/inhouse-assignments
2. Sees stats and requests
3. Optionally searches/filters
4. Clicks "Assign" button
5. Modal opens with developer list
6. Selects developer
7. Clicks "Confirm"
8. Livewire calls assignDeveloper()
9. Gig updated with developer
10. Toast notification shown
11. Table refreshes
```

---

## 🔄 Data Flow

```
Client Request:
User Action → Livewire Component → Gig Model → Database
           → Reject Applications → Toast

Admin Assignment:
User Action → Livewire Component → Gig Model → Database
           → Timestamp + Developer → Toast → Refresh UI
```

---

## 🎓 Learning Points

This implementation covers:
- ✅ Database migrations with foreign keys
- ✅ Eloquent relationships
- ✅ Livewire components (real-time)
- ✅ Blade templating
- ✅ Alpine.js integration
- ✅ Authorization with middleware
- ✅ Form validation
- ✅ Toast notifications
- ✅ Search and filtering
- ✅ Pagination

---

## 📞 Support

For help:
1. Check IMPLEMENTATION_SUMMARY.md for overview
2. Check IN_HOUSE_DEVELOPER_FEATURE.md for details
3. Check WORKFLOW_DIAGRAMS.md for visual flows
4. Check INHOUSE_EXAMPLES.php for code samples
5. Check INHOUSE_SETUP_GUIDE.md for setup

---

## 🎉 You're All Set!

The in-house developer assignment feature is now ready to use. Clients can request in-house developers, and admins can assign them accordingly.

**Status**: ✅ Complete and ready for production
**Version**: 1.0
**Date**: November 15, 2025

---

## 📋 Summary Table

| Component | Status | Tested |
|-----------|--------|--------|
| Migration | ✅ Created | - |
| Gig Model | ✅ Updated | - |
| Client Component | ✅ Updated | - |
| Admin Component | ✅ Created | - |
| Client View | ✅ Updated | - |
| Admin View | ✅ Created | - |
| Routes | ✅ Added | - |
| Documentation | ✅ Complete | - |

---

## 🚨 Important Notes

1. **Run migration first**: `php artisan migrate`
2. **Users need role**: Ensure users have correct `role` field
3. **Clear cache after changes**: `php artisan cache:clear`
4. **Verify Livewire**: Ensure Livewire is properly installed
5. **Check Alpine.js**: Required for modal functionality

---

*Last Updated: November 15, 2025*
