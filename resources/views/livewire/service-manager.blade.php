<div class="container mx-auto p-4">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1>Service Management</h1>
                <p>Manage medical services and their prices</p>
            </div>
            @can('create.services')
            <button class="btn-add btn-action flex items-center gap-2" wire:click="create">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Service
            </button>
            @endcan
        </div>
    </div>

    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="alert alert-success mb-6 shadow-lg animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm">{{ session('message') }}</span>
        </div>
    @endif

    {{-- Search Bar and Per Page --}}
    <div class="search-container">
        <div class="flex flex-col gap-4">
            {{-- Search with Per Page --}}
            <div>
                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="form-label">Search</label>
                        <div class="search-input-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="Search services by name...">
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <label class="form-label">Per Page</label>
                        <select class="form-select" wire:model.live="perPage" style="width: 80px; min-width: 80px;">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="-1">All</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table of Services --}}
    <div class="data-table-container overflow-x-auto">
        <table class="data-table min-w-full">
            <thead>
            <tr>
                    <th class="sticky left-0 z-10 bg-gray-50 w-[40%]">Service Name</th>
                    <th class="w-[25%]">Price</th>
                    <th class="w-[20%]">Status</th>
                    <th class="text-right sticky right-0 z-10 bg-gray-50 w-[15%] min-w-[60px]">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($services as $service)
                <tr>
                    <td class="font-bold text-gray-900 text-base sticky left-0 z-10 bg-white">
                        <div class="max-w-full pr-2">
                            <div class="truncate">{{ $service->name }}</div>
                        </div>
                    </td>
                    <td class="font-semibold text-gray-900">
                        ₪{{ number_format((float) $service->base_price, 2, '.', ',') }}
                    </td>
                    <td>
                        @if($service->is_active)
                            <span class="badge-status bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="badge-status bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </td>
                    <td class="sticky right-0 z-10 bg-white" style="display: flex; justify-content: flex-end; align-items: center;">
                        <div class="relative inline-block" data-dropdown-container="{{ $service->id }}">
                            <button type="button"
                                    class="btn btn-sm btn-ghost"
                                    onclick="toggleSimpleDropdown({{ $service->id }}, event)"
                                    data-dropdown-trigger="{{ $service->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <div class="simple-dropdown-menu"
                                 id="dropdown-menu-{{ $service->id }}"
                                 data-dropdown-menu="{{ $service->id }}"
                                 data-original-parent="{{ $service->id }}"
                                 style="display: none;">
                                <ul class="dropdown-menu-list">
                                    @can('update.services')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-edit" wire:click="edit({{ $service->id }})" onclick="closeSimpleDropdown({{ $service->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                            <span>Edit</span>
                                </button>
                                    </li>
                                    @endcan
                                    @can('update.services')
                                    <li>
                                        <button type="button" class="dropdown-menu-item {{ $service->is_active ? 'dropdown-menu-item-delete' : 'dropdown-menu-item-visit' }}" wire:click="toggleActive({{ $service->id }})" onclick="closeSimpleDropdown({{ $service->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        @if($service->is_active)
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        @endif
                                    </svg>
                                            <span>{{ $service->is_active ? 'Deactivate' : 'Activate' }}</span>
                                </button>
                                    </li>
                                    @endcan
                                    @can('delete.services')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-delete" wire:click="delete({{ $service->id }})" wire:confirm="Are you sure you want to delete this service?" onclick="closeSimpleDropdown({{ $service->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                            <span>Delete</span>
                                </button>
                                    </li>
                                    @endcan
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty-state" style="grid-column: 1 / -1;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3>No services found</h3>
                        <p>Start by adding your first service</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($services->hasPages() || $services->total() > 0)
    <div class="pagination-wrapper">
    @if($services->hasPages())
            <div class="pagination-buttons">
                {{ $services->links() }}
            </div>
        @endif
        </div>
    @endif

    {{-- Modal for Create/Edit --}}
    @if($showModal)
    <div class="modal-overlay" wire:click.self="resetForm">
        <div class="modal-container">
                <div class="modal-header">
                <h2 class="modal-title">{{ $editingId ? 'Edit Service' : 'Add Service' }}</h2>
                <button class="btn-cancel btn-action flex items-center justify-center" wire:click="resetForm" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group md:col-span-2">
                            <label class="form-label">Service Name *</label>
                            <input type="text" class="form-input" wire:model.defer="form.name" required>
                            @error('form.name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group md:col-span-2">
                            <label class="form-label">Price (₪) *</label>
                            <input type="number" step="0.01" class="form-input" wire:model.defer="form.base_price" placeholder="0.00" required>
                            @error('form.base_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="checkbox checkbox-primary" wire:model.defer="form.is_active">
                                <span class="ml-2 text-sm">Active Service</span>
                            </label>
                        </div>
                        </div>

                        <div class="modal-footer">
                        <button type="submit" class="btn-add btn-action flex items-center gap-2">
                            @if($editingId)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Update Service
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Service
                            @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

