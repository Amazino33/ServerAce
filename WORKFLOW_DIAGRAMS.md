# In-House Developer Assignment - Visual Workflow

## System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ServerAce Gig Platform                              │
└─────────────────────────────────────────────────────────────────────────────┘
                                       │
                ┌──────────────────────┼──────────────────────┐
                │                      │                      │
        ┌───────▼────────┐     ┌───────▼────────┐     ┌───────▼────────┐
        │    Freelancer   │     │    Client      │     │   Admin        │
        │   Dashboard     │     │   Dashboard    │     │   Dashboard    │
        └────────────────┘     └───────┬────────┘     └───────┬────────┘
                                       │                      │
                        ┌──────────────┴──────────────┬───────┴─────────┐
                        │                             │                 │
                  [1] Browse Gigs             [2] Request          [3] Manage
                        │                   In-House Dev         Assignments
                        │                             │                 │
                        └─────────────────────────────┼─────────────────┘
                                                      │
                                    ┌─────────────────▼─────────────────┐
                                    │   Gigs Table (Database)           │
                                    │                                   │
                                    │  • assigned_to_inhouse (BOOLEAN)  │
                                    │  • inhouse_developer_id (FK)      │
                                    │  • inhouse_assigned_at (TIMESTAMP)│
                                    │  • inhouse_assignment_notes (TEXT)│
                                    └───────────────────────────────────┘
```

---

## Client Workflow - Detailed Flow

```
                          CLIENT DASHBOARD
                                 │
                  ┌───────────────┴───────────────┐
                  │                               │
            [Overview Tab]              [Applications Tab]
                  │                               │
                  │                     ┌─────────▼──────────┐
                  │                     │                    │
                  │              [Click on Application]       │
                  │                     │                    │
                  │           ┌─────────▼──────────────┐    │
                  │           │ Application Modal      │    │
                  │           │                        │    │
                  │           │ • Freelancer Info      │    │
                  │           │ • Proposed Price       │    │
                  │           │ • Cover Letter         │    │
                  │           │                        │    │
                  │           │ [Accept] [Reject]      │    │
                  │           │ [Assign In-House] ◄────┼────┘
                  │           └─────────┬──────────────┘
                  │                     │
                  │          ┌──────────▼────────────┐
                  │          │ In-House Modal Opens  │
                  │          │                       │
                  │          │ • Explanation Text    │
                  │          │ • Notes Textarea      │
                  │          │                       │
                  │          │ [Submit Request]      │
                  │          │ [Cancel]              │
                  │          └──────────┬────────────┘
                  │                     │
                  │          ┌──────────▼──────────────────┐
                  │          │ REQUEST SUBMITTED           │
                  │          │                             │
                  │          │ • Gig marked in-house       │
                  │          │ • Freelancer apps rejected  │
                  │          │ • Notes saved               │
                  │          │ • Success toast shown       │
                  │          │ • Awaiting admin assignment │
                  │          └─────────────────────────────┘
```

---

## Admin Workflow - Detailed Flow

```
                        ADMIN DASHBOARD
                               │
              ┌────────────────┴────────────────┐
              │                                 │
        [Other Features]              [New: In-House Assignments]
              │                                 │
              │                    ┌────────────▼────────────┐
              │                    │ STATS CARDS             │
              │                    │                         │
              │                    │ Total Requests: 15      │
              │                    │ Pending: 8              │
              │                    │ Assigned: 7             │
              │                    └────────────┬────────────┘
              │                                 │
              │                    ┌────────────▼────────────┐
              │                    │ SEARCH & FILTER         │
              │                    │                         │
              │                    │ Search [__________]     │
              │                    │ Filter [Pending ▼]      │
              │                    └────────────┬────────────┘
              │                                 │
              │                    ┌────────────▼────────────┐
              │                    │ REQUESTS TABLE          │
              │                    │                         │
              │                    │ ┌─────────────────────┐ │
              │                    │ │ Title      Developer│ │
              │                    │ │ Client     Status   │ │
              │                    │ │ Budget     Actions  │ │
              │                    │ │                     │ │
              │                    │ │ Gig Title  Unassign │ │
              │                    │ │ [Assign]   [Remove] │ │
              │                    │ └──────┬──────────────┘ │
              │                    └────────┼────────────────┘
              │                             │
              │              ┌──────────────▼──────────────┐
              │              │ ASSIGNMENT MODAL OPENS      │
              │              │                             │
              │              │ Gig Details:                │
              │              │ • Title                     │
              │              │ • Client                    │
              │              │ • Budget                    │
              │              │ • Client Notes (if any)     │
              │              │                             │
              │              │ Developer Selection:        │
              │              │ ◯ Dev 1 (email)            │
              │              │ ◯ Dev 2 (email)            │
              │              │ ◯ Dev 3 (email)            │
              │              │                             │
              │              │ [Confirm] [Cancel]          │
              │              └──────────┬──────────────────┘
              │                         │
              │            ┌────────────▼───────────────┐
              │            │ ASSIGNMENT CONFIRMED       │
              │            │                            │
              │            │ • Developer assigned       │
              │            │ • Timestamp recorded       │
              │            │ • Status: Assigned         │
              │            │ • Success toast shown      │
              │            │ • Table updated            │
              │            └────────────────────────────┘
```

---

## Database State Changes

### Initial State (After Client Requests In-House)

```
Gigs Table:
┌──────────┬────────────────────────┬──────────────────────┐
│ gig_id   │ assigned_to_inhouse    │ inhouse_developer_id │
├──────────┼────────────────────────┼──────────────────────┤
│ 1        │ FALSE                  │ NULL                 │
│ 2        │ TRUE  ◄ Updated        │ NULL                 │
│ 3        │ FALSE                  │ NULL                 │
└──────────┴────────────────────────┴──────────────────────┘

inhouse_assigned_at: 2025-11-15 10:30:00
inhouse_assignment_notes: "Need ASAP, high priority"
```

### After Admin Assigns Developer

```
Gigs Table:
┌──────────┬────────────────────────┬──────────────────────┐
│ gig_id   │ assigned_to_inhouse    │ inhouse_developer_id │
├──────────┼────────────────────────┼──────────────────────┤
│ 1        │ FALSE                  │ NULL                 │
│ 2        │ TRUE                   │ 5  ◄ Updated         │
│ 3        │ FALSE                  │ NULL                 │
└──────────┴────────────────────────┴──────────────────────┘

inhouse_assigned_at: 2025-11-15 10:45:22 ◄ Updated
inhouse_developer_id: 5 (John Developer)
```

---

## Data Flow Diagram

```
┌─────────────────────┐
│ Client Submits      │
│ Request             │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────────────────────┐
│ Livewire Action:                    │
│ assignToInHouseDeveloper()          │
└──────────┬──────────────────────────┘
           │
           ├──► Update Gig (assigned_to_inhouse = true)
           │
           ├──► Reject Pending Applications
           │
           ├──► Record Notes
           │
           └──► Toast Notification
                │
                ▼
         ┌──────────────┐
         │ Admin View   │
         └──────┬───────┘
                │
                ▼
         ┌────────────────────┐
         │ See Request in     │
         │ Dashboard          │
         └──────┬─────────────┘
                │
                ▼
         ┌──────────────────────────┐
         │ Admin Assigns Developer  │
         │ Modal Form Submission    │
         └──────┬───────────────────┘
                │
                ├──► Update inhouse_developer_id
                │
                ├──► Update inhouse_assigned_at
                │
                └──► Toast Notification
                     │
                     ▼
              ┌────────────────┐
              │ Assignment     │
              │ Complete ✓     │
              └────────────────┘
```

---

## User Roles & Permissions

```
┌─────────────────────────────────────────────────────────────────┐
│                    Permission Matrix                             │
├──────────────────┬────────────────┬────────────────┬─────────────┤
│ Action           │ Client         │ Freelancer     │ Admin       │
├──────────────────┼────────────────┼────────────────┼─────────────┤
│ View own gigs    │ ✓              │               │ ✓ (all)     │
│ Request in-house │ ✓ (own gigs)   │               │             │
│ View requests    │ ✓ (own)        │               │ ✓ (all)     │
│ Assign developer │                │               │ ✓           │
│ Remove assign    │                │               │ ✓           │
│ Cancel request   │ ✓ (own)        │               │ ✓           │
│ View assigned gig│               │               │ ✓           │
│ Work on in-house │               │               │ ✓ assigned  │
└──────────────────┴────────────────┴────────────────┴─────────────┘
```

---

## Integration Points

```
In-House Assignment Feature:

┌─────────────────────────────────┐
│ Client Dashboard Component      │
│ - View applications             │
│ - Submit in-house request       │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│ Gig Model                       │
│ - Relationships                 │
│ - Methods                       │
│ - Validation                    │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│ Database                        │
│ - Gigs table                    │
│ - 4 new columns                 │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│ Admin Dashboard Component       │
│ - View requests                 │
│ - Assign developers             │
│ - Manage assignments            │
└─────────────────────────────────┘
```

---

## Status Progression

```
Gig Status Throughout In-House Assignment Process:

Initial State: OPEN / DRAFT
       │
       ▼
[Client applies for gig]
       │
       ▼
OPEN (with pending applications)
       │
       ▼
[Client requests in-house instead]
       │
       ▼
OPEN (assigned_to_inhouse = true, pending freelancer apps rejected)
       │
       ▼
[Admin assigns developer]
       │
       ▼
IN_PROGRESS (assigned_to_inhouse = true, inhouse_developer_id = X)
       │
       ▼
[Developer completes work]
       │
       ▼
COMPLETED (assigned_to_inhouse = true, inhouse_developer_id = X)
```

---

## Error Handling Flow

```
┌─────────────────────┐
│ User Action         │
└──────────┬──────────┘
           │
           ▼
    ┌─────────────┐
    │ Validation  │
    └──────┬──────┘
           │
    ┌──────┴────────┐
    │               │
    ▼               ▼
[FAIL]          [PASS]
    │               │
    ▼               ▼
Error Toast    Authorization
    │               │
    │          ┌────┴────┐
    │          │          │
    │      [FAIL]     [PASS]
    │          │          │
    │          ▼          ▼
    │       Error      Database
    │       Toast      Update
    │          │          │
    │          │          ▼
    │          │      Success
    │          │      Toast
    │          │          │
    └──────────┴──────────┘
           │
           ▼
    [End of Action]
```

---

## Next Steps / Extension Points

```
Current Feature
       │
       ├─► Add Email Notifications
       │   ├─► Client notification
       │   ├─► Admin notification
       │   └─► Developer notification
       │
       ├─► Add Developer Profiles
       │   ├─► Specialties
       │   ├─► Availability
       │   ├─► Workload
       │   └─► Ratings
       │
       ├─► Add Tracking
       │   ├─► Started timestamp
       │   ├─► Completed timestamp
       │   ├─► Status updates
       │   └─► Progress notes
       │
       ├─► Add Reporting
       │   ├─► Assignment analytics
       │   ├─► Developer performance
       │   ├─► Client satisfaction
       │   └─► SLA tracking
       │
       └─► Add Feedback
           ├─► Client ratings
           ├─► Developer ratings
           └─► Comments/reviews
```

---

**Last Updated**: November 15, 2025
**Feature Version**: 1.0
