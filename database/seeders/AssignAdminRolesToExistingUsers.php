<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignAdminRolesToExistingUsers extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder assigns Spatie roles to existing users based on their 'role' column.
     */
    public function run(): void
    {
        $this->command->info('Assigning Spatie roles to existing users...');

        // Get all users
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found.');
            return;
        }

        $assigned = 0;
        $skipped = 0;

        foreach ($users as $user) {
            // مزامنة دور Spatie مع عمود role لجميع أنواع المستخدمين (admin, doctor, secretary)
            if ($user->role) {
                $role = Role::where('name', $user->role)->first();
                if ($role) {
                    $user->syncRoles([$role->name]);
                    $assigned++;
                    $this->command->info("✓ User '{$user->name}' synced to role '{$role->name}'");
                } else {
                    $this->command->warn("  Role '{$user->role}' not found in Spatie for user '{$user->name}'");
                }
            } else {
                $skipped++;
                $this->command->line("  User '{$user->name}' has no role in database");
            }
        }

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info("\nCompleted!");
        $this->command->info("Assigned: {$assigned} users");
        $this->command->info("Skipped: {$skipped} users");
    }
}
