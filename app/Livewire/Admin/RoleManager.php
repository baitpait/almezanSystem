<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManager extends Component
{
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
        $modules = config('permissions.modules', []);
        $moduleLabels = config('permissions.module_labels', []);
        
        $role = Role::where('name', $this->activeRole)->first();
        if (!$role) {
            session()->flash('error', 'Role not found.');
            return;
        }

        $permissionsToSync = [];
        
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
                    }
                }
            }
        }

        $role->syncPermissions($permissionsToSync);
        
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        session()->flash('message', 'Permissions saved successfully!');
    }

    public function render()
    {
        $moduleLabels = config('permissions.module_labels', []);
        return view('livewire.admin.role-manager', [
            'modules' => array_values($moduleLabels),
        ])->layout('components.layouts.app');
    }
}

