# In-House Developer Assignment Feature

## Overview
This feature allows clients to request that their gigs be handled by in-house developers instead of freelancers. Site admins can then review these requests and assign gigs to available in-house developers.

## Database Changes

### New Migration: `2025_11_15_000000_add_inhouse_developer_to_gigs_table.php`

Adds the following columns to the `gigs` table:
- `assigned_to_inhouse` (boolean, default: false) - Indicates if a gig is assigned to in-house
- `inhouse_developer_id` (nullable foreignId) - References the in-house developer user
- `inhouse_assigned_at` (nullable timestamp) - When the assignment was made
- `inhouse_assignment_notes` (nullable text) - Notes from the client about the gig

## Models

### Gig Model (`app/Models/Gig.php`)

#### New Relationship
```php
public function inHouseDeveloper()
{
    return $this->belongsTo(User::class, 'inhouse_developer_id');
}
```

#### New Methods
```php
// Assign gig to in-house developer
public function assignToInHouseDeveloper($developerId, $notes = null)

// Remove in-house developer assignment
public function removeInHouseAssignment()

// Check if gig is assigned to in-house
public function isAssignedToInHouse(): bool
```

## Livewire Components

### Client Dashboard (`app/Livewire/Client/Dashboard.php`)

#### New Method
```php
public function assignToInHouseDeveloper($gigId, $notes = '')
```
- Allows clients to request in-house developer assignment
- Automatically rejects all pending freelancer applications
- Sends toast notification to client

### Admin Manager (`app/Livewire/Admin/ManageInHouseAssignments.php`)

New component for managing in-house assignments:
- **View all requests** - See pending and assigned gigs
- **Search & Filter** - By gig title, client name, or assignment status
- **Assign developer** - Select an available developer for a gig
- **Remove assignment** - Unassign or cancel requests
- **Stats dashboard** - Show total, pending, and assigned requests

## Views

### Client Dashboard (`resources/views/livewire/client/dashboard.blade.php`)

**New Modal**: "Request In-House Developer"
- Appears in the application modal
- Allows clients to add special instructions/notes
- Shows what happens after submission

### Admin In-House Manager (`resources/views/livewire/admin/manage-inhouse-assignments.blade.php`)

**Features**:
- Stats cards showing request counts
- Search and filter functionality
- Table view with all requests
- Assignment modal with developer selection
- Action buttons (Assign/Remove/Cancel)

## Routes

### Admin Route
```
GET /admin/inhouse-assignments → ManageInHouseAssignments component
```

## Workflow

### Client Perspective
1. Client views an application for their gig
2. If they prefer in-house developer, click "Assign In-House" button
3. Fill in optional notes (special requirements, timeline, etc.)
4. Submit request
5. All pending freelancer applications are automatically rejected
6. Client receives confirmation notification

### Admin Perspective
1. Admin visits `/admin/inhouse-assignments`
2. Views all in-house developer requests
3. Filters by status (pending/assigned)
4. Searches by gig or client name
5. Clicks "Assign" on a pending request
6. Selects an available in-house developer
7. Confirms assignment
8. Developer is assigned and timestamps are recorded

## Future Enhancements

- [ ] Email notifications to developers when assigned
- [ ] Email notifications to clients when developer assigned
- [ ] Developer availability calendar
- [ ] Completion tracking for in-house assignments
- [ ] Rating/feedback system for in-house work
- [ ] Create a new UserRole for "IN_HOUSE_DEVELOPER"
- [ ] Advanced developer filtering (skills, availability, workload)
- [ ] Assignment history and audit logs

## Technical Notes

- The feature uses Livewire for real-time interactions
- No freelancer is automatically selected; clients choose to go in-house
- Existing freelancer applications are rejected when in-house assignment is requested
- In-house assignment is independent of gig status
- Admins manage the in-house developer list (currently uses User::ADMIN role)
