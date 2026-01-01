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
        Schema::create('eye_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');
            $table->enum('examination_type', ['pre_op', 'post_op'])->default('pre_op');
            
            // Right Eye (OD) Examination
            $table->string('od_lids')->nullable();
            $table->string('od_conjunctiva')->nullable();
            $table->string('od_cornea')->nullable();
            $table->string('od_tbut')->nullable();
            $table->string('od_schirmer')->nullable();
            $table->string('od_anterior_chamber')->nullable();
            $table->string('od_iris_pupil')->nullable();
            $table->string('od_lens')->nullable();
            $table->string('od_vitreous')->nullable();
            $table->string('od_optic_disc')->nullable();
            $table->string('od_retina')->nullable();
            $table->string('od_macula')->nullable();
            $table->string('od_vessels')->nullable();
            $table->string('od_fom')->nullable();
            $table->text('od_findings')->nullable();
            
            // Left Eye (OS) Examination
            $table->string('os_lids')->nullable();
            $table->string('os_conjunctiva')->nullable();
            $table->string('os_cornea')->nullable();
            $table->string('os_tbut')->nullable();
            $table->string('os_schirmer')->nullable();
            $table->string('os_anterior_chamber')->nullable();
            $table->string('os_iris_pupil')->nullable();
            $table->string('os_lens')->nullable();
            $table->string('os_vitreous')->nullable();
            $table->string('os_optic_disc')->nullable();
            $table->string('os_retina')->nullable();
            $table->string('os_macula')->nullable();
            $table->string('os_vessels')->nullable();
            $table->string('os_fom')->nullable();
            $table->text('os_findings')->nullable();
            
            // Visual Acuity
            $table->string('unaided_od')->nullable();
            $table->string('unaided_os')->nullable();
            
            // Refraction
            $table->text('manifest_refraction_od')->nullable();
            $table->text('manifest_refraction_os')->nullable();
            $table->text('cyclo_refraction_od')->nullable();
            $table->text('cyclo_refraction_os')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('operation_id');
            $table->index('examination_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eye_examinations');
    }
};
