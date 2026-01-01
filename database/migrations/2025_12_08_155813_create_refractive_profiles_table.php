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
        Schema::create('refractive_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');
            
            // Patient Information
            $table->string('patient_name')->nullable();
            $table->integer('patient_age')->nullable();
            $table->string('optometrist')->nullable();
            $table->string('eyeglasses_age')->nullable();
            $table->string('time_with_current_rx')->nullable();
            
            // Current Eyeglasses - OD (Right Eye)
            $table->decimal('current_eyeglasses_od_sphere', 8, 2)->nullable();
            $table->decimal('current_eyeglasses_od_cylinder', 8, 2)->nullable();
            $table->integer('current_eyeglasses_od_axis')->nullable();
            $table->string('current_eyeglasses_od_vision')->nullable();
            
            // Current Eyeglasses - OS (Left Eye)
            $table->decimal('current_eyeglasses_os_sphere', 8, 2)->nullable();
            $table->decimal('current_eyeglasses_os_cylinder', 8, 2)->nullable();
            $table->integer('current_eyeglasses_os_axis')->nullable();
            $table->string('current_eyeglasses_os_vision')->nullable();
            
            // Contact Lenses
            $table->enum('contact_lenses', ['No', 'Soft', 'Hard'])->nullable();
            $table->string('time_without_lenses')->nullable();
            
            // Manifest Refraction - OD
            $table->string('manifest_refraction_od_udva')->nullable();
            $table->decimal('manifest_refraction_od_sphere', 8, 2)->nullable();
            $table->decimal('manifest_refraction_od_cylinder', 8, 2)->nullable();
            $table->integer('manifest_refraction_od_axis')->nullable();
            $table->string('manifest_refraction_od_bscva')->nullable();
            $table->string('manifest_refraction_od_dcnva_40cm')->nullable();
            $table->string('manifest_refraction_od_add_j1')->nullable();
            
            // Manifest Refraction - OS
            $table->string('manifest_refraction_os_udva')->nullable();
            $table->decimal('manifest_refraction_os_sphere', 8, 2)->nullable();
            $table->decimal('manifest_refraction_os_cylinder', 8, 2)->nullable();
            $table->integer('manifest_refraction_os_axis')->nullable();
            $table->string('manifest_refraction_os_bscva')->nullable();
            $table->string('manifest_refraction_os_dcnva_40cm')->nullable();
            $table->string('manifest_refraction_os_add_j1')->nullable();
            
            // Refraction After Dilatation - OD
            $table->decimal('refraction_after_dilation_od_sphere', 8, 2)->nullable();
            $table->decimal('refraction_after_dilation_od_cylinder', 8, 2)->nullable();
            $table->integer('refraction_after_dilation_od_axis')->nullable();
            $table->string('refraction_after_dilation_od_vision')->nullable();
            
            // Refraction After Dilatation - OS
            $table->decimal('refraction_after_dilation_os_sphere', 8, 2)->nullable();
            $table->decimal('refraction_after_dilation_os_cylinder', 8, 2)->nullable();
            $table->integer('refraction_after_dilation_os_axis')->nullable();
            $table->string('refraction_after_dilation_os_vision')->nullable();
            
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
        Schema::dropIfExists('refractive_profiles');
    }
};
