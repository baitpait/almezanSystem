<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Operation;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class ScheduledOperations extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $dateFilter = 'today'; // upcoming, today, past, all
    public int $perPage = 15;
    public $selectedOperationId = null;

    public function updatingSearch(): void
    {
        try {
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('ScheduledOperations updatingSearch error: ' . $e->getMessage());
        }
    }

    public function updatingStatusFilter(): void
    {
        try {
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('ScheduledOperations updatingStatusFilter error: ' . $e->getMessage());
        }
    }

    public function updatingDateFilter(): void
    {
        try {
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('ScheduledOperations updatingDateFilter error: ' . $e->getMessage());
        }
    }

    public function updatingPerPage(): void
    {
        try {
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('ScheduledOperations updatingPerPage error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return view('livewire.unauthorized')->layout('components.layouts.app');
            }

            $branchId = $user->branch_id;

            // Show Appointments of type "Operation" (with or without operations)
            $query = Appointment::query()
                ->with(['patient', 'doctor', 'branch', 'operation'])
                ->where('visit_type', 'Operation');
                
            // Apply branch filter (include legacy appointments with branch_id null)
            if ($branchId && !$user->isAdmin()) {
                $query->forBranchAccess((int) $branchId);
            }

            // Doctor users see only their appointments; admin and secretary see all
            $currentDoctor = $user->doctor;
            if ($currentDoctor && !$user->isAdmin() && !$user->hasRole('secretary')) {
                $query->where('doctor_id', $currentDoctor->id);
            }
            
            // Apply search filter
            if (!empty($this->search)) {
                $query->whereHas('patient', function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%')
                      ->orWhere('id_number', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            }
            
            // Apply status filter - now using visit_stage instead of operation.status
            if (!empty($this->statusFilter)) {
                // Map old operation status values to visit_stage values
                $statusMap = [
                    'scheduled' => 'scheduled',
                    'in_progress' => 'in_consultation',
                    'completed' => 'completed',
                    'cancelled' => 'cancelled',
                    'postponed' => 'scheduled', // postponed operations are still scheduled
                ];
                
                $visitStageFilter = $statusMap[$this->statusFilter] ?? $this->statusFilter;
                $query->where('visit_stage', $visitStageFilter);
            }
            
            // Apply date filter - now using visit_stage instead of operation status
            if ($this->dateFilter === 'today') {
                // Show only today's appointments
                $query->whereDate('appointment_date', Carbon::today());
            } elseif ($this->dateFilter === 'upcoming') {
                // Show upcoming appointments (future dates, excluding completed/cancelled)
                $query->whereDate('appointment_date', '>=', Carbon::today())
                      ->whereNotIn('visit_stage', ['completed', 'cancelled']);
            } elseif ($this->dateFilter === 'past') {
                // Show past appointments (past dates or completed/cancelled)
                $query->where(function ($query) {
                    $query->whereDate('appointment_date', '<', Carbon::today())
                          ->orWhereIn('visit_stage', ['completed', 'cancelled']);
                });
            }
            // If dateFilter === 'all', show all appointments (no date filter applied)
            
            $query->orderBy('appointment_date', 'asc')
                  ->orderBy('appointment_time', 'asc')
                  ->orderBy('created_at', 'desc');

            $appointments = $query->paginate($this->perPage);

            return view('livewire.scheduled-operations', [
                'appointments' => $appointments,
                'operations' => collect([]), // Keep for backward compatibility
                'doctors' => Doctor::orderBy('name')->get(),
            ])->layout('components.layouts.app');
        } catch (\Exception $e) {
            \Log::error('ScheduledOperations render error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            session()->flash('error', 'An error occurred while loading the page. Please try again.');
            return view('livewire.scheduled-operations', [
                'appointments' => collect([])->paginate(15),
                'operations' => collect([]),
                'doctors' => collect([]),
            ])->layout('components.layouts.app');
        }
    }

    /**
     * View operation - creates it if it doesn't exist, then redirects to edit page
     * 
     * Business Purpose: When user clicks "View" button in Scheduled Operations page,
     * this method ensures an Operation exists for the appointment and opens it for editing.
     * 
     * @param int $appointmentId The appointment ID to create/view operation for
     */
    public function viewOperation($appointmentId): void
    {
        try {
            $appointment = Appointment::with('operation')->findOrFail($appointmentId);
            
            // Verify appointment type is valid for operations
            if (!in_array($appointment->visit_type, ['Assessment', 'Operation'])) {
                session()->flash('error', 'This appointment type does not support operations.');
                return;
            }

            // If operation doesn't exist, create it
            if (!$appointment->operation_id || !$appointment->operation) {
                $operation = Operation::create([
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    'branch_id' => $appointment->branch_id ?? auth()->user()->branch_id ?? 1,
                    'appointment_id' => $appointment->id,
                    'created_by' => auth()->id(),
                    'status' => 'scheduled',
                    'start_date' => $appointment->appointment_date,
                ]);
                
                // Link appointment to operation
                $appointment->update(['operation_id' => $operation->id]);
                
                $operationId = $operation->id;
            } else {
                $operationId = $appointment->operation_id;
            }

            // Update visit_stage to 'in_consultation' when doctor opens the file
            // Only for the specific patient, not all patients
            if (!in_array($appointment->visit_stage, ['completed', 'cancelled'])) {
                $appointment->update(['visit_stage' => 'in_consultation']);
            }

            // Redirect to operation notes page
            $this->redirect(route('operation-notes.create', ['appointmentId' => $appointment->id]));
        } catch (\Exception $e) {
            \Log::error('ScheduledOperations viewOperation error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Failed to open operation: ' . $e->getMessage());
        }
    }
}

