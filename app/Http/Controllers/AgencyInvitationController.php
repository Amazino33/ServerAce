<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Http\Requests\StoreAgencyInvitationRequest;
use Illuminate\Http\Request;
use App\Actions\Agency\AcceptAgencyInvitationAction;
use App\Actions\Agency\InviteTeamMemberAction;
use Exception;

class AgencyInvitationController extends Controller
{
    public function store(StoreAgencyInvitationRequest $request, Agency $agency, InviteTeamMemberAction $action)
    {
        try {
            // The Action class handles the token generation and database insertion
            $action->execute($agency, $request->validated('email'));

            return back()->with('success', 'Invitation sent to ' . $request->validated('email'));

        } catch (Exception $e) {
            // If they are already in the agency, or already invited, the Action throws an exception
            return back()->withErrors(['email' => $e->getMessage()])->withInput();
        }
    }

    public function accept(Request $request, string $token, AcceptAgencyInvitationAction $action)
    {
        try {
            // Execute the secure acceptance logic
            $agency = $action->execute($request->user(), $token);

            // Redirect them straight into their new workspace!
            return redirect()->route('agencies.workspace', $agency->slug)
                             ->with('success', "Welcome to the team! You have successfully joined {$agency->name}.");

        } catch (Exception $e) {
            // If anything fails (wrong email, invalid token), send them to the dashboard with an error
            return redirect()->route('dashboard')
                             ->with('error', $e->getMessage());
        }
    }
}
