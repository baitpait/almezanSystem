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
        // Update all monovision_eye ENUM fields to include 'none' option
        $monovisionFields = [
            // Old shared fields
            'prk_monovision_eye',
            'femto_monovision_eye',
            'smile_monovision_eye',
            'ptk_monovision_eye',
            // Separate OD fields
            'prk_monovision_eye_od',
            'femto_monovision_eye_od',
            'smile_monovision_eye_od',
            'ptk_monovision_eye_od',
            // Separate OS fields
            'prk_monovision_eye_os',
            'femto_monovision_eye_os',
            'smile_monovision_eye_os',
            'ptk_monovision_eye_os',
        ];

        foreach ($monovisionFields as $field) {
            if (Schema::hasColumn('operations', $field)) {
                // Modify ENUM to include 'none'
                DB::statement("ALTER TABLE `operations` MODIFY COLUMN `{$field}` ENUM('none', 'OD', 'OS') NULL");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM without 'none'
        $monovisionFields = [
            'prk_monovision_eye',
            'femto_monovision_eye',
            'smile_monovision_eye',
            'ptk_monovision_eye',
            'prk_monovision_eye_od',
            'femto_monovision_eye_od',
            'smile_monovision_eye_od',
            'ptk_monovision_eye_od',
            'prk_monovision_eye_os',
            'femto_monovision_eye_os',
            'smile_monovision_eye_os',
            'ptk_monovision_eye_os',
        ];

        foreach ($monovisionFields as $field) {
            if (Schema::hasColumn('operations', $field)) {
                // First, set any 'none' values to NULL
                DB::statement("UPDATE `operations` SET `{$field}` = NULL WHERE `{$field}` = 'none'");
                // Then modify ENUM back to original
                DB::statement("ALTER TABLE `operations` MODIFY COLUMN `{$field}` ENUM('OD', 'OS') NULL");
            }
        }
    }
};
