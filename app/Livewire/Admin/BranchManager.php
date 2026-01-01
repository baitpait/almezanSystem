<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Branch;
use Livewire\Component;
use Livewire\WithPagination;

class BranchManager extends Component
{
    use WithPagination;

    public string $search = '';
    public $editingId = null;
    public $showModal = false;
    public array $form = [
        'name' => '',
        'address' => '',
        'phone' => '',
        'email' => '',
        'notes' => '',
        'is_active' => true,
    ];

    protected function rules(): array
    {
        return [
            'form.name' => 'required|string|max:255',
            'form.address' => 'nullable|string|max:500',
            'form.phone' => 'nullable|string|max:32',
            'form.email' => 'nullable|email|max:255',
            'form.notes' => 'nullable|string|max:1000',
            'form.is_active' => 'boolean',
        ];
    }

    public function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'notes' => '',
            'is_active' => true,
        ];
        $this->editingId = null;
        $this->showModal = false;
    }

    public function create(): void
    {
        abort_unless(auth()->user()->can('create.branches'), 403);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id): void
    {
        abort_unless(auth()->user()->can('update.branches'), 403);
        $branch = Branch::findOrFail($id);
        $this->editingId = $branch->id;
        $this->form = [
            'name' => $branch->name,
            'address' => $branch->address,
            'phone' => $branch->phone,
            'email' => $branch->email,
            'notes' => $branch->notes,
            'is_active' => $branch->is_active,
        ];
        $this->showModal = true;
    }

    public function save(): void
    {
        abort_unless(
            $this->editingId 
                ? auth()->user()->can('update.branches')
                : auth()->user()->can('create.branches'),
            403
        );
        
        $this->validate();

        if ($this->editingId) {
            Branch::findOrFail($this->editingId)->update($this->form);
            $message = 'Branch updated successfully.';
        } else {
            Branch::create($this->form);
            $message = 'Branch created successfully.';
        }

        $this->resetForm();
        session()->flash('message', $message);
    }

    public function delete($id): void
    {
        abort_unless(auth()->user()->can('delete.branches'), 403);
        Branch::findOrFail($id)->delete();
        session()->flash('message', 'Branch deleted successfully.');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Branch::query();

        if (!empty(trim($this->search))) {
            $searchTerm = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('address', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        }

        $branches = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.branch-manager', [
            'branches' => $branches,
        ])->layout('components.layouts.app');
    }
}
