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
        Schema::table('operations', function (Blueprint $table) {
            // Drop old ptk_topo_guided boolean field (if exists)
            if (Schema::hasColumn('operations', 'ptk_topo_guided')) {
                $table->dropColumn('ptk_topo_guided');
            }
            
            // Add PRK-like fields for PTK - check if not exists
            if (!Schema::hasColumn('operations', 'ptk_epithelial_removal')) {
                $table->string('ptk_epithelial_removal')->nullable()->after('smile_target');
            }
            if (!Schema::hasColumn('operations', 'ptk_excimer_profile')) {
                $table->string('ptk_excimer_profile')->nullable()->after('ptk_epithelial_removal');
            }
            if (!Schema::hasColumn('operations', 'ptk_monovision_eye')) {
                $table->enum('ptk_monovision_eye', ['OD', 'OS'])->nullable()->after('ptk_excimer_profile');
            }
            if (!Schema::hasColumn('operations', 'ptk_target')) {
                $table->string('ptk_target')->nullable()->after('ptk_monovision_eye');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            // Drop new fields
            $columnsToDrop = ['ptk_epithelial_removal', 'ptk_excimer_profile', 'ptk_monovision_eye', 'ptk_target'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('operations', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Re-add old field
            if (!Schema::hasColumn('operations', 'ptk_topo_guided')) {
                $table->boolean('ptk_topo_guided')->nullable()->after('smile_target');
            }
        });
    }
};
