<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;
use App\Models\Doctor;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $branchId = $user->branch_id;

        // Base query with branch filter if user has a branch
        $appointmentQuery = Appointment::query();
        $invoiceQuery = Invoice::query();
        $patientQuery = Patient::query();

        if ($branchId) {
            $appointmentQuery->where('branch_id', $branchId);
            $invoiceQuery->where('branch_id', $branchId);
        }

        $stats = [
            'total_patients' => $patientQuery->count(),
            'today_appointments' => (clone $appointmentQuery)->whereDate('appointment_date', today())->count(),
            'upcoming_appointments' => (clone $appointmentQuery)
                ->whereDate('appointment_date', '>', today())
                ->where('status', 'scheduled')
                ->count(),
            'total_users' => User::where('is_active', true)->count(),
            'total_invoices' => (clone $invoiceQuery)->count(),
            'pending_invoices' => (clone $invoiceQuery)->where('status', 'pending')->count(),
            'paid_invoices' => (clone $invoiceQuery)->where('status', 'paid')->count(),
            'total_revenue' => (clone $invoiceQuery)->where('status', 'paid')->sum('total_amount'),
            'pending_revenue' => (clone $invoiceQuery)->whereIn('status', ['pending', 'partial'])->sum('remaining_amount'),
        ];

        // Get today's appointments grouped by doctor
        $todayAppointments = (clone $appointmentQuery)
            ->with(['patient', 'doctor'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get()
            ->groupBy('doctor_id');

        // Get recent appointments for the table
        $recentAppointments = (clone $appointmentQuery)
            ->with(['patient', 'doctor'])
            ->whereDate('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(10)
            ->get();

        // Get today's patient queue
        $todayQueue = (clone $appointmentQuery)
            ->with(['patient', 'doctor'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        // Get active doctors
        $doctors = Doctor::when($branchId, function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->get();

        return view('livewire.dashboard', [
            'stats' => $stats,
            'recentAppointments' => $recentAppointments,
            'todayAppointments' => $todayAppointments,
            'todayQueue' => $todayQueue,
            'doctors' => $doctors,
        ])->layout('components.layouts.app');
    }
}
