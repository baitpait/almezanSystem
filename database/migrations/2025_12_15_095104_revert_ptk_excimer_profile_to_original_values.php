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
        // Revert ptk_excimer_profile enum to original values (Normal, Topography-guided)
        DB::statement("ALTER TABLE operation_notes MODIFY COLUMN ptk_excimer_profile ENUM('Normal', 'Topography-guided') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to PRK values if needed
        DB::statement("ALTER TABLE operation_notes MODIFY COLUMN ptk_excimer_profile ENUM('Aspheric Front', 'Topography-guided') NULL");
    }
};
