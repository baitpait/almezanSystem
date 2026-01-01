<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class MigrateOldRolesToSpatieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * هذا Seeder يحول الأدوار القديمة (من عمود role) إلى Spatie Roles
     */
    public function run(): void
    {
        $this->command->info('Starting migration of old roles to Spatie Permission...');

        // الحصول على جميع المستخدمين
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found to migrate.');
            return;
        }

        $migrated = 0;
        $skipped = 0;

        foreach ($users as $user) {
            // إذا كان المستخدم لديه role في العمود القديم
            if ($user->role) {
                // البحث عن Role في Spatie
                $role = Role::where('name', $user->role)->first();
                
                if ($role) {
                    // ربط المستخدم بالدور (إذا لم يكن مربوطاً بالفعل)
                    if (!$user->hasRole($role->name)) {
                        $user->assignRole($role);
                        $migrated++;
                        $this->command->info("✓ User '{$user->name}' assigned to role '{$role->name}'");
                    } else {
                        $skipped++;
                        $this->command->line("  User '{$user->name}' already has role '{$role->name}'");
                    }
                } else {
                    $this->command->warn("  Role '{$user->role}' not found in Spatie for user '{$user->name}'");
                }
            } else {
                $skipped++;
                $this->command->line("  User '{$user->name}' has no role to migrate");
            }
        }

        $this->command->info("\nMigration completed!");
        $this->command->info("Migrated: {$migrated} users");
        $this->command->info("Skipped: {$skipped} users");
    }
}
