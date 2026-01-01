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
        // Change all decimal and integer fields to string in refractive_profiles table
        Schema::table('refractive_profiles', function (Blueprint $table) {
            // Current Eyeglasses OD
            $table->string('current_eyeglasses_od_sphere')->nullable()->change();
            $table->string('current_eyeglasses_od_cylinder')->nullable()->change();
            $table->string('current_eyeglasses_od_axis')->nullable()->change();
            
            // Current Eyeglasses OS
            $table->string('current_eyeglasses_os_sphere')->nullable()->change();
            $table->string('current_eyeglasses_os_cylinder')->nullable()->change();
            $table->string('current_eyeglasses_os_axis')->nullable()->change();
            
            // Manifest Refraction OD
            $table->string('manifest_refraction_od_sphere')->nullable()->change();
            $table->string('manifest_refraction_od_cylinder')->nullable()->change();
            $table->string('manifest_refraction_od_axis')->nullable()->change();
            
            // Manifest Refraction OS
            $table->string('manifest_refraction_os_sphere')->nullable()->change();
            $table->string('manifest_refraction_os_cylinder')->nullable()->change();
            $table->string('manifest_refraction_os_axis')->nullable()->change();
            
            // Refraction After Dilatation OD
            $table->string('refraction_after_dilation_od_sphere')->nullable()->change();
            $table->string('refraction_after_dilation_od_cylinder')->nullable()->change();
            $table->string('refraction_after_dilation_od_axis')->nullable()->change();
            
            // Refraction After Dilatation OS
            $table->string('refraction_after_dilation_os_sphere')->nullable()->change();
            $table->string('refraction_after_dilation_os_cylinder')->nullable()->change();
            $table->string('refraction_after_dilation_os_axis')->nullable()->change();
            
            // Pupil Diameter
            $table->string('pupil_diameter_od_mesopic')->nullable()->change();
            $table->string('pupil_diameter_od_scotopic')->nullable()->change();
            $table->string('pupil_diameter_os_mesopic')->nullable()->change();
            $table->string('pupil_diameter_os_scotopic')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refractive_profiles', function (Blueprint $table) {
            // Revert to original types
            $table->decimal('current_eyeglasses_od_sphere', 8, 2)->nullable()->change();
            $table->decimal('current_eyeglasses_od_cylinder', 8, 2)->nullable()->change();
            $table->integer('current_eyeglasses_od_axis')->nullable()->change();
            
            $table->decimal('current_eyeglasses_os_sphere', 8, 2)->nullable()->change();
            $table->decimal('current_eyeglasses_os_cylinder', 8, 2)->nullable()->change();
            $table->integer('current_eyeglasses_os_axis')->nullable()->change();
            
            $table->decimal('manifest_refraction_od_sphere', 8, 2)->nullable()->change();
            $table->decimal('manifest_refraction_od_cylinder', 8, 2)->nullable()->change();
            $table->integer('manifest_refraction_od_axis')->nullable()->change();
            
            $table->decimal('manifest_refraction_os_sphere', 8, 2)->nullable()->change();
            $table->decimal('manifest_refraction_os_cylinder', 8, 2)->nullable()->change();
            $table->integer('manifest_refraction_os_axis')->nullable()->change();
            
            $table->decimal('refraction_after_dilation_od_sphere', 8, 2)->nullable()->change();
            $table->decimal('refraction_after_dilation_od_cylinder', 8, 2)->nullable()->change();
            $table->integer('refraction_after_dilation_od_axis')->nullable()->change();
            
            $table->decimal('refraction_after_dilation_os_sphere', 8, 2)->nullable()->change();
            $table->decimal('refraction_after_dilation_os_cylinder', 8, 2)->nullable()->change();
            $table->integer('refraction_after_dilation_os_axis')->nullable()->change();
            
            $table->decimal('pupil_diameter_od_mesopic', 5, 2)->nullable()->change();
            $table->decimal('pupil_diameter_od_scotopic', 5, 2)->nullable()->change();
            $table->decimal('pupil_diameter_os_mesopic', 5, 2)->nullable()->change();
            $table->decimal('pupil_diameter_os_scotopic', 5, 2)->nullable()->change();
        });
    }
};
