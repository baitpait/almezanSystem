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
        Schema::table('refractive_profiles', function (Blueprint $table) {
            $table->decimal('pupil_diameter_od_mesopic', 5, 2)->nullable()->after('refraction_after_dilation_type');
            $table->decimal('pupil_diameter_od_scotopic', 5, 2)->nullable()->after('pupil_diameter_od_mesopic');
            $table->decimal('pupil_diameter_os_mesopic', 5, 2)->nullable()->after('pupil_diameter_od_scotopic');
            $table->decimal('pupil_diameter_os_scotopic', 5, 2)->nullable()->after('pupil_diameter_os_mesopic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refractive_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'pupil_diameter_od_mesopic',
                'pupil_diameter_od_scotopic',
                'pupil_diameter_os_mesopic',
                'pupil_diameter_os_scotopic',
            ]);
        });
    }
};
