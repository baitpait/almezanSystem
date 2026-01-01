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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // اسم الخدمة
            $table->text('description')->nullable(); // وصف الخدمة
            $table->enum('service_type', ['doctor', 'center', 'both'])->default('center');
            // doctor: تحسب للطبيب، center: تحسب للمركز، both: يمكن لكلا
            $table->decimal('base_price', 10, 2); // السعر الأساسي
            $table->decimal('center_percentage', 5, 2)->default(100.00); // نسبة المركز
            $table->decimal('doctor_percentage', 5, 2)->default(0.00); // نسبة الطبيب
            $table->boolean('is_active')->default(true);
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->timestamps();

            $table->index(['service_type', 'is_active']);
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
