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
            // If user has a role in the old 'role' column
            if ($user->role) {
                // Find the Role in Spatie
                $role = Role::where('name', $user->role)->first();
                
                if ($role) {
                    // Assign role to user (if not already assigned)
                    if (!$user->hasRole($role->name)) {
                        $user->assignRole($role);
                        $assigned++;
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
                $this->command->line("  User '{$user->name}' has no role to assign");
            }
        }

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info("\nCompleted!");
        $this->command->info("Assigned: {$assigned} users");
        $this->command->info("Skipped: {$skipped} users");
    }
}
