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
    public string $dateFilter = 'upcoming'; // upcoming, today, past, all
    public $selectedOperationId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $branchId = $user?->branch_id;

        // Show Appointments of type "Operation" (with or without operations)
        $query = Appointment::with(['patient', 'doctor', 'branch', 'operation'])
            ->where('visit_type', 'Operation')
            ->when($branchId && !$user->isAdmin(), function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->whereHas('patient', function ($q) {
                        $q->where('full_name', 'like', '%' . $this->search . '%')
                            ->orWhere('id_number', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->statusFilter, function ($q) {
                // Filter by operation status if operation exists
                // If no operation exists, show only if status filter is 'scheduled'
                $q->where(function ($query) {
                    $query->whereHas('operation', function ($q) {
                        $q->where('status', $this->statusFilter);
                    });
                    // Include appointments without operation only for 'scheduled' status
                    if ($this->statusFilter === 'scheduled') {
                        $query->orWhereNull('operation_id')
                              ->orWhereDoesntHave('operation');
                    }
                });
            })
            ->when($this->dateFilter === 'today', function ($q) {
                $q->whereDate('appointment_date', Carbon::today());
            })
            ->when($this->dateFilter === 'upcoming', function ($q) {
                $q->whereDate('appointment_date', '>=', Carbon::today())
                  ->where(function ($query) {
                      // Include appointments without operation OR with operation not completed/cancelled
                      $query->whereNull('operation_id')
                            ->orWhereDoesntHave('operation')
                            ->orWhereHas('operation', function ($q) {
                                $q->where('status', '!=', 'completed')
                                  ->where('status', '!=', 'cancelled');
                            });
                  });
            })
            ->when($this->dateFilter === 'past', function ($q) {
                $q->where(function ($query) {
                    $query->whereDate('appointment_date', '<', Carbon::today())
                          ->orWhereHas('operation', function ($q) {
                              $q->whereIn('status', ['completed', 'cancelled']);
                          });
                });
            })
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->orderBy('created_at', 'desc');

        $appointments = $query->paginate(15);

        return view('livewire.scheduled-operations', [
            'appointments' => $appointments,
            'operations' => collect([]), // Keep for backward compatibility
            'doctors' => Doctor::orderBy('name')->get(),
        ])->layout('components.layouts.app');
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
                'branch_id' => $appointment->branch_id,
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

        // Redirect to operation notes page
        $this->redirect(route('operation-notes.create', ['appointmentId' => $appointment->id]));
    }
}

