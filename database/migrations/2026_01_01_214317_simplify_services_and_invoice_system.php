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
        // إزالة جدول نسب الأطباء لأنه غير مطلوب
        Schema::dropIfExists('doctor_service_percentages');

        // إعادة تصميم جدول الخدمات ليصبح بسيط
        Schema::table('services', function (Blueprint $table) {
            // إزالة الأعمدة المعقدة
            $table->dropColumn([
                'service_type',
                'center_percentage',
                'doctor_percentage'
            ]);

            // إزالة foreign key constraint أولاً
            if (Schema::hasColumn('services', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }

            // إضافة عمود للفئة (اختياري)
            $table->string('category')->nullable()->after('description');
        });

        // إعادة تصميم جدول تفاصيل الفاتورة
        Schema::table('invoice_services', function (Blueprint $table) {
            // إزالة الأعمدة المعقدة
            $table->dropColumn([
                'doctor_percentage',
                'center_percentage',
                'doctor_amount',
                'center_amount'
            ]);

            // تبسيط الكمية (default = 1)
            $table->integer('quantity')->default(1)->change();

            // إضافة ملاحظات اختيارية
            if (!Schema::hasColumn('invoice_services', 'notes')) {
                $table->text('notes')->nullable()->after('total_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إعادة إنشاء جدول نسب الأطباء
        Schema::create('doctor_service_percentages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->decimal('doctor_percentage', 5, 2)->default(0.00);
            $table->decimal('center_percentage', 5, 2)->default(100.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['doctor_id', 'service_id']);
            $table->index(['doctor_id', 'is_active']);
            $table->index('service_id');
        });

        // إعادة الأعمدة المعقدة لجدول الخدمات
        Schema::table('services', function (Blueprint $table) {
            $table->enum('service_type', ['doctor', 'center', 'both'])->default('center');
            $table->decimal('center_percentage', 5, 2)->default(100.00);
            $table->decimal('doctor_percentage', 5, 2)->default(0.00);
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->dropColumn('category');
        });

        // إعادة الأعمدة المعقدة لجدول تفاصيل الفاتورة
        Schema::table('invoice_services', function (Blueprint $table) {
            $table->decimal('doctor_percentage', 5, 2)->default(0.00);
            $table->decimal('center_percentage', 5, 2)->default(100.00);
            $table->decimal('doctor_amount', 10, 2)->default(0.00);
            $table->decimal('center_amount', 10, 2)->default(0.00);
            $table->dropColumn('notes');
        });
    }
};
