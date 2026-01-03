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

        // Redirect if user is not authenticated
        if (!$user) {
            return redirect()->route('login');
        }

        $branchId = $user->branch_id;
        $userRole = $user->getRoleNames()->first() ?? 'guest';

        // Base query with branch filter if user has a branch
        $appointmentQuery = Appointment::query();
        $invoiceQuery = Invoice::query();
        $patientQuery = Patient::query();

        if ($branchId) {
            $appointmentQuery->where('branch_id', $branchId);
            $invoiceQuery->where('branch_id', $branchId);
        }

        // Get base stats
        $baseStats = [
            'total_patients' => $patientQuery->count(),
            'today_appointments' => (clone $appointmentQuery)->whereDate('appointment_date', today())->count(),
            'upcoming_appointments' => (clone $appointmentQuery)
                ->whereDate('appointment_date', '>', today())
                ->where('status', 'scheduled')
                ->count(),
            'total_users' => User::where('is_active', true)->count(),
        ];

        // Customize stats based on role
        $stats = $this->getRoleBasedStats($userRole, $baseStats, $user, $appointmentQuery, $invoiceQuery, $patientQuery);

        // Get role-based data
        $dashboardData = $this->getRoleBasedData($userRole, $user, $appointmentQuery, $invoiceQuery, $patientQuery, $branchId);

        return view('livewire.dashboard', array_merge([
            'stats' => $stats,
            'userRole' => $userRole,
        ], $dashboardData))->layout('components.layouts.app');
    }

    private function getRoleBasedStats($role, $baseStats, $user, $appointmentQuery, $invoiceQuery, $patientQuery)
    {
        $stats = [];

        switch ($role) {
            case 'admin':
                $stats = [
                    'total_patients' => $baseStats['total_patients'],
                    'today_appointments' => $baseStats['today_appointments'],
                    'active_users' => $baseStats['total_users'],
                    'scheduled_operations' => (clone $appointmentQuery)->where('visit_type', 'operation')->whereDate('appointment_date', '>=', today())->count(),
                ];
                break;

            case 'doctor':
                $doctorAppointments = (clone $appointmentQuery)->where('doctor_id', $user->doctor?->id ?? 0);
                $stats = [
                    'my_today_appointments' => (clone $doctorAppointments)->whereDate('appointment_date', today())->count(),
                    'my_upcoming_appointments' => (clone $doctorAppointments)->whereDate('appointment_date', '>', today())->count(),
                    'my_active_patients' => (clone $patientQuery)->whereHas('appointments', function($q) use ($user) {
                        $q->where('doctor_id', $user->doctor?->id ?? 0)->whereDate('appointment_date', '>=', today()->subDays(30));
                    })->count(),
                    'completed_assessments' => (clone $doctorAppointments)->where('status', 'completed')->whereDate('appointment_date', '>=', today()->subDays(30))->count(),
                ];
                break;

            case 'secretary':
            default:
                $stats = [
                    'today_appointments' => $baseStats['today_appointments'],
                    'waiting_patients' => (clone $appointmentQuery)->whereDate('appointment_date', today())->where('status', 'scheduled')->count(),
                ];
                break;
        }

        return $stats;
    }

    private function getRoleBasedData($role, $user, $appointmentQuery, $invoiceQuery, $patientQuery, $branchId)
    {
        $data = [];

        switch ($role) {
            case 'admin':
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
                    ->limit(8)
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

                // Get alerts
                $alerts = $this->getAdminAlerts($appointmentQuery, $invoiceQuery);

                $data = [
                    'recentAppointments' => $recentAppointments,
                    'todayAppointments' => $todayAppointments,
                    'todayQueue' => $todayQueue,
                    'doctors' => $doctors,
                    'alerts' => $alerts,
                ];
                break;

            case 'doctor':
                // Get doctor's today appointments
                $myTodayAppointments = (clone $appointmentQuery)
                    ->with(['patient', 'doctor'])
                    ->where('doctor_id', $user->doctor?->id ?? 0)
                    ->whereDate('appointment_date', today())
                    ->orderBy('appointment_time')
                    ->get();

                // Get recent patients
                $recentPatients = (clone $patientQuery)
                    ->whereHas('appointments', function($q) use ($user) {
                        $q->where('doctor_id', $user->doctor?->id ?? 0);
                    })
                    ->with(['appointments' => function($q) use ($user) {
                        $q->where('doctor_id', $user->doctor?->id ?? 0)->latest()->take(1);
                    }])
                    ->limit(5)
                    ->get()
                    ->map(function($patient) {
                        // Ensure appointments is always a collection
                        $patient->appointments = $patient->appointments ?? collect();
                        return $patient;
                    });

                $data = [
                    'myTodayAppointments' => $myTodayAppointments,
                    'recentPatients' => $recentPatients,
                ];
                break;

            case 'secretary':
            default:
                // Get today's appointments for secretary
                $todayAppointments = (clone $appointmentQuery)
                    ->with(['patient', 'doctor'])
                    ->whereDate('appointment_date', today())
                    ->orderBy('appointment_time')
                    ->get();

                // Get quick actions data
                $quickActions = [
                    'can_create_patients' => $user->can('create.patients'),
                    'can_create_appointments' => $user->can('create.appointments'),
                ];

                $data = [
                    'todayAppointments' => $todayAppointments,
                    'quickActions' => $quickActions,
                ];
                break;
        }

        return $data;
    }

    private function getAdminAlerts($appointmentQuery, $invoiceQuery)
    {
        $alerts = [];

        // Overdue appointments (more than 30 min late)
        $overdueAppointments = (clone $appointmentQuery)
            ->whereDate('appointment_date', today())
            ->where('appointment_time', '<', now()->subMinutes(30))
            ->where('status', 'scheduled')
            ->count();

        if ($overdueAppointments > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'clock',
                'message' => "{$overdueAppointments} appointment(s) are overdue",
                'action' => route('appointments.index'),
            ];
        }

        // Pending invoices older than 7 days
        $oldPendingInvoices = (clone $invoiceQuery)
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subDays(7))
            ->count();

        if ($oldPendingInvoices > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'exclamation-triangle',
                'message' => "{$oldPendingInvoices} invoice(s) pending for more than a week",
                'action' => route('invoices.index'),
            ];
        }

        // Patients without recent appointments
        $inactivePatients = Patient::whereDoesntHave('appointments', function($q) {
            $q->where('appointment_date', '>=', now()->subMonths(3));
        })->count();

        if ($inactivePatients > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'user-clock',
                'message' => "{$inactivePatients} patients haven't visited in 3 months",
                'action' => route('patients.index'),
            ];
        }

        return $alerts;
    }
}
