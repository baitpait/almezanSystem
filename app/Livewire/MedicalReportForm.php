<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Component;

class MedicalReportForm extends Component
{
    public $appointmentId;

    public string $procedure_date = '';
    public string $leave_duration = '1_week'; // 1_week | 2_weeks
    public string $report_date = '';
    public string $doctor_name = '';

    public $errorMessage = null;

    public function mount($appointmentId): void
    {
        try {
            $this->appointmentId = (int) $appointmentId;
            if ($this->appointmentId < 1) {
                $this->errorMessage = 'Invalid appointment ID.';
                return;
            }
            $appointment = Appointment::with(['patient', 'doctor', 'operation'])->find($this->appointmentId);
            if (!$appointment) {
                $this->errorMessage = 'Appointment not found.';
                return;
            }
            if ($appointment->visit_type !== 'Operation') {
                $this->errorMessage = 'Medical report is only available for Operation visits.';
                return;
            }
            if (!auth()->user()?->can('view.medical_report')) {
                $this->errorMessage = 'You do not have permission to view medical reports.';
                return;
            }
            $this->procedure_date = $appointment->appointment_date?->format('Y-m-d') ?? now()->format('Y-m-d');
            $this->report_date = $this->procedure_date; // نفس تاريخ العملية
            $this->doctor_name = $appointment->doctor?->name ?? '';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Error: ' . $e->getMessage();
            report($e);
        }
    }

    /** عند تغيير تاريخ العملية يُحدَّث تاريخ إصدار التقرير ليكون نفس التاريخ */
    public function updatedProcedureDate($value): void
    {
        if ($value !== '') {
            $this->report_date = $value;
        }
    }

    public function getAppointmentProperty()
    {
        if (!$this->appointmentId) {
            return null;
        }
        return Appointment::with(['patient', 'doctor', 'operation'])->find($this->appointmentId);
    }

    public function render()
    {
        if ($this->errorMessage) {
            return view('livewire.medical-report-error', [
                'message' => $this->errorMessage,
            ])->layout('components.layouts.app');
        }
        $appointment = $this->appointment;
        if (!$appointment) {
            return view('livewire.medical-report-error', [
                'message' => 'Appointment not found.',
            ])->layout('components.layouts.app');
        }
        return view('livewire.medical-report-form', [
            'appointment' => $appointment,
        ])->layout('components.layouts.app');
    }
}
