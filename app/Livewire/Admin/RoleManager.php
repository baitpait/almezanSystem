<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManager extends Component
{
    protected $listeners = ['refreshPermissions' => '$refresh'];
    
    public string $activeRole = 'admin';

    /** @var array<string, string> */
    public array $roleLabels = [
        'admin' => 'Admin',
        'doctor' => 'Doctor',
        'secretary' => 'Secretary',
    ];

    /** @var array<string, array<string, array<string, bool>>> */
    public array $permissions = [];

    /** @var array<string, string> */
    public array $actions = [
        'view' => 'View',
        'create' => 'Create',
        'update' => 'Update',
        'delete' => 'Delete',
        'print' => 'Print',
    ];

    public function mount(): void
    {
        $this->loadPermissionsFromDatabase();
    }

    protected function loadPermissionsFromDatabase(): void
    {
        $modules = config('permissions.modules', []);
        $moduleLabels = config('permissions.module_labels', []);

        // Initialize permissions array
        foreach ($moduleLabels as $moduleKey => $moduleLabel) {
            foreach ($this->roleLabels as $roleKey => $roleLabel) {
                foreach ($this->actions as $actionKey => $actionLabel) {
                    $this->permissions[$moduleLabel][$roleKey][$actionKey] = false;
                }
            }
        }

        // Load actual permissions from database
        foreach ($this->roleLabels as $roleKey => $roleLabel) {
            $role = Role::where('name', $roleKey)->first();
            if ($role) {
                $rolePermissions = $role->permissions->pluck('name')->toArray();
                
                foreach ($modules as $moduleKey => $modulePermissions) {
                    $moduleLabel = $moduleLabels[$moduleKey] ?? $moduleKey;
                    
                    foreach ($modulePermissions as $permission) {
                        $permissionName = "{$permission}.{$moduleKey}";
                        if (in_array($permissionName, $rolePermissions)) {
                            $this->permissions[$moduleLabel][$roleKey][$permission] = true;
                        }
                    }
                }
            }
        }
    }

    public function setRole(string $role): void
    {
        if (!isset($this->roleLabels[$role])) {
            return;
        }
        $this->activeRole = $role;
    }

    public function toggle(string $module, string $action): void
    {
        if (!isset($this->permissions[$module][$this->activeRole][$action])) {
            return;
        }
        $this->permissions[$module][$this->activeRole][$action] = ! $this->permissions[$module][$this->activeRole][$action];
    }

    public function setPreset(string $preset): void
    {
        foreach ($this->permissions as $module => $roles) {
            if ($preset === 'full') {
                $this->permissions[$module][$this->activeRole] = [
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                    'print' => true,
                ];
            } elseif ($preset === 'view') {
                $this->permissions[$module][$this->activeRole] = [
                    'view' => true,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                    'print' => false,
                ];
            }
        }
    }

    public function save(): void
    {
        try {
            $modules = config('permissions.modules', []);
            $moduleLabels = config('permissions.module_labels', []);
            
            $role = Role::where('name', $this->activeRole)->first();
            if (!$role) {
                session()->flash('error', 'Role not found.');
                return;
            }

            $permissionsToSync = [];
            $missingPermissions = [];
            
            foreach ($moduleLabels as $moduleKey => $moduleLabel) {
                if (!isset($this->permissions[$moduleLabel][$this->activeRole])) {
                    continue;
                }
                
                $modulePermissions = $modules[$moduleKey] ?? [];
                
                foreach ($modulePermissions as $permission) {
                    if (isset($this->permissions[$moduleLabel][$this->activeRole][$permission]) 
                        && $this->permissions[$moduleLabel][$this->activeRole][$permission]) {
                        $permissionName = "{$permission}.{$moduleKey}";
                        $permissionModel = Permission::where('name', $permissionName)->first();
                        
                        if ($permissionModel) {
                            $permissionsToSync[] = $permissionModel;
                        } else {
                            // Create missing permission
                            $permissionModel = Permission::create([
                                'name' => $permissionName,
                                'guard_name' => 'web',
                            ]);
                            $permissionsToSync[] = $permissionModel;
                        }
                    }
                }
            }

            // Sync permissions
            if (empty($permissionsToSync)) {
                session()->flash('error', 'No permissions selected to save.');
                return;
            }
            
            $role->syncPermissions($permissionsToSync);
            
            // Clear cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            // Reload permissions from database - this will update $this->permissions
            $this->loadPermissionsFromDatabase();
            
            session()->flash('message', 'Permissions saved successfully! (' . count($permissionsToSync) . ' permissions)');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save permissions: ' . $e->getMessage());
            \Log::error('Failed to save permissions', [
                'role' => $this->activeRole,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function render()
    {
        $moduleLabels = config('permissions.module_labels', []);
        return view('livewire.admin.role-manager', [
            'modules' => array_values($moduleLabels),
        ])->layout('components.layouts.app');
    }
}

