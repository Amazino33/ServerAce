<?php

/**
 * IN-HOUSE DEVELOPER ASSIGNMENT FEATURE - EXAMPLES & EXTENSIONS
 * 
 * This file provides examples of how to use and extend the in-house
 * developer assignment feature.
 */

// ==========================================
// 1. QUERYING IN-HOUSE ASSIGNMENTS
// ==========================================

use App\Models\Gig;

// Get all pending in-house requests
$pendingRequests = Gig::where('assigned_to_inhouse', true)
    ->whereNull('inhouse_developer_id')
    ->with(['client', 'category'])
    ->latest()
    ->get();

// Get all assigned in-house gigs
$assignedGigs = Gig::where('assigned_to_inhouse', true)
    ->whereNotNull('inhouse_developer_id')
    ->with(['client', 'inHouseDeveloper', 'category'])
    ->get();

// Get gigs for a specific developer
$developerGigs = Gig::where('inhouse_developer_id', auth()->id())
    ->where('assigned_to_inhouse', true)
    ->with(['client'])
    ->get();

// Get recent in-house assignments (last 7 days)
$recentAssignments = Gig::where('assigned_to_inhouse', true)
    ->whereNotNull('inhouse_assigned_at')
    ->where('inhouse_assigned_at', '>=', now()->subDays(7))
    ->latest('inhouse_assigned_at')
    ->get();

// ==========================================
// 2. WORKING WITH GIG ASSIGNMENTS
// ==========================================

$gig = Gig::find(1);

// Check if assigned to in-house
if ($gig->isAssignedToInHouse()) {
    echo "This gig is assigned in-house";
}

// Get the assigned developer
$developer = $gig->inHouseDeveloper;
echo $developer->name; // "John Developer"

// Get assignment timestamp
$assignedAt = $gig->inhouse_assigned_at;

// Get client notes
$notes = $gig->inhouse_assignment_notes;

// ==========================================
// 3. CREATING/UPDATING ASSIGNMENTS (Controller Example)
// ==========================================

namespace App\Http\Controllers;

use App\Models\Gig;
use App\Models\User;

class InHouseAssignmentController extends Controller
{
    /**
     * Request in-house developer assignment (Client)
     */
    public function requestInHouse(Request $request, Gig $gig)
    {
        // Authorize: only gig owner
        $this->authorize('owner', $gig);

        // Validate
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        // Assign to in-house (without developer - admin will assign)
        $gig->assignToInHouseDeveloper(null, $validated['notes']);

        // Reject all pending applications
        $gig->applications()
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        return response()->json([
            'message' => 'Request submitted successfully',
            'gig_id' => $gig->id,
        ]);
    }

    /**
     * Assign developer to gig (Admin)
     */
    public function assignDeveloper(Request $request, Gig $gig)
    {
        // Authorize: admin only
        $this->authorize('admin');

        // Validate
        $validated = $request->validate([
            'developer_id' => 'required|exists:users,id',
        ]);

        // Assign developer
        $gig->assignToInHouseDeveloper(
            $validated['developer_id']
        );

        return response()->json([
            'message' => 'Developer assigned successfully',
        ]);
    }

    /**
     * Remove assignment
     */
    public function removeAssignment(Gig $gig)
    {
        $this->authorize('admin');

        $gig->removeInHouseAssignment();

        return response()->json([
            'message' => 'Assignment removed',
        ]);
    }
}

// ==========================================
// 4. SENDING NOTIFICATIONS
// ==========================================

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InHouseAssignmentRequested extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Gig $gig)
    {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("New In-House Assignment Request: {$this->gig->title}")
            ->greeting("Hello Admin,")
            ->line("A new in-house developer request has been submitted.")
            ->line("Gig: {$this->gig->title}")
            ->line("Client: {$this->gig->client->name}")
            ->action('Review Request', route('admin.inhouse-assignments'))
            ->line('Thank you for using our platform!');
    }

    public function toArray($notifiable)
    {
        return [
            'gig_id' => $this->gig->id,
            'client_name' => $this->gig->client->name,
            'gig_title' => $this->gig->title,
            'type' => 'inhouse_request',
        ];
    }
}

class InHouseDeveloperAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Gig $gig, public User $developer)
    {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("New Assignment: {$this->gig->title}")
            ->greeting("Hello {$this->developer->name},")
            ->line("You have been assigned a new gig!")
            ->line("Gig: {$this->gig->title}")
            ->line("Client: {$this->gig->client->name}")
            ->line("Budget: {$this->gig->budget_display}")
            ->action('View Gig', route('gigs.show', $this->gig))
            ->line('Thank you!');
    }

    public function toArray($notifiable)
    {
        return [
            'gig_id' => $this->gig->id,
            'developer_id' => $this->developer->id,
            'type' => 'inhouse_assigned',
        ];
    }
}

// Usage in Livewire component:
// $gig->client->notify(new InHouseAssignmentRequested($gig));
// $developer->notify(new InHouseDeveloperAssigned($gig, $developer));

// ==========================================
// 5. EXTENDING WITH ADDITIONAL FIELDS
// ==========================================

/**
 * To add more tracking, create a new migration:
 * 
 * Schema::table('gigs', function (Blueprint $table) {
 *     $table->timestamp('inhouse_started_at')->nullable()->after('inhouse_assigned_at');
 *     $table->timestamp('inhouse_completed_at')->nullable();
 *     $table->decimal('inhouse_cost', 10, 2)->nullable();
 *     $table->enum('inhouse_status', ['assigned', 'in_progress', 'completed', 'on_hold'])->default('assigned');
 *     $table->text('inhouse_completion_notes')->nullable();
 * });
 */

// ==========================================
// 6. REPORTING & ANALYTICS
// ==========================================

namespace App\Reports;

use App\Models\Gig;
use Carbon\Carbon;

class InHouseAssignmentReport
{
    /**
     * Get assignment statistics for a date range
     */
    public static function getStats(Carbon $from, Carbon $to)
    {
        return [
            'total_requests' => Gig::where('assigned_to_inhouse', true)
                ->whereBetween('created_at', [$from, $to])
                ->count(),
            'total_assigned' => Gig::where('assigned_to_inhouse', true)
                ->whereNotNull('inhouse_developer_id')
                ->whereBetween('inhouse_assigned_at', [$from, $to])
                ->count(),
            'pending_requests' => Gig::where('assigned_to_inhouse', true)
                ->whereNull('inhouse_developer_id')
                ->whereBetween('created_at', [$from, $to])
                ->count(),
            'average_assignment_time' => Gig::where('assigned_to_inhouse', true)
                ->whereNotNull('inhouse_assigned_at')
                ->whereBetween('created_at', [$from, $to])
                ->get()
                ->average(function($gig) {
                    return $gig->inhouse_assigned_at->diffInHours($gig->created_at);
                }),
        ];
    }

    /**
     * Get assignments by developer
     */
    public static function getByDeveloper()
    {
        return Gig::where('assigned_to_inhouse', true)
            ->whereNotNull('inhouse_developer_id')
            ->select('inhouse_developer_id')
            ->selectRaw('COUNT(*) as total_assignments')
            ->groupBy('inhouse_developer_id')
            ->with('inHouseDeveloper')
            ->get();
    }
}

// Usage:
// $stats = InHouseAssignmentReport::getStats(now()->subMonth(), now());
// $byDeveloper = InHouseAssignmentReport::getByDeveloper();

// ==========================================
// 7. BLADE VIEW HELPERS
// ==========================================

/**
 * Add to your blade files to easily show in-house status:
 * 
 * @forelse($gigs as $gig)
 *     <tr>
 *         <td>{{ $gig->title }}</td>
 *         <td>
 *             @if($gig->isAssignedToInHouse())
 *                 <span class="badge badge-purple">
 *                     In-House: {{ $gig->inHouseDeveloper->name ?? 'Pending' }}
 *                 </span>
 *             @endif
 *         </td>
 *     </tr>
 * @empty
 *     <tr><td colspan="2">No gigs</td></tr>
 * @endforelse
 */
