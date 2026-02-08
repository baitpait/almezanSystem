<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\PatientManager;
use App\Livewire\AppointmentManager;
use App\Livewire\InvoiceManager;
use App\Livewire\OperationManager;
use App\Livewire\OperationNoteManager;
use App\Livewire\ScheduledOperations;
use App\Livewire\Admin\UserManager;
use App\Livewire\Admin\BranchManager;
use App\Livewire\Admin\RoleManager;
use App\Livewire\Admin\DoctorManager;
use App\Livewire\Profile;
use App\Livewire\ServiceManager;
use App\Livewire\MedicalReportForm;
use App\Models\Invoice;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard-alt');

    // Patients
    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/', PatientManager::class)->name('index');
    });

    // Appointments
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', AppointmentManager::class)->name('index');
    });

    // Operation Notes (linked to appointments)
    Route::prefix('operation-notes')->name('operation-notes.')->group(function () {
        Route::get('/appointment/{appointmentId}', OperationNoteManager::class)->name('create');
    });

    // Invoices
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', InvoiceManager::class)->name('index');
        Route::get('/{invoice}/print', function (Invoice $invoice) {
            $invoice->load(['patient', 'service', 'branch']);
            return view('invoices.print', ['invoice' => $invoice]);
        })->name('print');
    });

    // Services
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', ServiceManager::class)->name('index');
    });

    // Operations
    Route::prefix('operations')->name('operations.')->group(function () {
        Route::get('/', OperationManager::class)->name('index');
        Route::get('/create', OperationManager::class)->name('create');
        Route::get('/{id}/edit', OperationManager::class)->name('edit');
    });

    // Scheduled Operations
    Route::prefix('scheduled-operations')->name('scheduled-operations.')->group(function () {
        Route::get('/', ScheduledOperations::class)->name('index');
    });

    // Medical Report (only for Operation visits; permission: view.medical_report)
    Route::get('/medical-report/{appointmentId}', MedicalReportForm::class)->name('medical-report.form');

    // Profile
    Route::get('/profile', Profile::class)->name('profile');

    // Admin Routes - Only accessible by admins
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', UserManager::class)->name('index');
        });
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', RoleManager::class)->name('index');
        });
        Route::prefix('doctors')->name('doctors.')->group(function () {
            Route::get('/', DoctorManager::class)->name('index');
        });
        Route::prefix('branches')->name('branches.')->group(function () {
            Route::get('/', BranchManager::class)->name('index');
        });
    });

    // Database Backup Download (Admin Only)
    Route::get('/database/backup', function () {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            $filename = 'database_backup_' . date('Y-m-d_His') . '.sql';
            $backupDir = storage_path('app/backups');
            $filepath = $backupDir . '/' . $filename;

            // Create backups directory if it doesn't exist
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Build mysqldump command
            $command = sprintf(
                'mysqldump -h %s -P %s -u %s %s %s --single-transaction --routines --triggers --events > %s 2>&1',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                !empty($password) ? '-p' . escapeshellarg($password) : '',
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            // Execute mysqldump
            exec($command, $output, $returnVar);

            if ($returnVar !== 0 || !file_exists($filepath) || filesize($filepath) === 0) {
                $errorMessage = !empty($output) ? implode("\n", $output) : 'Unknown error';
                \Log::error('Database backup failed', [
                    'return_var' => $returnVar,
                    'output' => $errorMessage,
                    'command' => str_replace($password, '***', $command)
                ]);
                return back()->with('error', 'Failed to create database backup. Please contact technical support.');
            }

            // Return file download
            return response()->download($filepath, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('Database backup exception', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while creating the backup: ' . $e->getMessage());
        }
    })->name('database.backup');

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
