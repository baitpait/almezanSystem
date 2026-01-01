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
        Schema::table('operation_notes', function (Blueprint $table) {
            // Add PTK fields similar to PRK
            $table->enum('ptk_epithelial_removal', ['Alcohol', 'Mechanical', 'Trans-PTK'])->nullable()->after('ptk_excimer_profile');
            $table->boolean('ptk_mmc_0_02_percent')->default(false)->after('ptk_epithelial_removal');
            $table->boolean('ptk_bandage_contact_lens')->default(false)->after('ptk_mmc_0_02_percent');
        });

        // Update ptk_excimer_profile enum to match PRK options
        DB::statement("ALTER TABLE operation_notes MODIFY COLUMN ptk_excimer_profile ENUM('Aspheric Front', 'Topography-guided') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operation_notes', function (Blueprint $table) {
            $table->dropColumn(['ptk_epithelial_removal', 'ptk_mmc_0_02_percent', 'ptk_bandage_contact_lens']);
        });

        // Revert ptk_excimer_profile enum to original
        DB::statement("ALTER TABLE operation_notes MODIFY COLUMN ptk_excimer_profile ENUM('Normal', 'Topography-guided') NULL");
    }
};
