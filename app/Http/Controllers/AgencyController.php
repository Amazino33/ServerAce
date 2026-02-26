<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\Agency\CreateAgencyAction;
use App\Http\Requests\StoreAgencyRequest;
use Exception;

class AgencyController extends Controller
{
    // Depency injection: Laravel automatically injects Action class here.
    public function store(StoreAgencyRequest $request, CreateAgencyAction $createAgencyAction)
    {
        // 1. Try/Catch block for resilience
        try {
            $agency = $createAgencyAction->execute($request->user(), $request->validated());

        // 2. Success Response
            return redirect()->route('dashboard')
                             ->with('success', "Agency '{$agency->name}' created successfully!");
        } catch (Exception $e) {
        // 3. Failure Response.
            return back()->withInput()
                         ->withErrors(['agency_limit' => $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('agencies.create');
    }
}
