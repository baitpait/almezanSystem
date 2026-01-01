<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // إنشاء الصلاحيات (Permissions)
        $permissions = [
            // Patients - المرضى
            'view-patients',
            'create-patients',
            'edit-patients',
            'delete-patients',
            
            // Appointments - المواعيد
            'view-appointments',
            'create-appointments',
            'edit-appointments',
            'delete-appointments',
            
            // Operations - العمليات
            'view-operations',
            'create-operations',
            'edit-operations',
            'delete-operations',
            
            // Invoices - الفواتير
            'view-invoices',
            'create-invoices',
            'edit-invoices',
            'delete-invoices',
            
            // Operation Notes - ملاحظات العمليات
            'view-operation-notes',
            'create-operation-notes',
            'edit-operation-notes',
            'delete-operation-notes',
            
            // Admin - إدارة النظام
            'manage-users',
            'manage-branches',
            'manage-settings',
            'view-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // إنشاء الأدوار (Roles)
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $doctor = Role::firstOrCreate(['name' => 'doctor']);
        $secretary = Role::firstOrCreate(['name' => 'secretary']);

        // ربط الصلاحيات بالأدوار
        
        // 1. Admin (مسؤول النظام) - جميع الصلاحيات
        $admin->syncPermissions(Permission::all());

        // 2. Doctor (الطبيب) - صلاحيات محددة
        $doctor->syncPermissions([
            // Patients
            'view-patients',
            'create-patients',
            'edit-patients',
            // لا يمكن حذف المرضى
            
            // Appointments
            'view-appointments',
            'create-appointments',
            'edit-appointments',
            // لا يمكن حذف المواعيد
            
            // Operations - جميع الصلاحيات
            'view-operations',
            'create-operations',
            'edit-operations',
            'delete-operations',
            
            // Operation Notes
            'view-operation-notes',
            'create-operation-notes',
            'edit-operation-notes',
            'delete-operation-notes',
            
            // Invoices - فقط المشاهدة
            'view-invoices',
            // لا يمكن إنشاء أو تعديل الفواتير
        ]);

        // 3. Secretary (السكرتيرة) - صلاحيات محددة
        $secretary->syncPermissions([
            // Patients - جميع الصلاحيات
            'view-patients',
            'create-patients',
            'edit-patients',
            'delete-patients',
            
            // Appointments - جميع الصلاحيات
            'view-appointments',
            'create-appointments',
            'edit-appointments',
            'delete-appointments',
            
            // Invoices - جميع الصلاحيات
            'view-invoices',
            'create-invoices',
            'edit-invoices',
            'delete-invoices',
            
            // Operations - لا يمكن رؤية أو إدارة العمليات
            // Operation Notes - لا يمكن رؤية أو إدارة ملاحظات العمليات
        ]);

        $this->command->info('Roles and Permissions created successfully!');
        $this->command->info('Roles: admin, doctor, secretary');
        $this->command->info('Permissions: ' . count($permissions) . ' permissions created');
    }
}
