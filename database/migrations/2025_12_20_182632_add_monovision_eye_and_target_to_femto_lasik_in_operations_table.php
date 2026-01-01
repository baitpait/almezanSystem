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
            // Drop old femto_monovision boolean field (if exists)
            if (Schema::hasColumn('operations', 'femto_monovision')) {
                $table->dropColumn('femto_monovision');
            }
            
            // Add new fields for Femto Lasik (similar to PRK) - check if not exists
            if (!Schema::hasColumn('operations', 'femto_monovision_eye')) {
                $table->enum('femto_monovision_eye', ['OD', 'OS'])->nullable()->after('femto_excimer_profile');
            }
            if (!Schema::hasColumn('operations', 'femto_target')) {
                $table->string('femto_target')->nullable()->after('femto_monovision_eye');
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
            if (Schema::hasColumn('operations', 'femto_monovision_eye')) {
                $table->dropColumn('femto_monovision_eye');
            }
            if (Schema::hasColumn('operations', 'femto_target')) {
                $table->dropColumn('femto_target');
            }
            
            // Re-add old field
            if (!Schema::hasColumn('operations', 'femto_monovision')) {
                $table->boolean('femto_monovision')->nullable()->after('femto_excimer_profile');
            }
        });
    }
};
