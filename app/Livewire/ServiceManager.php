<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public int $perPage = 10;
    public ?int $editingId = null;
    public bool $showModal = false;

    public array $form = [
        'name' => '',
        'base_price' => '0.00',
        'is_active' => true,
    ];

    protected function rules(): array
    {
        return [
            'form.name' => 'required|string|max:255',
            'form.base_price' => 'required|numeric|min:0',
            'form.is_active' => 'boolean',
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
            'base_price' => '0.00',
            'is_active' => true,
        ];

        $this->editingId = null;
        $this->showModal = false;
    }

    public function create(): void
    {
        abort_unless(auth()->user()->can('create.services'), 403);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        abort_unless(auth()->user()->can('update.services'), 403);
        $service = Service::findOrFail($id);

        $this->editingId = $service->id;
        $this->form = [
            'name' => $service->name,
            'base_price' => number_format((float) $service->base_price, 2, '.', ''),
            'is_active' => (bool) $service->is_active,
        ];

        $this->showModal = true;
    }

    public function save(): void
    {
        abort_unless(
            $this->editingId 
                ? auth()->user()->can('update.services')
                : auth()->user()->can('create.services'),
            403
        );
        
        $this->validate();

        $data = $this->form;
        $data['base_price'] = (float) $data['base_price'];

        if ($this->editingId) {
            $service = Service::findOrFail($this->editingId);
            $service->update($data);
            $message = 'Service updated successfully.';
        } else {
            Service::create($data);
            $message = 'Service created successfully.';
        }

        session()->flash('message', $message);
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('delete.services'), 403);
        $service = Service::findOrFail($id);
        $service->delete();
        session()->flash('message', 'Service deleted successfully.');
    }

    public function toggleActive(int $id): void
    {
        abort_unless(auth()->user()->can('update.services'), 403);
        $service = Service::findOrFail($id);
        $service->update(['is_active' => ! $service->is_active]);

        $message = $service->is_active ? 'Service activated.' : 'Service deactivated.';
        session()->flash('message', $message);
    }

    public function render()
    {
        $query = Service::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name');

        $perPageValue = $this->perPage === -1 ? 1000 : $this->perPage;
        $services = $query->paginate($perPageValue);

        return view('livewire.service-manager', [
            'services' => $services,
        ])->layout('components.layouts.app');
    }
}

