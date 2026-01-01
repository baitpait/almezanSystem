<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Operation;

class MigrateOldOperationTypeToSeparateFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * هذا Seeder يحول operation_type القديم إلى operation_type_od و operation_type_os
     */
    public function run(): void
    {
        $this->command->info('Starting migration of old operation_type to separate OD/OS fields...');

        // الحصول على جميع العمليات
        $operations = Operation::whereNotNull('operation_type')->get();

        if ($operations->isEmpty()) {
            $this->command->warn('No operations found to migrate.');
            return;
        }

        $migrated = 0;
        $skipped = 0;

        foreach ($operations as $operation) {
            // إذا كان operation_eye = OU و operation_type موجود
            if ($operation->operation_eye === 'OU' && !empty($operation->operation_type)) {
                // نسخ operation_type إلى operation_type_od و operation_type_os إذا كانت فارغة
                if (empty($operation->operation_type_od)) {
                    $operation->operation_type_od = $operation->operation_type;
                }
                if (empty($operation->operation_type_os)) {
                    $operation->operation_type_os = $operation->operation_type;
                }
                $operation->save();
                $migrated++;
                $this->command->info("✓ Operation #{$operation->id} migrated: operation_type '{$operation->operation_type}' → OD/OS");
            } 
            // إذا كان operation_eye = OD
            elseif ($operation->operation_eye === 'OD' && !empty($operation->operation_type)) {
                if (empty($operation->operation_type_od)) {
                    $operation->operation_type_od = $operation->operation_type;
                    $operation->save();
                    $migrated++;
                    $this->command->info("✓ Operation #{$operation->id} migrated: operation_type '{$operation->operation_type}' → OD");
                } else {
                    $skipped++;
                }
            }
            // إذا كان operation_eye = OS
            elseif ($operation->operation_eye === 'OS' && !empty($operation->operation_type)) {
                if (empty($operation->operation_type_os)) {
                    $operation->operation_type_os = $operation->operation_type;
                    $operation->save();
                    $migrated++;
                    $this->command->info("✓ Operation #{$operation->id} migrated: operation_type '{$operation->operation_type}' → OS");
                } else {
                    $skipped++;
                }
            } else {
                $skipped++;
                $this->command->line("  Operation #{$operation->id} skipped (no operation_type or already migrated)");
            }
        }

        $this->command->info("\nMigration completed!");
        $this->command->info("Migrated: {$migrated} operations");
        $this->command->info("Skipped: {$skipped} operations");
    }
}
