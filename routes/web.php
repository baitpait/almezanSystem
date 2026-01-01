<?php

use Illuminate\Support\Facades\Route;
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
use App\Livewire\Profile;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

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

    // Profile
    Route::get('/profile', Profile::class)->name('profile');

    // Admin Routes - Only accessible by admins
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', UserManager::class)->name('index');
        });
        Route::prefix('branches')->name('branches.')->group(function () {
            Route::get('/', BranchManager::class)->name('index');
        });
    });

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
