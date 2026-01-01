<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Patient;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class PatientManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public $editingId = null;
    public $showModal = false;
    public $showDetailsModal = false;
    public $selectedPatientId = null;
    public int $perPage = 10;
    public $openDropdownId = null;
    public array $form = [
        'full_name' => '',
        'id_number' => '',
        'date_of_birth' => '',
        'gender' => '',
        'phone' => '',
        'phone_secondary' => '',
        'city' => '',
        'occupation' => '',
        'notes' => '',
    ];

    protected function rules(): array
    {
        return [
            'form.full_name' => 'required|string|max:255',
            'form.id_number' => 'required|string|max:50|unique:patients,id_number,'.$this->editingId,
            'form.date_of_birth' => 'required|date',
            'form.gender' => 'required|in:male,female',
            'form.phone' => [
                'required',
                'string',
                'max:32',
                'regex:/^[\d٠١٢٣٤٥٦٧٨٩+\-\s()]+$/u',
            ],
            'form.phone_secondary' => [
                'nullable',
                'string',
                'max:32',
                'regex:/^[\d٠١٢٣٤٥٦٧٨٩+\-\s()]+$/u',
                'different:form.phone',
            ],
            'form.city' => 'required|string|max:255',
            'form.occupation' => 'required|string|max:255',
            'form.notes' => 'nullable|string|max:1000',
        ];
    }

    protected function messages(): array
    {
        return [
            'form.phone_secondary.different' => 'The secondary phone number must be different from the primary phone number.',
        ];
    }

    // Reset the form for create/edit views
    public function resetForm(): void
    {
        $this->form = [
            'full_name' => '',
            'id_number' => '',
            'date_of_birth' => '',
            'gender' => '',
            'phone' => '',
            'phone_secondary' => '',
            'city' => '',
            'occupation' => '',
            'notes' => '',
        ];
        $this->editingId = null;
        $this->showModal = false;
    }

    public function create(): void
    {
        if (!auth()->user()->can('create.patients')) {
            session()->flash('error', 'You do not have permission to create patients.');
            return;
        }
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id): void
    {
        if (!auth()->user()->can('update.patients')) {
            session()->flash('error', 'You do not have permission to update patients.');
            return;
        }
        
        // Close details modal if open
        if ($this->showDetailsModal) {
            $this->closeDetailsModal();
        }

        $patient = Patient::findOrFail($id);
        $this->editingId = $patient->id;
        $this->form = [
            'full_name' => $patient->full_name,
            'id_number' => $patient->id_number,
            'date_of_birth' => $patient->date_of_birth,
            'gender' => $patient->gender,
            'phone' => $patient->phone,
            'phone_secondary' => $patient->phone_secondary,
            'city' => $patient->city,
            'occupation' => $patient->occupation,
            'notes' => $patient->notes,
        ];
        $this->showModal = true;
    }

    public function save(): void
    {
        if ($this->editingId) {
            if (!auth()->user()->can('update.patients')) {
                session()->flash('error', 'You do not have permission to update patients.');
                return;
            }
        } else {
            if (!auth()->user()->can('create.patients')) {
                session()->flash('error', 'You do not have permission to create patients.');
                return;
            }
        }
        
        $this->validate();
        
        if ($this->editingId) {
            // Update existing patient
            $patient = Patient::findOrFail($this->editingId);
            $patient->update($this->form);
            $message = 'Patient updated successfully.';
        } else {
            // Create new patient
            Patient::create($this->form);
            $message = 'Patient added successfully.';
        }
        
        $this->resetForm();
        $this->showModal = false;
        session()->flash('message', $message);
    }

    public function delete($id): void
    {
        if (!auth()->user()->can('delete.patients')) {
            session()->flash('error', 'You do not have permission to delete patients.');
            return;
        }
        
        Patient::findOrFail($id)->delete();
        session()->flash('message', 'Patient deleted successfully.');
    }

    public function createVisit($patientId): void
    {
        // Redirect to appointments page with patient_id parameter to create new appointment
        $this->redirect(route('appointments.index', ['patient_id' => $patientId]));
    }

    public function viewDetails($id): void
    {
        $this->selectedPatientId = $id;
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal(): void
    {
        $this->showDetailsModal = false;
        $this->selectedPatientId = null;
    }

    public function toggleDropdown($patientId): void
    {
        if ($this->openDropdownId === $patientId) {
            $this->openDropdownId = null;
        } else {
            $this->openDropdownId = $patientId;
        }
    }

    public function closeDropdown(): void
    {
        $this->openDropdownId = null;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!auth()->user()->can('view.patients')) {
            abort(403, 'You do not have permission to view patients.');
        }
        
        $query = Patient::query();
        
        if (!empty(trim($this->search))) {
            $searchTerm = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('full_name', 'like', $searchTerm)
                  ->orWhere('id_number', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm)
                  ->orWhere('phone_secondary', 'like', $searchTerm)
                  ->orWhere('city', 'like', $searchTerm);
            });
        }
        
        $perPageValue = $this->perPage === -1 ? 10000 : $this->perPage;
        $patients = $query->orderBy('created_at', 'desc')->paginate($perPageValue);

        $selectedPatient = $this->selectedPatientId ? Patient::find($this->selectedPatientId) : null;

        return view('livewire.patient-manager', [
            'patients' => $patients,
            'selectedPatient' => $selectedPatient,
        ])->layout('components.layouts.app');
    }
}
