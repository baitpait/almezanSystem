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
        // Modify enum to include 'not_normal'
        DB::statement("ALTER TABLE ectasia_risk_assessments MODIFY COLUMN tomography_status ENUM('normal', 'suspicious', 'other', 'not_normal') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE ectasia_risk_assessments MODIFY COLUMN tomography_status ENUM('normal', 'suspicious', 'other') NULL");
    }
};
