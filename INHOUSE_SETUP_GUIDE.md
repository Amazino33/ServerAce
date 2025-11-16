# In-House Developer Assignment - Quick Setup Guide

## Installation Steps

### 1. Run the Migration
```bash
php artisan migrate
```

This will add the following columns to your `gigs` table:
- `assigned_to_inhouse` - Boolean flag for in-house assignments
- `inhouse_developer_id` - Foreign key to assigned developer
- `inhouse_assigned_at` - Timestamp when assigned
- `inhouse_assignment_notes` - Client notes

### 2. Updated Files

The following files have been created/modified:

**Created:**
- `database/migrations/2025_11_15_000000_add_inhouse_developer_to_gigs_table.php`
- `app/Livewire/Admin/ManageInHouseAssignments.php`
- `resources/views/livewire/admin/manage-inhouse-assignments.blade.php`

**Modified:**
- `app/Models/Gig.php` - Added relationships and methods
- `app/Livewire/Client/Dashboard.php` - Added `assignToInHouseDeveloper()` method
- `resources/views/livewire/client/dashboard.blade.php` - Added in-house assignment modal
- `routes/web.php` - Added admin route

### 3. Test the Feature

**For Clients:**
1. Navigate to `/client/dashboard`
2. View an application on a gig
3. Click "Assign In-House" button
4. Add optional notes
5. Submit request

**For Admins:**
1. Navigate to `/admin/inhouse-assignments`
2. View all in-house requests with stats
3. Filter by status or search
4. Click "Assign" on any request
5. Select a developer and confirm

## API Methods

### Gig Model

```php
// Assign gig to developer
$gig->assignToInHouseDeveloper($developerId, $notes = null);

// Remove assignment
$gig->removeInHouseAssignment();

// Check if assigned
$gig->isAssignedToInHouse();

// Get assigned developer
$gig->inHouseDeveloper;
```

### Dashboard Component (Client)

```php
// Assign to in-house
$this->assignToInHouseDeveloper($gigId, $notes);
```

### ManageInHouseAssignments Component (Admin)

```php
// Assign developer
$this->assignDeveloper();

// Remove assignment
$this->removeAssignment($gigId);

// Cancel request
$this->cancelInHouseRequest($gigId);
```

## UI Components

### Client Modal
Located in client dashboard blade view:
- "Request In-House Developer" modal
- Appears when clicking "Assign In-House" button
- Allows entering special instructions

### Admin Dashboard
Located at `/admin/inhouse-assignments`:
- Stats cards for request metrics
- Search and filter controls
- Table with all requests
- Assignment modal for selecting developers

## Database Queries

```php
// Get all in-house requests (pending)
Gig::where('assigned_to_inhouse', true)
    ->whereNull('inhouse_developer_id')
    ->get();

// Get assigned gigs
Gig::where('assigned_to_inhouse', true)
    ->whereNotNull('inhouse_developer_id')
    ->with('inHouseDeveloper')
    ->get();

// Get gigs for a specific developer
Gig::where('inhouse_developer_id', $developerId)->get();
```

## Customization

### Change Available Developers
Edit `ManageInHouseAssignments.php` `getAvailableDevelopersProperty()`:
```php
public function getAvailableDevelopersProperty()
{
    // Currently returns Users with ADMIN role
    // Customize to your developer selection criteria
}
```

### Modify Developer Email Domain Check
```php
->orWhere(function($query) {
    // Adjust the company domain filter
    $query->where('email', 'like', '%@yourcompany.%');
})
```

## Security Notes

- Client route protected by `middleware('role:client')`
- Admin route protected by `middleware('role:admin')`
- Authorization checks on both create and update operations
- Only clients can create requests for their own gigs
- Only admins can assign developers

## Notifications (TODO)

Consider adding:
- Email to client when developer assigned
- Email to developer when new gig assigned
- In-app notification system
- SMS notifications for urgent gigs

## Next Steps

1. Customize developer selection criteria for your needs
2. Add email notifications
3. Create developer completion tracking
4. Add gig feedback/rating system
5. Create reports on in-house assignments
