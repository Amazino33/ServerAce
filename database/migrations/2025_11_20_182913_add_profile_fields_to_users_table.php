<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Basic
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->default('avatars/default.png')->nullable();

            // Visibility
            $table->boolean('profile_public')->default(true);
            $table->boolean('available_for_work')->default(true);

            // Freelancer fields
            $table->string('title')->nullable();                    // e.g., "Senior Laravel Developer"
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->string('experience_level')->nullable();         // beginner, intermediate, expert
            $table->json('skills')->nullable();                     // ["Laravel", "Vue", "Tailwind"]
            $table->text('portfolio_description')->nullable();

            // Client fields
            $table->string('company_name')->nullable();
            $table->string('industry')->nullable();
            $table->string('company_size')->nullable();

            // Social
            $table->string('website')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('twitter_url')->nullable();

            // Profile completion (for progress bar)
            $table->integer('profile_completion')->default(30);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'location', 'phone', 'bio', 'avatar',
                'profile_public', 'available_for_work',
                'title', 'hourly_rate', 'experience_level', 'skills', 'portfolio_description',
                'company_name', 'industry', 'company_size',
                'website', 'linkedin_url', 'github_url', 'twitter_url',
                'profile_completion'
            ]);
        });
    }
};