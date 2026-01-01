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
            $table->dropColumn([
                'surgeon_correction_od_sphere',
                'surgeon_correction_od_cylinder',
                'surgeon_correction_od_axis',
                'surgeon_correction_od_bscva',
                'surgeon_correction_os_sphere',
                'surgeon_correction_os_cylinder',
                'surgeon_correction_os_axis',
                'surgeon_correction_os_bscva',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refractive_profiles', function (Blueprint $table) {
            $table->decimal('surgeon_correction_od_sphere', 8, 2)->nullable()->after('refraction_after_dilation_os_vision');
            $table->decimal('surgeon_correction_od_cylinder', 8, 2)->nullable()->after('surgeon_correction_od_sphere');
            $table->integer('surgeon_correction_od_axis')->nullable()->after('surgeon_correction_od_cylinder');
            $table->string('surgeon_correction_od_bscva')->nullable()->after('surgeon_correction_od_axis');
            $table->decimal('surgeon_correction_os_sphere', 8, 2)->nullable()->after('surgeon_correction_od_bscva');
            $table->decimal('surgeon_correction_os_cylinder', 8, 2)->nullable()->after('surgeon_correction_os_sphere');
            $table->integer('surgeon_correction_os_axis')->nullable()->after('surgeon_correction_os_cylinder');
            $table->string('surgeon_correction_os_bscva')->nullable()->after('surgeon_correction_os_axis');
        });
    }
};
