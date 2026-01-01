<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = config('permissions.modules', []);
        $roles = config('permissions.roles', []);

        // Create all permissions
        $allPermissions = [];
        foreach ($modules as $module => $permissions) {
            foreach ($permissions as $permission) {
                $permissionName = "{$permission}.{$module}";
                $allPermissions[$permissionName] = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['guard_name' => 'web']
                );
            }
        }

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web']
            );

            if ($rolePermissions === 'all') {
                // Assign all permissions to admin
                $role->syncPermissions($allPermissions);
            } elseif (is_array($rolePermissions) && isset($rolePermissions['modules'])) {
                // Secretary: specific modules with specific permissions
                $allowedModules = $rolePermissions['modules'] ?? [];
                $allowedPermissions = $rolePermissions['permissions'] ?? ['view'];
                $permissionsToAssign = [];
                
                foreach ($allowedModules as $module) {
                    if (isset($modules[$module])) {
                        $modulePermissions = $modules[$module];
                        foreach ($allowedPermissions as $permission) {
                            if (in_array($permission, $modulePermissions)) {
                                $permissionName = "{$permission}.{$module}";
                                if (isset($allPermissions[$permissionName])) {
                                    $permissionsToAssign[] = $allPermissions[$permissionName];
                                }
                            }
                        }
                    }
                }
                $role->syncPermissions($permissionsToAssign);
            } else {
                // Doctor: View only for all modules
                $permissionsToAssign = [];
                foreach ($modules as $module => $modulePermissions) {
                    foreach ($rolePermissions as $permission) {
                        if (in_array($permission, $modulePermissions)) {
                            $permissionName = "{$permission}.{$module}";
                            if (isset($allPermissions[$permissionName])) {
                                $permissionsToAssign[] = $allPermissions[$permissionName];
                            }
                        }
                    }
                }
                $role->syncPermissions($permissionsToAssign);
            }
        }

        $this->command->info('Permissions and roles created successfully!');
    }
}
