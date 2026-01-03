<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * مسح جميع البيانات غير المهمة والاحتفاظ فقط بالبيانات الأساسية:
     * - Users (المستخدمين)
     * - Roles & Permissions (الأدوار والصلاحيات)
     * - Branches (الفروع)
     * - Doctors (الأطباء)
     * - Services (الخدمات)
     */
    public function up(): void
    {
        // مسح جميع المواعيد
        DB::table('appointments')->delete();

        // مسح جميع الفواتير وتفاصيلها
        DB::table('invoice_services')->delete();
        DB::table('invoices')->delete();

        // مسح جميع المرضى
        DB::table('patients')->delete();

        // مسح جميع بيانات العمليات
        DB::table('operation_files')->delete();
        DB::table('operation_approvals')->delete();
        DB::table('operation_details')->delete();
        DB::table('ectasia_risk_assessments')->delete();
        DB::table('eye_examinations')->delete();
        DB::table('medical_histories')->delete();
        DB::table('refractive_profiles')->delete();
        DB::table('procedures')->delete();
        DB::table('operation_notes')->delete();
        DB::table('operations')->delete();

        // مسح الفئات (إذا كانت غير مهمة)
        DB::table('categories')->delete();

        // إعادة تعيين auto-increment counters للجداول الممسوحة
        $this->resetAutoIncrement([
            'patients',
            'appointments',
            'invoices',
            'invoice_services',
            'operations',
            'operation_notes',
            'operation_files',
            'operation_approvals',
            'operation_details',
            'ectasia_risk_assessments',
            'eye_examinations',
            'medical_histories',
            'refractive_profiles',
            'procedures',
            'categories'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // لا يمكن التراجع عن مسح البيانات
        // يمكن استعادة البيانات من backup إذا كان متوفراً
    }

    /**
     * إعادة تعيين auto-increment counters
     */
    private function resetAutoIncrement(array $tables): void
    {
        foreach ($tables as $table) {
            // إعادة تعيين AUTO_INCREMENT إلى 1
            DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = 1");
        }
    }
};
