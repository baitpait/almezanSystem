<div class="container mx-auto p-4 lg:p-6">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
                <h1>Branch Management</h1>
                <p>Manage clinic branches</p>
        </div>
            @can('create.branches')
            <button class="btn-add btn-action flex items-center gap-2" wire:click="create">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Branch
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

    {{-- Search --}}
    <div class="search-container">
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
            <div class="search-input-wrapper flex-1 w-full md:w-auto order-1 md:order-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Search branches...">
            </div>
        </div>
    </div>

    {{-- Branches Table --}}
    <div class="data-table-container overflow-x-auto">
        <table class="data-table min-w-full">
                    <thead>
                <tr>
                    <th class="sticky left-0 z-10 bg-gray-50">Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Status</th>
                    <th class="text-right sticky right-0 z-10 bg-gray-50">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                <tr>
                    <td class="sticky left-0 z-10 bg-white font-semibold text-gray-900">
                        {{ $branch->name }}
                    </td>
                    <td class="text-sm text-gray-800">{{ $branch->address ?? '-' }}</td>
                    <td class="text-sm text-gray-800">{{ $branch->phone ?? '-' }}</td>
                    <td class="text-sm text-gray-800">{{ $branch->email ?? '-' }}</td>
                            <td>
                                @if($branch->is_active)
                            <span class="badge-status bg-green-100 text-green-800">Active</span>
                                @else
                            <span class="badge-status bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </td>
                    <td class="sticky right-0 z-10 bg-white" style="display: flex; justify-content: flex-end; align-items: center;">
                        <div class="relative inline-block" data-dropdown-container="{{ $branch->id }}">
                            <button type="button"
                                    class="btn btn-sm btn-ghost"
                                    onclick="toggleSimpleDropdown({{ $branch->id }}, event)"
                                    data-dropdown-trigger="{{ $branch->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <div class="simple-dropdown-menu"
                                 id="dropdown-menu-{{ $branch->id }}"
                                 data-dropdown-menu="{{ $branch->id }}"
                                 data-original-parent="{{ $branch->id }}"
                                 style="display: none;">
                                <ul class="dropdown-menu-list">
                                    @can('update.branches')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-edit" wire:click="edit({{ $branch->id }})" onclick="closeSimpleDropdown({{ $branch->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </button>
                                    </li>
                                    @endcan
                                    @can('delete.branches')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-delete" wire:click="delete({{ $branch->id }})" onclick="closeSimpleDropdown({{ $branch->id }}); if(!confirm('Are you sure you want to delete this branch?')) { event.stopPropagation(); return false; }">
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
                    <td colspan="6">
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" class="empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3>No branches found</h3>
                            <p>Get started by creating a new branch</p>
                        </div>
                    </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

    {{-- Pagination --}}
    @if($branches->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-buttons">
            {{ $branches->links() }}
        </div>
    </div>
    @endif

    {{-- Modal --}}
    @if($showModal)
    <div class="modal-overlay" wire:click="resetForm">
        <div class="modal-container max-w-2xl max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="modal-header">
                <h2 class="modal-title">{{ $editingId ? 'Edit Branch' : 'Add Branch' }}</h2>
                <button type="button" class="modal-close" wire:click="resetForm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
            <form wire:submit.prevent="save" autocomplete="off">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                            <label class="form-label">Branch Name <span class="text-red-500">*</span></label>
                            <input type="text" class="form-input" wire:model.defer="form.name" required>
                            @error('form.name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-input" wire:model.defer="form.address">
                            @error('form.address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-input" wire:model.defer="form.phone">
                            @error('form.phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" wire:model.defer="form.email">
                            @error('form.email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                            <label class="form-label">Notes</label>
                            <textarea class="form-input" wire:model.defer="form.notes" rows="2"></textarea>
                            @error('form.notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                            <label class="form-label">Status</label>
                            <select class="form-select" wire:model.defer="form.is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel btn-action" wire:click="resetForm">Cancel</button>
                        <button type="submit" class="btn-add btn-action">
                        {{ $editingId ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
    @endif
</div>
