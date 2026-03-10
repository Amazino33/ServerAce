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
        Schema::table('gig_applications', function (Blueprint $table) {
            // Nullable because individual/solo bids will have no agency_id
            $table->foreignId('agency_id')
                ->nullable()
                ->after('freelancer_id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gig_applications', function (Blueprint $table) {
            $table->dropForeign(['agency_id']);
            $table->dropColumn('agency_id');
        });
    }
};
