<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyWorkspaceController extends Controller
{
    public function show(Agency $agency)
    {
        // 🛡️ The Senior Security Move: Authorization Check
        // If the currently logged-in user is NOT in this agency's user list, kick them out.
        if (!$agency->users->contains(auth()->id())) {
            abort(403, 'You are not a member of this agency.');
        }

        // Load the users and their pivot data (role) so we can display the team list
        $agency->load('users');
        // Fetch pending invites to show on the dashboard
        $pendingInvitations = $agency->invitations()->latest()->get();

        return view('agencies.workspace', [
            'agency' => $agency,
            'teamMembers' => $agency->users,
            'pendingInvitations' => $pendingInvitations,
        ]);
    }
}
