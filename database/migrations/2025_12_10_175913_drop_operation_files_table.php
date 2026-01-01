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
        Schema::dropIfExists('operation_files');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('operation_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            
            $table->string('file_name');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type')->nullable()->comment('image, document, pdf, etc.');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable()->comment('Size in bytes');
            $table->enum('file_category', [
                'refractive_profile',
                'medical_history',
                'eye_examination',
                'pre_op_photo',
                'post_op_photo',
                'topography',
                'pachymetry',
                'other'
            ])->default('other');
            
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('operation_id');
            $table->index('file_category');
        });
    }
};
