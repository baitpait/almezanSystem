<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearOldOperationsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * هذا Seeder يحذف جميع البيانات القديمة المتعلقة بالعمليات
     * للبدء من جديد
     */
    public function run(): void
    {
        $this->command->info('Starting to clear old operations data...');
        $this->command->warn('⚠️  This will DELETE ALL operations and related data!');

        // تعطيل فحص Foreign Key مؤقتاً
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // حذف الجداول المرتبطة بالعمليات (بالترتيب الصحيح)
            $tables = [
                'operation_files',
                'operation_notes',
                'ectasia_risk_assessments',
                'eye_examinations',
                'medical_histories',
                'refractive_profiles',
                'operation_details',
                'operation_approvals',
                'invoices', // قد تحتوي على operation_id
            ];

            foreach ($tables as $table) {
                $count = DB::table($table)->count();
                if ($count > 0) {
                    DB::table($table)->truncate();
                    $this->command->info("✓ Cleared {$count} records from {$table}");
                } else {
                    $this->command->line("  {$table} is already empty");
                }
            }

            // حذف العمليات
            $operationsCount = DB::table('operations')->count();
            if ($operationsCount > 0) {
                DB::table('operations')->truncate();
                $this->command->info("✓ Cleared {$operationsCount} operations");
            } else {
                $this->command->line("  operations table is already empty");
            }

            // مسح operation_id من appointments
            $appointmentsUpdated = DB::table('appointments')
                ->whereNotNull('operation_id')
                ->update(['operation_id' => null]);
            
            if ($appointmentsUpdated > 0) {
                $this->command->info("✓ Cleared operation_id from {$appointmentsUpdated} appointments");
            } else {
                $this->command->line("  No appointments with operation_id found");
            }

            $this->command->info("\n✅ All old operations data has been cleared successfully!");
            $this->command->info("You can now start fresh with new operations.");

        } catch (\Exception $e) {
            $this->command->error("❌ Error occurred: " . $e->getMessage());
            throw $e;
        } finally {
            // إعادة تفعيل فحص Foreign Key
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
