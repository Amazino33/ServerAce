<?php

namespace App\Actions\Agency;

use App\Models\Agency;
use App\Models\AgencyInvitation;
use Illuminate\Support\Str;
use Exception;

class InviteTeamMemberAction
{
    /**
     * @throws Exception
     */
    public function execute(Agency $agency, string $email, string $role = 'member'): AgencyInvitation
    {
        // 1. Check if the user is ALREADY in the agency
        $isAlreadyMember = $agency->users()->where('email', $email)->exists();
        if ($isAlreadyMember) {
            throw new Exception('This user is already a member of the agency.');
        }

        // 2. Check if an invitation is ALREADY pending
        $hasPendingInvite = $agency->invitations()->where('email', $email)->exists();
        if ($hasPendingInvite) {
            throw new Exception('An invitation has already been sent to this email.');
        }

        // 3. Create the invitation with a secure random token
        $invitation = $agency->invitations()->create([
            'email' => $email,
            'role' => $role,
            'token' => Str::random(40), // Generates a secure 40-character string
        ]);

        // Note: We will trigger an email notification here later!

        return $invitation;
    }
}
