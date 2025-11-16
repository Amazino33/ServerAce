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
        Schema::table('gigs', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('status');
            $table->timestamp('approved_at')->nullable()->after('is_approved');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');

            $table->index(['is_approved', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->dropIndex(['is_approved', 'status']);
            $table->dropColumn(['is_approved', 'approved_at', 'approved_by']);
        });
    }
};
