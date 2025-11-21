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
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'title' => 'nullable|string|max:255',
            'hourly_rate' => 'nullable|numeric|min:0',
            'experience_level' => 'nullable|in:beginner,intermediate,expert',
            'skills' => 'nullable|array',
            'skills.*' => 'nullable|string|max:100',
            'portfolio_description' => 'nullable|string|max:2000',
            'company_name' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'profile_public' => 'sometimes|boolean',
            'available_for_work' => 'sometimes|boolean',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (isset($validated['skills'])) {
            $validated['skills'] = array_filter($validated['skills']);
        }

        $validated['profile_public'] = $request->has('profile_public');
        $validated['available_for_work'] = $request->has('available_for_work');

        $user->update($validated);

        $completion = 30;
        $completion += $user->bio ? 20 : 0;
        $completion += $user->location || $user->phone ? 10 : 0;
        $completion += $user->avatar && $user->avatar !== 'avatars/default.png' ? 15 : 0;
        $completion += $user->role === 'freelancer' && $user->title && count($user->skills ?? []) > 0 ? 25 : 0;
        $user->profile_completion = min(100, $completion);
        $user->save();

        return back()->with('success', 'Profile updated successfully! 🎉');
    }

    public function uploadPortfolio(Request $request)
{
    $request->validate(['portfolio.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120']);

    foreach ($request->file('portfolio') as $file) {
        auth()->user()->addMedia($file)->toMediaCollection('portfolio');
    }

    return response()->json(['success' => true]);
}

    public function deletePortfolio(\Spatie\MediaLibrary\MediaCollections\Models\Media $media)
    {
        if ($media->model_id !== auth()->id()) abort(403);

        $media->delete();

        return back()->with('success', 'Portfolio image deleted successfully! 🎉');
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
