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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            // Basic Information
            $table->enum('operation_type', [
                'LASIK',
                'Femto-LASIK',
                'PRK',
                'Trans-PRK',
                'SMILE',
                'PTK',
                'Topography Guided',
                'Presbyopia',
                'Other'
            ])->default('LASIK');

            $table->enum('operation_eye', ['OD', 'OS', 'OU'])->default('OU');
            $table->decimal('cost', 10, 2)->default(0.00);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', [
                'scheduled',
                'in_progress',
                'completed',
                'cancelled',
                'postponed'
            ])->default('scheduled');

            // Pre-Operation Assessment
            $table->date('pre_op_assessment_date')->nullable();

            // Post-Operation Information
            $table->text('post_op_notes')->nullable();
            $table->boolean('dry_eye_treatment')->default(false);
            $table->text('dry_eye_follow_up')->nullable();
            $table->boolean('corneal_warpage')->default(false);
            $table->text('corneal_warpage_follow_up')->nullable();
            $table->text('presbyopia_profile')->nullable();

            // Additional Information
            $table->text('recommendation_notes')->nullable();
            $table->boolean('patient_teaching_completed')->default(false);
            $table->text('diagnosis')->nullable();
            $table->text('plan')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('branch_id');
            $table->index('operation_type');
            $table->index('status');
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
