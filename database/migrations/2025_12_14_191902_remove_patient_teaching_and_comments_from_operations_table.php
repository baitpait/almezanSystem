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
            $table->dropColumn([
                'patient_teaching_completed',
                'patient_teaching_details',
                'increased_risk_for',
                'comments'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            $table->boolean('patient_teaching_completed')->default(false)->after('target');
            $table->text('patient_teaching_details')->nullable()->after('patient_teaching_completed');
            $table->text('increased_risk_for')->nullable()->after('patient_teaching_details');
            $table->text('comments')->nullable()->after('increased_risk_for');
        });
    }
};
