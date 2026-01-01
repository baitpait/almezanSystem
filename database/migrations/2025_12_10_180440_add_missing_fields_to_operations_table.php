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
            // Check if columns exist before adding
            if (!Schema::hasColumn('operations', 'topographic_guided_profile')) {
                $table->string('topographic_guided_profile')->nullable()->after('presbyopia_profile');
            }
            if (!Schema::hasColumn('operations', 'mv_eye')) {
                $table->enum('mv_eye', ['OD', 'OS'])->nullable()->after('topographic_guided_profile')->comment('Monovision Eye');
            }
            if (!Schema::hasColumn('operations', 'target')) {
                $table->string('target')->nullable()->after('mv_eye');
            }
            if (!Schema::hasColumn('operations', 'comments')) {
                $table->text('comments')->nullable()->after('target');
            }
            if (!Schema::hasColumn('operations', 'patient_teaching_details')) {
                $table->text('patient_teaching_details')->nullable()->after('patient_teaching_completed');
            }
            if (!Schema::hasColumn('operations', 'increased_risk_for')) {
                $table->text('increased_risk_for')->nullable()->after('patient_teaching_details')->comment('Explained increased risk for');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            $table->dropColumn([
                'excimer_profile',
                'topographic_guided_profile',
                'mv_eye',
                'target',
                'comments',
                'patient_teaching_details',
                'increased_risk_for',
            ]);
        });
    }
};
