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
        Schema::table('ectasia_risk_assessments', function (Blueprint $table) {
            // Tomography status from PDF: "Normal pattern both eyes, no signs of ectasia" or "susp." (suspicious)
            $table->enum('tomography_status', ['normal', 'suspicious', 'other'])->nullable()->after('tomography_normal_pattern');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ectasia_risk_assessments', function (Blueprint $table) {
            $table->dropColumn('tomography_status');
        });
    }
};
