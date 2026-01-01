<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Doctor;
use App\Models\Branch;
use Livewire\Component;
use Livewire\WithPagination;

class DoctorManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public int $perPage = 10;
    public $editingId = null;
    public bool $showModal = false;

    public array $form = [
        'name' => '',
        'phone' => '',
        'branch_id' => '',
    ];

    protected function rules(): array
    {
        return [
            'form.name' => 'required|string|max:255',
            'form.phone' => 'nullable|string|max:50',
            'form.branch_id' => 'nullable|exists:branches,id',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'phone' => '',
            'branch_id' => '',
        ];
        $this->editingId = null;
        $this->showModal = false;
    }

    public function create(): void
    {
        abort_unless(auth()->user()->can('create.doctors'), 403);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        abort_unless(auth()->user()->can('update.doctors'), 403);
        $doctor = Doctor::findOrFail($id);
        $this->editingId = $doctor->id;
        $this->form = [
            'name' => $doctor->name ?? '',
            'phone' => $doctor->phone ?? '',
            'branch_id' => $doctor->branch_id ?? '',
        ];
        $this->showModal = true;
    }

    public function save(): void
    {
        abort_unless(
            $this->editingId 
                ? auth()->user()->can('update.doctors')
                : auth()->user()->can('create.doctors'),
            403
        );
        
        $this->validate();

        $data = $this->form;
        $data['branch_id'] = $data['branch_id'] ?: null;

        if ($this->editingId) {
            $doctor = Doctor::findOrFail($this->editingId);
            $doctor->update($data);
            $message = 'Doctor updated successfully.';
        } else {
            Doctor::create($data);
            $message = 'Doctor created successfully.';
        }

        session()->flash('message', $message);
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('delete.doctors'), 403);
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();
        session()->flash('message', 'Doctor deleted successfully.');
    }

    public function render()
    {
        $query = Doctor::with('branch')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name');

        $perPageValue = $this->perPage === -1 ? 1000 : $this->perPage;
        $doctors = $query->paginate($perPageValue);

        $branches = Branch::all();

        return view('livewire.admin.doctor-manager', [
            'doctors' => $doctors,
            'branches' => $branches,
        ])->layout('components.layouts.app');
    }
}

