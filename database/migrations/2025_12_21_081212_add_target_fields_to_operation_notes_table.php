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
        Schema::table('operation_notes', function (Blueprint $table) {
            // PRK Target Fields - Separate for OD and OS
            if (!Schema::hasColumn('operation_notes', 'prk_target_od')) {
                $table->string('prk_target_od')->nullable()->after('prk_bandage_contact_lens_os');
            }
            if (!Schema::hasColumn('operation_notes', 'prk_target_os')) {
                $table->string('prk_target_os')->nullable()->after('prk_target_od');
            }
            
            // Femto-LASIK Target Fields - Separate for OD and OS
            if (!Schema::hasColumn('operation_notes', 'femto_target_od')) {
                $table->string('femto_target_od')->nullable()->after('femto_bandage_contact_lens_os');
            }
            if (!Schema::hasColumn('operation_notes', 'femto_target_os')) {
                $table->string('femto_target_os')->nullable()->after('femto_target_od');
            }
            
            // SMILE Target Fields - Separate for OD and OS
            if (!Schema::hasColumn('operation_notes', 'smile_target_od')) {
                $table->string('smile_target_od')->nullable()->after('smile_complete_lenticule_extraction_os');
            }
            if (!Schema::hasColumn('operation_notes', 'smile_target_os')) {
                $table->string('smile_target_os')->nullable()->after('smile_target_od');
            }
            
            // PTK Target Fields - Separate for OD and OS
            if (!Schema::hasColumn('operation_notes', 'ptk_target_od')) {
                $table->string('ptk_target_od')->nullable()->after('ptk_bandage_contact_lens_os');
            }
            if (!Schema::hasColumn('operation_notes', 'ptk_target_os')) {
                $table->string('ptk_target_os')->nullable()->after('ptk_target_od');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operation_notes', function (Blueprint $table) {
            $columnsToDrop = [
                'prk_target_od', 'prk_target_os',
                'femto_target_od', 'femto_target_os',
                'smile_target_od', 'smile_target_os',
                'ptk_target_od', 'ptk_target_os',
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('operation_notes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
