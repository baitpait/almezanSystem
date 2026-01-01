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
            // Dominant Eye and Monovision
            $table->enum('dominant_eye', ['OD', 'OS'])->nullable()->after('refraction_after_dilation_os_vision');
            $table->string('simulation_for_monovision')->nullable()->after('dominant_eye');
            
            // R/G (Red/Green) test in Manifest Refraction
            $table->string('manifest_refraction_od_rg')->nullable()->after('manifest_refraction_od_add_j1')->comment('R/G test result');
            $table->string('manifest_refraction_os_rg')->nullable()->after('manifest_refraction_os_add_j1')->comment('R/G test result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refractive_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'dominant_eye',
                'simulation_for_monovision',
                'manifest_refraction_od_rg',
                'manifest_refraction_os_rg',
            ]);
        });
    }
};
