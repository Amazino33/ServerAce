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

        $user = auth()->user();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        foreach ($request->file('portfolio') as $image) {
            auth()->user()->addMedia($image)->toMediaCollection('portfolio');
        }   

    return back()->with('success', 'Portfolio images uploaded!');

        // Clean skills (remove empty)
        if (isset($validated['skills'])) {
            $validated['skills'] = array_filter($validated['skills']);
        }

        $user->update($validated);

        // Update profile completion (simple version)
        $completion = 30;
        if ($user->bio) $completion += 20;
        if ($user->location || $user->phone) $completion += 10;
        if ($user->avatar !== 'avatars/default.png') $completion += 15;
        if ($user->role === 'freelancer' && $user->title && $user->skills) $completion += 25;
        $user->update(['profile_completion' => min(100, $completion)]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function uploadPortfolio(Request $request)
    {
        $request->validate([
            'portfolio.*' => 'image|mimes:jpeg,png,webp|max:5120',
        ]);

        foreach ($request->file('portfolio') as $image) {
            auth()->user()->addMedia($image)->toMediaCollection('portfolio');
        }

        return back()->with('success', 'Portfolio images uploaded!');
    }

    public function deletePortfolio(\Spatie\MediaLibrary\MediaCollections\Models\Media $media)
    {
        if ($media->model_id !== auth()->id()) abort(403);

        $media->delete();

        return response()->json(['success' => true]);
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
