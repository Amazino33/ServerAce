<?php

use App\Enums\UserRole;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Models\Gig;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/browse', function() {
    return view('pages.browse');
})->name('browse');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - base access for all authenticated users
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    // Admin-only areas
    Route::prefix('admin')->middleware('role:'.UserRole::ADMIN->value)->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });

    // Freelancer-only areas
    Route::prefix('freelancer')->middleware('role:'.UserRole::FREELANCER->value)->name('freelancer.')->group(function () {
        Route::get('/dashboard', function () {
            return view('freelancer.dashboard');
        })->name('dashboard');
    });

    // Client-only areas
    Route::prefix('client')->middleware('role:'.UserRole::CLIENT->value)->name('client.')->group(function () {
        Route::get('/dashboard', function () {
            return view('client.dashboard');
        })->name('dashboard');
    });

    Route::get('/post-gig', function () {
        return view('pages.post-gig');
    })->name('post-gig');

    Route::get('/category/{category:slug}', [CategoryController::class, 'show'])
        ->name('category.show');
    // Route::resource('gigs', GigController::class);

    Route::get('/gig/{gig:slug}', function (Gig $gig) {
        return view('gigs.show', compact('gig'));
    })->name('gigs.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
