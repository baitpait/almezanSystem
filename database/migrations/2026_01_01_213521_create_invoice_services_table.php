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
        Schema::create('invoice_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->integer('quantity')->default(1); // الكمية
            $table->decimal('unit_price', 10, 2); // سعر الوحدة
            $table->decimal('total_price', 10, 2); // السعر الإجمالي
            $table->decimal('doctor_percentage', 5, 2)->default(0.00); // نسبة الطبيب المستخدمة
            $table->decimal('center_percentage', 5, 2)->default(100.00); // نسبة المركز المستخدمة
            $table->decimal('doctor_amount', 10, 2)->default(0.00); // مبلغ الطبيب
            $table->decimal('center_amount', 10, 2)->default(0.00); // مبلغ المركز
            $table->text('notes')->nullable(); // ملاحظات لهذه الخدمة
            $table->timestamps();

            $table->index(['invoice_id', 'service_id']);
            $table->index('doctor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_services');
    }
};
