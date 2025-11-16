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
            // Add in-house developer assignment fields
            $table->boolean('assigned_to_inhouse')->default(false)->after('awarded_to');
            $table->foreignId('inhouse_developer_id')->nullable()->constrained('users')->onDelete('set null')->after('assigned_to_inhouse');
            $table->timestamp('inhouse_assigned_at')->nullable()->after('inhouse_developer_id');
            $table->text('inhouse_assignment_notes')->nullable()->after('inhouse_assigned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->dropForeign(['inhouse_developer_id']);
            $table->dropColumn(['assigned_to_inhouse', 'inhouse_developer_id', 'inhouse_assigned_at', 'inhouse_assignment_notes']);
        });
    }
};
