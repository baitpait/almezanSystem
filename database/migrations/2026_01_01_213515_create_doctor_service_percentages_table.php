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
        Schema::create('doctor_service_percentages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->decimal('doctor_percentage', 5, 2)->default(0.00); // نسبة الطبيب لهذه الخدمة
            $table->decimal('center_percentage', 5, 2)->default(100.00); // نسبة المركز لهذه الخدمة
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['doctor_id', 'service_id']); // كل طبيب خدمة واحدة فقط
            $table->index(['doctor_id', 'is_active']);
            $table->index('service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_service_percentages');
    }
};
