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
        Schema::create('ectasia_risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');
            
            // PTA% (Percent Tissue Altered)
            $table->decimal('pta_percentage_od', 5, 2)->nullable();
            $table->decimal('pta_percentage_os', 5, 2)->nullable();
            
            // RSB (Residual Stromal Bed)
            $table->decimal('rsb_od', 5, 2)->nullable();
            $table->decimal('rsb_os', 5, 2)->nullable();
            
            // Tomography
            $table->boolean('tomography_normal_pattern')->default(false);
            $table->text('tomography_other')->nullable();
            
            // Pachymetry (Thinnest Point)
            $table->integer('pachymetry_thinnest_od')->nullable();
            $table->integer('pachymetry_thinnest_os')->nullable();
            
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
        Schema::dropIfExists('ectasia_risk_assessments');
    }
};
