<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Appointment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Gate for admin access
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });

        // Share Assessment count to sidebar
        View::composer(['components.layouts.app', 'livewire.*'], function ($view) {
            $assessmentCount = 0;
            
            if (auth()->check()) {
                try {
                    $user = auth()->user();
                    $query = Appointment::where('visit_type', 'Assessment')
                        ->whereDate('appointment_date', '>=', today());

                    // Filter by branch if user has a branch
                    if ($user->branch_id) {
                        $query->where('branch_id', $user->branch_id);
                    }

                    // Filter by doctor if user is a doctor
                    if ($user->isDoctor() && $user->doctor) {
                        $query->where('doctor_id', $user->doctor->id);
                    }

                    $assessmentCount = $query->count();
                } catch (\Exception $e) {
                    // Log error but don't break the view
                    \Log::error('Error calculating assessment count: ' . $e->getMessage());
                }
            }

            $view->with('assessmentCount', $assessmentCount);
        });
    }
}
