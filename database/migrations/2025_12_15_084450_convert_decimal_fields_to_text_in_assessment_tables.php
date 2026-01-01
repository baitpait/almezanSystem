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
        // Convert decimal fields to text in ectasia_risk_assessments table
        Schema::table('ectasia_risk_assessments', function (Blueprint $table) {
            // Use DB::statement for MySQL to change column types
            DB::statement('ALTER TABLE `ectasia_risk_assessments` 
                MODIFY COLUMN `pta_percentage_od` TEXT NULL,
                MODIFY COLUMN `pta_percentage_os` TEXT NULL,
                MODIFY COLUMN `rsb_od` TEXT NULL,
                MODIFY COLUMN `rsb_os` TEXT NULL,
                MODIFY COLUMN `pachymetry_thinnest_od` TEXT NULL,
                MODIFY COLUMN `pachymetry_thinnest_os` TEXT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ectasia_risk_assessments', function (Blueprint $table) {
            // Convert back to decimal (with default values)
            DB::statement('ALTER TABLE `ectasia_risk_assessments` 
                MODIFY COLUMN `pta_percentage_od` DECIMAL(5,2) NULL,
                MODIFY COLUMN `pta_percentage_os` DECIMAL(5,2) NULL,
                MODIFY COLUMN `rsb_od` DECIMAL(5,2) NULL,
                MODIFY COLUMN `rsb_os` DECIMAL(5,2) NULL,
                MODIFY COLUMN `pachymetry_thinnest_od` INTEGER NULL,
                MODIFY COLUMN `pachymetry_thinnest_os` INTEGER NULL');
        });
    }
};
