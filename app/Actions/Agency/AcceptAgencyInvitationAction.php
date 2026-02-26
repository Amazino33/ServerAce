<?php

namespace App\Actions\Agency;

use App\Models\Agency;
use App\Models\AgencyInvitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class AcceptAgencyInvitationAction
{
    /**
     * @throws Exception
     */
    public function execute(User $user, string $token): Agency
    {
        // 1. Find the invitation by its unguessable token
        $invitation = AgencyInvitation::where('token', $token)->first();

        if (!$invitation) {
            throw new Exception('This invitation link is invalid or has expired.');
        }

        // 2. 🛡️ Senior Security: Prevent Token Hijacking
        if (strtolower($user->email) !== strtolower($invitation->email)) {
            throw new Exception('This invitation was sent to a different email address.');
        }

        // 3. 🛡️ Check our system limits again
        if (!$user->canJoinNewAgency()) {
            throw new Exception('You have reached the maximum number of agencies allowed.');
        }

        // 4. Database Transaction to ensure data integrity
        return DB::transaction(function () use ($user, $invitation) {
            $agency = $invitation->agency;

            // Attach the user to the agency pivot table using the role from the invite
            $user->agencies()->attach($agency->id, ['role' => $invitation->role]);

            // Burn the invitation so it can never be used again
            $invitation->delete();

            return $agency;
        });
    }
}