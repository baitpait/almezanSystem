<div class="container mx-auto p-4 lg:p-6">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1>Doctor Management</h1>
                <p>Manage clinic doctors</p>
            </div>
            @can('create.doctors')
            <button class="btn-add btn-action flex items-center gap-2" wire:click="create">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Doctor
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
                       placeholder="Search doctors by name or phone...">
            </div>
            <div class="flex items-center gap-2 flex-shrink-0 order-2 md:order-2">
                <select class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" wire:model.live="perPage" style="width: 90px;" title="Results per page">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="-1">All</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Doctors Table --}}
    <div class="data-table-container overflow-x-auto">
        <table class="data-table min-w-full">
            <thead>
                <tr>
                    <th class="sticky left-0 z-10 bg-gray-50">Name</th>
                    <th>Phone</th>
                    <th>Branch</th>
                    <th class="text-right sticky right-0 z-10 bg-gray-50">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                <tr>
                    <td class="sticky left-0 z-10 bg-white font-semibold text-gray-900">
                        {{ $doctor->name }}
                    </td>
                    <td class="text-sm text-gray-800">{{ $doctor->phone ?? '-' }}</td>
                    <td class="text-sm text-gray-800">{{ $doctor->branch->name ?? '-' }}</td>
                    <td class="sticky right-0 z-10 bg-white" style="display: flex; justify-content: flex-end; align-items: center;">
                        <div class="relative inline-block" data-dropdown-container="{{ $doctor->id }}">
                            <button type="button"
                                    class="btn btn-sm btn-ghost"
                                    onclick="toggleSimpleDropdown({{ $doctor->id }}, event)"
                                    data-dropdown-trigger="{{ $doctor->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <div class="simple-dropdown-menu"
                                 id="dropdown-menu-{{ $doctor->id }}"
                                 data-dropdown-menu="{{ $doctor->id }}"
                                 data-original-parent="{{ $doctor->id }}"
                                 style="display: none;">
                                <ul class="dropdown-menu-list">
                                    @can('update.doctors')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-edit" wire:click="edit({{ $doctor->id }})" onclick="closeSimpleDropdown({{ $doctor->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </button>
                                    </li>
                                    @endcan
                                    @can('delete.doctors')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-delete" wire:click="delete({{ $doctor->id }})" onclick="closeSimpleDropdown({{ $doctor->id }}); if(!confirm('Are you sure you want to delete this doctor?')) { event.stopPropagation(); return false; }">
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
                    <td colspan="4">
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" class="empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3>No doctors found</h3>
                            <p>Get started by adding a new doctor</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($doctors->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-buttons">
            {{ $doctors->links() }}
        </div>
    </div>
    @endif

    {{-- Modal --}}
    @if($showModal)
    <div class="modal-overlay" wire:click="resetForm">
        <div class="modal-container max-w-2xl max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="modal-header">
                <h2 class="modal-title">{{ $editingId ? 'Edit Doctor' : 'Add Doctor' }}</h2>
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
                            <label class="form-label">Doctor Name <span class="text-red-500">*</span></label>
                            <input type="text" class="form-input" wire:model.defer="form.name" required>
                            @error('form.name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-input" wire:model.defer="form.phone">
                            @error('form.phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Branch</label>
                            <select class="form-select" wire:model.defer="form.branch_id">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('form.branch_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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

