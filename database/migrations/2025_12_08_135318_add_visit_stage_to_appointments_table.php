<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Add visit_stage column
            $table->enum('visit_stage', ['waiting', 'in_consultation', 'completed'])->nullable()->after('status');
            
            // Update status enum to include 'rescheduled'
            // Note: MySQL doesn't support modifying enum directly, so we need to use raw SQL
        });

        // Update status enum using raw SQL
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('scheduled', 'completed', 'cancelled', 'rescheduled', 'no_show') DEFAULT 'scheduled'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('visit_stage');
        });

        // Revert status enum
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('scheduled', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled'");
    }
};
