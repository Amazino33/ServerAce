<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gig_applications', function (Blueprint $table) {
            $table->id();

            // Foreign key - Who and What
            $table->foreignId('gig_id')
                  ->constrained()
                  ->onDelete('cascade'); // If gig is deleted, delete application

            $table->foreignId('freelancer_id')
                  ->constrained('users') // References users table
                  ->onDelete('cascade'); // if users is deleted, delete application

            // Application Data
            $table->text('cover_letter');
            $table->decimal('proposed_price', 10, 2);

            // Status - Using enum
            $table->enum('status', ['pending', 'accepted', 'rejected'])
                  ->default('pending');

            // Timestamp
            $table->timestamps();

            // Constraints
            // Prevent duplicate application - one user can only apply once per gig
            $table->unique(['gig_id', 'freelancer_id']);

            // Add indexes for faster queries
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gig_applications');
    }
};
