<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    public string $search = '';
    public $editingId = null;
    public $showModal = false;
    public array $form = [
        'name' => '',
        'email' => '',
        'password' => '',
        'phone' => '',
        'notes' => '',
        'role' => 'secretary',
        'branch_id' => null,
        'is_active' => true,
    ];

    protected function rules(): array
    {
        $rules = [
            'form.name' => 'required|string|max:255',
            'form.email' => 'required|email|unique:users,email,'.$this->editingId,
            'form.phone' => 'nullable|string|max:20',
            'form.notes' => 'nullable|string|max:1000',
            'form.role' => 'required|in:admin,doctor,secretary',
            'form.branch_id' => 'nullable|exists:branches,id',
            'form.is_active' => 'boolean',
        ];

        if (!$this->editingId || !empty($this->form['password'])) {
            $rules['form.password'] = 'required|string|min:8';
        }

        return $rules;
    }

    public function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'email' => '',
            'password' => '',
            'phone' => '',
            'notes' => '',
            'role' => 'secretary',
            'branch_id' => null,
            'is_active' => true,
        ];
        $this->editingId = null;
        $this->showModal = false;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id): void
    {
        $user = User::findOrFail($id);
        $this->editingId = $user->id;
        $this->form = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => '',
            'phone' => $user->phone ?? '',
            'notes' => $user->notes ?? '',
            'role' => $user->role,
            'branch_id' => $user->branch_id,
            'is_active' => $user->is_active,
        ];
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->form['name'],
            'email' => $this->form['email'],
            'phone' => $this->form['phone'] ?? null,
            'notes' => $this->form['notes'] ?? null,
            'role' => $this->form['role'],
            'branch_id' => $this->form['branch_id'],
            'is_active' => $this->form['is_active'],
        ];

        if (!empty($this->form['password'])) {
            // Laravel automatically hashes password due to 'password' => 'hashed' cast in User model
            $data['password'] = $this->form['password'];
        }

        if ($this->editingId) {
            User::findOrFail($this->editingId)->update($data);
            $message = 'User updated successfully.';
        } else {
            User::create($data);
            $message = 'User created successfully.';
        }

        $this->resetForm();
        session()->flash('message', $message);
    }

    public function delete($id): void
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'User deleted successfully.');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query();

        if (!empty(trim($this->search))) {
            $searchTerm = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        }

        $users = $query->with('branch')->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.user-manager', [
            'users' => $users,
            'branches' => Branch::where('is_active', true)->orderBy('name')->get(),
        ])->layout('components.layouts.app');
    }
}
