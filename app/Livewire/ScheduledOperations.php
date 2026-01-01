<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Operation;
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

        $query = Operation::with(['patient', 'doctor', 'branch', 'appointment'])
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
                $q->where('status', $this->statusFilter);
            })
            ->when($this->dateFilter === 'today', function ($q) {
                $q->whereDate('start_date', Carbon::today());
            })
            ->when($this->dateFilter === 'upcoming', function ($q) {
                $q->whereDate('start_date', '>=', Carbon::today())
                  ->where('status', '!=', 'completed')
                  ->where('status', '!=', 'cancelled');
            })
            ->when($this->dateFilter === 'past', function ($q) {
                $q->whereDate('start_date', '<', Carbon::today())
                  ->orWhere(function ($query) {
                      $query->whereIn('status', ['completed', 'cancelled']);
                  });
            })
            ->orderBy('start_date', 'asc')
            ->orderBy('created_at', 'desc');

        $operations = $query->paginate(15);

        return view('livewire.scheduled-operations', [
            'operations' => $operations,
            'doctors' => Doctor::orderBy('name')->get(),
        ])->layout('components.layouts.app');
    }
}

