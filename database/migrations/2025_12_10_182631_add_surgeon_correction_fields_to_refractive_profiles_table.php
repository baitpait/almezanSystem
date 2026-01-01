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
            // Surgeon Correction - OD (Right Eye)
            $table->decimal('surgeon_correction_od_sphere', 8, 2)->nullable()->after('refraction_after_dilation_os_vision');
            $table->decimal('surgeon_correction_od_cylinder', 8, 2)->nullable()->after('surgeon_correction_od_sphere');
            $table->integer('surgeon_correction_od_axis')->nullable()->after('surgeon_correction_od_cylinder');
            $table->string('surgeon_correction_od_bscva')->nullable()->after('surgeon_correction_od_axis');
            
            // Surgeon Correction - OS (Left Eye)
            $table->decimal('surgeon_correction_os_sphere', 8, 2)->nullable()->after('surgeon_correction_od_bscva');
            $table->decimal('surgeon_correction_os_cylinder', 8, 2)->nullable()->after('surgeon_correction_os_sphere');
            $table->integer('surgeon_correction_os_axis')->nullable()->after('surgeon_correction_os_cylinder');
            $table->string('surgeon_correction_os_bscva')->nullable()->after('surgeon_correction_os_axis');
            
            // Cycloplegic Refraction (if not already covered by refraction_after_dilation)
            // We'll use refraction_after_dilation for Cycloplegic, but add explicit fields if needed
            $table->decimal('cycloplegic_refraction_od_sphere', 8, 2)->nullable()->after('surgeon_correction_os_bscva');
            $table->decimal('cycloplegic_refraction_od_cylinder', 8, 2)->nullable()->after('cycloplegic_refraction_od_sphere');
            $table->integer('cycloplegic_refraction_od_axis')->nullable()->after('cycloplegic_refraction_od_cylinder');
            $table->string('cycloplegic_refraction_od_bscva')->nullable()->after('cycloplegic_refraction_od_axis');
            
            $table->decimal('cycloplegic_refraction_os_sphere', 8, 2)->nullable()->after('cycloplegic_refraction_od_bscva');
            $table->decimal('cycloplegic_refraction_os_cylinder', 8, 2)->nullable()->after('cycloplegic_refraction_os_sphere');
            $table->integer('cycloplegic_refraction_os_axis')->nullable()->after('cycloplegic_refraction_os_cylinder');
            $table->string('cycloplegic_refraction_os_bscva')->nullable()->after('cycloplegic_refraction_os_axis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
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
                'cycloplegic_refraction_od_sphere',
                'cycloplegic_refraction_od_cylinder',
                'cycloplegic_refraction_od_axis',
                'cycloplegic_refraction_od_bscva',
                'cycloplegic_refraction_os_sphere',
                'cycloplegic_refraction_os_cylinder',
                'cycloplegic_refraction_os_axis',
                'cycloplegic_refraction_os_bscva',
            ]);
        });
    }
};
