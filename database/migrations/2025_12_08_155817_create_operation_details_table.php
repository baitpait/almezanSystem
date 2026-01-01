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
        Schema::create('operation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');
            
            // Procedure Type
            $table->boolean('femto_lasik')->default(false);
            $table->boolean('prk_mmc')->default(false);
            $table->enum('prk_type', ['Alcohol 20%', 'Trans-PRK', 'Brush'])->nullable();
            $table->boolean('trans_prk')->default(false);
            $table->boolean('ptk')->default(false);
            $table->boolean('topography_guided')->default(false);
            
            // Excimer Profile
            $table->enum('excimer_profile', [
                'WFO',
                'Topography Guided',
                'Custom',
                'Other'
            ])->nullable();
            
            // MMC (Mitomycin C) Details
            $table->decimal('mmc_concentration', 5, 3)->nullable()->comment('e.g., 0.02 for 0.02%');
            $table->integer('mmc_duration_seconds')->nullable();
            
            // Additional Procedures
            $table->boolean('bll')->default(false);
            $table->text('drops_used')->nullable();
            
            // Target Refraction
            $table->text('target_refraction_od')->nullable();
            $table->text('target_refraction_os')->nullable();
            $table->string('mv_eye')->nullable()->comment('Monovision Eye');
            
            // Complications
            $table->boolean('has_complications')->default(false);
            $table->text('complications_details')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('operation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_details');
    }
};
