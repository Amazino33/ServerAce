<?php

use App\Enums\UserRole;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeController;
use App\Livewire\BrowseGigs;
use App\Models\Gig;
use Illuminate\Support\Facades\Route;
use App\Livewire\Freelancer\Dashboard as FreelancerDashboard;

Route::get('/', function () {
    return view('pages.home');
})->name('home');


Route::get('/test-stripe-connection', function() {
    try {
        $stripe = new \Stripe\StripeClient(config('cashier.secret'));
        $balance = $stripe->balance->retrieve();
        
        return response()->json([
            'success' => true,
            'message' => 'Connected to Stripe successfully!',
            'balance' => $balance,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
});

Route::get('/test-stripe-full', function() {
    try {
        $stripe = new \Stripe\StripeClient(config('cashier.secret'));
        
        // Test 1: Retrieve balance
        $balance = $stripe->balance->retrieve();
        
        // Test 2: Create a test account
        $account = $stripe->accounts->create([
            'type' => 'express',
            'country' => 'US',
            'email' => 'test@example.com',
            'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers' => ['requested' => true],
            ],
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'All Stripe operations working!',
            'tests' => [
                'balance_retrieved' => true,
                'account_created' => true,
                'account_id' => $account->id,
            ],
            'config' => [
                'has_publishable_key' => !empty(config('cashier.key')),
                'has_secret_key' => !empty(config('cashier.secret')),
                'publishable_key_prefix' => substr(config('cashier.key'), 0, 10),
                'secret_key_prefix' => substr(config('cashier.secret'), 0, 10),
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'type' => get_class($e),
            'config' => [
                'has_publishable_key' => !empty(config('cashier.key')),
                'has_secret_key' => !empty(config('cashier.secret')),
            ]
        ], 500);
    }
});


Route::get('/browse', function() {
    return view('pages.browse');
})->name('browse');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - base access for all authenticated users
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/profile/portfolio', [ProfileController::class, 'uploadPortfolio'])->name('profile.portfolio.upload');
    Route::delete('/profile/portfolio/{media}', [ProfileController::class, 'deletePortfolio'])->name('profile.portfolio.delete');


    // Admin-only areas
    Route::prefix('admin')->middleware('role:'.UserRole::ADMIN->value)->name('admin.')->group(function () {
        Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)
            ->name('dashboard');
        Route::get('/inhouse-assignments', App\Livewire\Admin\ManageInHouseAssignments::class)
            ->name('inhouse-assignments');
    });

    // Freelancer-only areas
    Route::prefix('freelancer')->middleware('role:'.UserRole::FREELANCER->value)->name('freelancer.')->group(function () {
        Route::get('/dashboard', FreelancerDashboard::class)
        ->name('dashboard');
        Route::get('/stripe/onboarding', [StripeController::class, 'onboarding'])
            ->name('freelancer.stripe.onboarding');
        Route::get('/stripe/return', [StripeController::class, 'return'])
            ->name('freelancer.stripe.return');
        Route::get('/stripe/refresh', [StripeController::class, 'refresh'])
            ->name('freelancer.stripe.refresh'); 
        Route::get('/stripe/onboarding', [StripeController::class, 'onboarding'])
            ->name('stripe.onboarding');
        
        Route::get('/stripe/return', [StripeController::class, 'return'])
            ->name('stripe.return');
        
        Route::get('/stripe/refresh', [StripeController::class, 'refresh'])
            ->name('stripe.refresh');
        
        Route::get('/stripe/dashboard', [StripeController::class, 'accountDashboard'])
            ->name('stripe.dashboard');
        
        Route::get('/stripe/status', [StripeController::class, 'status'])
            ->name('stripe.status');
        
        Route::post('/stripe/disconnect', [StripeController::class, 'disconnect'])
            ->name('stripe.disconnect');       
    });

    // Client-only areas
    Route::prefix('client')->middleware('role:'.UserRole::CLIENT->value)->name('client.')->group(function () {
        Route::get('/dashboard', App\Livewire\Client\Dashboard::class)
            ->name('dashboard');

        Route::get('/payment/success/', [PaymentController::class, 'success'])
            ->name('payment.success');

        Route::get('/payment/history', [PaymentController::class, 'history'])
            ->name('payment.history');
            
        Route::get('/payment/{payment}', [PaymentController::class, 'show'])
            ->name('payment.show');

        Route::post('/payment/{payment}/release', [PaymentController::class, 'release'])
            ->name('payment.release');
            
        Route::post('/payment/{payment}/refund', [PaymentController::class, 'refund'])
            ->name('payment.refund');
        // Create payment intent (client accepting application)
        Route::post('/payment/intent/{application}', [PaymentController::class, 'createIntent'])
            ->name('payment.create-intent');
    });

    Route::get('/post-gig', function () {
        return view('pages.post-gig');
    })->name('post-gig');

    Route::get('/category/{category:slug}', [CategoryController::class, 'show'])
        ->name('category.show');
    // Route::resource('gigs', GigController::class);

    Route::get('/gig/{gig:slug}', function (Gig $gig) {
        $gig->load(['user', 'category', 'media']);
        return view('gigs.show', compact('gig'));
    })->name('gigs.show');

    Route::get('/gigs', BrowseGigs::class )->name('gigs.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/@{user:username}', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
