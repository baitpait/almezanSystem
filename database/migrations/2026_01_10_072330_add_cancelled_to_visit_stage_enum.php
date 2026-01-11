<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add 'cancelled' option to visit_stage enum
     */
    public function up(): void
    {
        // For MySQL: Add 'cancelled' to enum values
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE appointments MODIFY COLUMN visit_stage ENUM('scheduled', 'waiting', 'in_consultation', 'completed', 'cancelled') NULL");
        }
        // For SQLite: No action needed as SQLite doesn't enforce enum constraints
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For MySQL: Remove 'cancelled' from enum values
        if (config('database.default') === 'mysql') {
            // First, update any existing 'cancelled' records to 'completed'
            DB::table('appointments')
                ->where('visit_stage', 'cancelled')
                ->update(['visit_stage' => 'completed']);
            
            // Then modify the enum column
            DB::statement("ALTER TABLE appointments MODIFY COLUMN visit_stage ENUM('scheduled', 'waiting', 'in_consultation', 'completed') NULL");
        }
    }
};
