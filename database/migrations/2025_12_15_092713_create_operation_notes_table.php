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
        Schema::create('operation_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            
            // Operation Type
            $table->enum('operation_type', ['PRK', 'Femto-LASIK', 'SMILE', 'PTK'])->nullable();
            $table->enum('operation_eye', ['OD', 'OS', 'OU'])->default('OU');
            
            // Common Fields for all operations
            $table->boolean('monovision')->default(false);
            $table->boolean('eye_drops_vigamox')->default(false);
            $table->boolean('eye_drops_pred_forte')->default(false);
            $table->boolean('eye_drops_other')->default(false);
            $table->text('eye_drops_other_details')->nullable();
            
            // PRK Specific Fields
            $table->enum('prk_epithelial_removal', ['Alcohol', 'Mechanical', 'Trans-PRK'])->nullable();
            $table->enum('prk_excimer_profile', ['Aspheric Front', 'Topography-guided'])->nullable();
            $table->boolean('prk_mmc_0_02_percent')->default(false);
            $table->boolean('prk_bandage_contact_lens')->default(false);
            
            // Femto-LASIK Specific Fields
            $table->boolean('femto_flap_done')->nullable();
            $table->enum('femto_excimer_profile', ['Aspheric Front', 'Topography-guided'])->nullable();
            $table->boolean('femto_bandage_contact_lens')->default(false);
            
            // SMILE Specific Fields
            $table->boolean('smile_complete_lenticule_separation')->nullable();
            $table->boolean('smile_complete_lenticule_extraction')->nullable();
            
            // PTK Specific Fields
            $table->enum('ptk_excimer_profile', ['Normal', 'Topography-guided'])->nullable();
            
            // Additional Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('appointment_id');
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('operation_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_notes');
    }
};
