<?php

return [
    /*
    |--------------------------------------------------------------------------
    | System Permissions Configuration
    |--------------------------------------------------------------------------
    |
    | This file defines all modules and their available permissions.
    | When you add a new module, just add it here and run:
    | php artisan db:seed --class=PermissionSeeder
    |
    */

    'modules' => [
        'users' => ['view', 'create', 'update', 'delete'],
        'branches' => ['view', 'create', 'update', 'delete'],
        'patients' => ['view', 'create', 'update', 'delete'],
        'appointments' => ['view', 'create', 'update', 'delete'],
        'services' => ['view', 'create', 'update', 'delete'],
        'invoices' => ['view', 'create', 'update', 'delete', 'print'],
        'assessment' => ['view', 'create', 'update', 'delete'],
        'operations' => ['view', 'create', 'update', 'delete'],
        'doctors' => ['view', 'create', 'update', 'delete'],
        'medical_report' => ['view', 'create'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Role Permissions
    |--------------------------------------------------------------------------
    |
    | Define default permissions for each role.
    | 'all' means all permissions for that role.
    | Array means specific permissions only.
    |
    */
    'roles' => [
        'admin' => 'all', // All permissions
        'doctor' => ['view'], // View only for all modules
        'secretary' => [
            'modules' => ['patients', 'appointments', 'services', 'invoices', 'assessment', 'operations', 'medical_report'], // Only these modules
            'permissions' => ['view', 'create'], // View and create (e.g. issue report)
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Labels
    |--------------------------------------------------------------------------
    |
    | Human-readable labels for permissions.
    |
    */
    'labels' => [
        'view' => 'View',
        'create' => 'Create',
        'update' => 'Update',
        'delete' => 'Delete',
        'print' => 'Print',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Labels
    |--------------------------------------------------------------------------
    |
    | Human-readable labels for modules.
    |
    */
    'module_labels' => [
        'users' => 'User Management',
        'branches' => 'Branch Management',
        'patients' => 'Patient Management',
        'appointments' => 'Appointment Management',
        'services' => 'Service Management',
        'invoices' => 'Invoice Management',
        'assessment' => 'Assessment',
        'operations' => 'Operations',
        'doctors' => 'Doctor Management',
        'medical_report' => 'Medical Report',
    ],
];

