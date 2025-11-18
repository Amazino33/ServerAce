<?php

// Create migration: php artisan make:migration create_payment_system_tables

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Payments table - tracks all payment transactions
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gig_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('freelancer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('freelancer_amount', 10, 2); // Amount after platform fee
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'held', 'released', 'refunded', 'failed'])->default('pending');
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
            
            $table->index('stripe_payment_intent_id');
            $table->index('status');
        });

        // Escrow table - holds funds until work completion
        Schema::create('escrow_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->foreignId('gig_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['holding', 'released', 'refunded'])->default('holding');
            $table->timestamp('held_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->text('release_notes')->nullable();
            $table->timestamps();
        });

        // Payouts table - tracks payments to freelancers
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freelancer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'processing', 'paid', 'failed'])->default('pending');
            $table->string('stripe_transfer_id')->nullable();
            $table->string('stripe_payout_id')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index('stripe_transfer_id');
            $table->index('status');
        });

        // Transactions table - complete transaction history
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('transactionable'); // Can be payment, payout, refund, etc.
            $table->enum('type', ['payment', 'payout', 'refund', 'fee', 'dispute']);
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'type']);
            $table->index('status');
        });

        // Update gigs table to include payment info
        Schema::table('gigs', function (Blueprint $table) {
            $table->boolean('payment_required')->default(true);
            $table->boolean('payment_completed')->default(false);
            $table->timestamp('payment_completed_at')->nullable();
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
        });

        // Update users table for Stripe Connect (freelancers)
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_account_id')->nullable(); // For freelancers
            $table->boolean('stripe_onboarded')->default(false);
            $table->timestamp('stripe_onboarded_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['stripe_account_id', 'stripe_onboarded', 'stripe_onboarded_at']);
        });

        Schema::table('gigs', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
            $table->dropColumn(['payment_required', 'payment_completed', 'payment_completed_at', 'payment_id']);
        });

        Schema::dropIfExists('transactions');
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('escrow_accounts');
        Schema::dropIfExists('payments');
    }
};