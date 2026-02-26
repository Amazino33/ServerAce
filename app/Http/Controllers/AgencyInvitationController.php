<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Http\Requests\StoreAgencyInvitationRequest;
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
}
