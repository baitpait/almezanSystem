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
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');
            
            // Medical History - Yes/No Fields
            $table->boolean('diabetes')->default(false);
            $table->boolean('chronic_disease')->default(false);
            $table->text('chronic_disease_details')->nullable();
            $table->boolean('herpes_keratitis')->default(false);
            $table->boolean('glaucoma')->default(false);
            $table->boolean('family_history_keratoconus')->default(false);
            $table->boolean('eye_rubber')->default(false);
            $table->boolean('pregnancy')->default(false);
            $table->boolean('ocular_surgery')->default(false);
            $table->text('ocular_surgery_details')->nullable();
            $table->text('family_history_ocular_disease')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('allergies')->nullable();
            
            // Vision Stability
            $table->boolean('glare_halos_squint')->default(false);
            $table->boolean('refraction_stable_1year')->default(false);
            $table->boolean('contact_lens_use')->default(false);
            
            // Past Medical History
            $table->text('past_medical_history')->nullable();
            $table->text('past_ophthalmic_history')->nullable();
            $table->text('chief_complaint')->nullable();
            
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
        Schema::dropIfExists('medical_histories');
    }
};
