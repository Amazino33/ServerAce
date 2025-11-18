<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show(User $user)
    {
        // Load everything we need in one query
        $user->load([
            'gigs' => fn($q) => $q->where('status', 'open')->limit(6),
            'completedGigs' => fn($q) => $q->where('status', 'completed'),
            'reviewsReceived',
            'reviewsGiven',
        ]);

        // Calculate stats
        $stats = [
            'total_earned' => $user->paymentsReceived()->where('status', 'released')->sum('freelancer_amount'),
            'total_spent'  => $user->paymentsMade()->where('status', 'released')->sum('amount'),
            'gigs_completed' => $user->completedGigs->count(),
            'rating_avg' => $user->reviewsReceived->avg('rating') ?? 0,
            'rating_count' => $user->reviewsReceived->count(),
        ];

        return view('profile.show', compact('user', 'stats'));
    }
}
