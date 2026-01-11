<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Doctor;
use App\Models\Branch;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

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
        'email' => '',
        'password' => '',
    ];

    protected function rules(): array
    {
        $rules = [
            'form.name' => 'required|string|max:255',
            'form.phone' => 'nullable|string|max:50',
            'form.branch_id' => 'nullable|exists:branches,id',
        ];

        // For new doctor, require email and password to create user
        if (!$this->editingId) {
            $rules['form.email'] = 'required|email|unique:users,email';
            $rules['form.password'] = 'required|string|min:8';
        } else {
            // For editing, email and password are optional
            // Validation will be handled in save() method based on doctor's user_id
            if (!empty($this->form['email'])) {
                // Get user_id from doctor if exists
                try {
                    $doctor = Doctor::findOrFail($this->editingId);
                    $userId = $doctor->user_id;
                    if ($userId) {
                        $rules['form.email'] = 'nullable|email|unique:users,email,' . $userId;
                    } else {
                        $rules['form.email'] = 'nullable|email|unique:users,email';
                    }
                } catch (\Exception $e) {
                    $rules['form.email'] = 'nullable|email|unique:users,email';
                }
            }
            if (!empty($this->form['password'])) {
                $rules['form.password'] = 'nullable|string|min:8';
            }
        }

        return $rules;
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
            'email' => '',
            'password' => '',
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
            'email' => $doctor->user->email ?? '',
            'password' => '', // Don't load password
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

        $doctorData = [
            'name' => $this->form['name'],
            'phone' => $this->form['phone'] ?? null,
            'branch_id' => $this->form['branch_id'] ?: null,
        ];

        if ($this->editingId) {
            // Update existing doctor
            $doctor = Doctor::findOrFail($this->editingId);
            $doctor->update($doctorData);

            // Update associated user if exists
            if ($doctor->user_id) {
                $user = User::find($doctor->user_id);
                if ($user) {
                    $userData = [
                        'name' => $this->form['name'],
                        'branch_id' => $this->form['branch_id'] ?: null,
                        'phone' => $this->form['phone'] ?? null,
                    ];

                    // Update email if provided
                    if (!empty($this->form['email'])) {
                        $userData['email'] = $this->form['email'];
                    }

                    // Update password if provided
                    if (!empty($this->form['password'])) {
                        $userData['password'] = $this->form['password'];
                    }

                    $user->update($userData);
                }
            } else {
                // Create user if doctor doesn't have one and email is provided
                if (!empty($this->form['email'])) {
                    $user = User::create([
                        'name' => $this->form['name'],
                        'email' => $this->form['email'],
                        'password' => $this->form['password'] ?? 'password123', // Default password if not provided
                        'role' => 'doctor',
                        'branch_id' => $this->form['branch_id'] ?: null,
                        'phone' => $this->form['phone'] ?? null,
                        'is_active' => true,
                    ]);

                    // Assign doctor role
                    $user->assignRole('doctor');

                    // Link doctor to user
                    $doctor->update(['user_id' => $user->id]);
                }
            }

            $message = 'Doctor updated successfully.';
        } else {
            // Create new doctor
            $doctor = Doctor::create($doctorData);

            // Create user account for the doctor
            if (!empty($this->form['email'])) {
                $user = User::create([
                    'name' => $this->form['name'],
                    'email' => $this->form['email'],
                    'password' => $this->form['password'],
                    'role' => 'doctor',
                    'branch_id' => $this->form['branch_id'] ?: null,
                    'phone' => $this->form['phone'] ?? null,
                    'is_active' => true,
                ]);

                // Assign doctor role
                $user->assignRole('doctor');

                // Link doctor to user
                $doctor->update(['user_id' => $user->id]);
            }

            $message = 'Doctor created successfully.';
        }

        session()->flash('message', $message);
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('delete.doctors'), 403);
        $doctor = Doctor::findOrFail($id);
        
        // Delete associated user if exists
        if ($doctor->user_id) {
            $user = User::find($doctor->user_id);
            if ($user) {
                $user->delete();
            }
        }
        
        $doctor->delete();
        session()->flash('message', 'Doctor deleted successfully.');
    }

    public function render()
    {
        $query = Doctor::with(['branch', 'user'])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($query) {
                      $query->where('email', 'like', '%' . $this->search . '%');
                  });
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

