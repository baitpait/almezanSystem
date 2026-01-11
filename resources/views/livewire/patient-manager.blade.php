<div class="container mx-auto p-4">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1>Patient Management</h1>
                <p>Manage patient records and information</p>
            </div>
            @can('create.patients')
            <button class="btn-add btn-action flex items-center gap-2" wire:click="create">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Patient
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
                                   placeholder="Search by name, phone, or city...">
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

    {{-- Table of Patients --}}
    <div class="data-table-container overflow-x-auto">
        <table class="data-table min-w-full">
            <thead>
                <tr>
                    <th class="sticky left-0 z-10 bg-gray-50 w-[40%]">Name</th>
                    <th class="w-[30%]">Phone</th>
                    <th class="w-[15%] text-center" style="text-align: center;">Age</th>
                    <th class="text-right sticky right-0 z-10 bg-gray-50 w-[15%] min-w-[60px]">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr>
                    <td class="font-bold text-gray-900 text-base sticky left-0 z-10 bg-white">
                        <div class="max-w-full pr-2">
                            <div class="truncate">{{ $patient->full_name }}</div>
                        </div>
                    </td>
                    <td class="font-mono text-sm font-semibold text-gray-800">{{ $patient->phone ?? '-' }}</td>
                    <td class="text-center">
                        @if($patient->date_of_birth)
                            @php
                                $age = \Carbon\Carbon::parse($patient->date_of_birth)->age;
                            @endphp
                            <span class="font-bold text-gray-900 text-base">{{ $age }}</span>
                        @else
                            <span class="text-gray-500 font-medium">-</span>
                        @endif
                    </td>
                    <td class="sticky right-0 z-10 bg-white" style="display: flex; justify-content: flex-end; align-items: center;">
                        <div class="relative inline-block" data-dropdown-container="{{ $patient->id }}">
                            <button type="button" 
                                    class="btn btn-sm btn-ghost" 
                                    onclick="toggleSimpleDropdown({{ $patient->id }}, event)"
                                    data-dropdown-trigger="{{ $patient->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <div class="simple-dropdown-menu" 
                                 id="dropdown-menu-{{ $patient->id }}"
                                 data-dropdown-menu="{{ $patient->id }}"
                                 data-original-parent="{{ $patient->id }}"
                                 style="display: none;">
                                <ul class="dropdown-menu-list">
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-view" wire:click="viewDetails({{ $patient->id }})" onclick="closeSimpleDropdown({{ $patient->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>View</span>
                                        </button>
                                    </li>
                                    @can('create.appointments')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-visit" wire:click="createVisit({{ $patient->id }})" onclick="closeSimpleDropdown({{ $patient->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                            </svg>
                                            <span>Visit</span>
                                        </button>
                                    </li>
                                    @endcan
                                    @can('update.patients')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-edit" wire:click="edit({{ $patient->id }})" onclick="closeSimpleDropdown({{ $patient->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </button>
                                    </li>
                                    @endcan
                                    @if($canViewInvoices ?? auth()->user()->can('view.invoices'))
                                    <li>
                                        <a href="{{ route('invoices.index', ['patient' => $patient->id]) }}"
                                           class="dropdown-menu-item dropdown-menu-item-view"
                                           onclick="closeSimpleDropdown({{ $patient->id }});">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span>Invoices</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($canCreateInvoices ?? auth()->user()->can('create.invoices'))
                                    <li>
                                        <a href="{{ route('invoices.index', ['create' => 1, 'patient' => $patient->id]) }}"
                                           class="dropdown-menu-item dropdown-menu-item-visit"
                                           onclick="closeSimpleDropdown({{ $patient->id }});">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span>Add Invoice</span>
                                        </a>
                                    </li>
                                    @endif
                                    @can('delete.patients')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-delete" wire:click="delete({{ $patient->id }})" wire:confirm="Are you sure you want to delete this patient?" onclick="closeSimpleDropdown({{ $patient->id }})">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3>No patients found</h3>
                        <p>Start by adding your first patient</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($patients->hasPages() || $patients->total() > 0)
    <div class="pagination-wrapper">
        @if($patients->hasPages())
        <div class="pagination-buttons">
            {{ $patients->links() }}
        </div>
        @endif
    </div>
    @endif

    {{-- Modal for Create/Edit --}}
    @if($showModal)
    <div class="modal-overlay" wire:click.self="resetForm">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">{{ $editingId ? 'Edit Patient' : 'Add Patient' }}</h2>
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
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-input" wire:model.defer="form.full_name" required>
                            @error('form.full_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">ID Number *</label>
                            <input type="text" class="form-input" wire:model.defer="form.id_number" required>
                            @error('form.id_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Date of Birth *</label>
                            <input type="date" class="form-input" wire:model.defer="form.date_of_birth" required>
                            @error('form.date_of_birth') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Gender *</label>
                            <select class="form-select" wire:model.defer="form.gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            @error('form.gender') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">City *</label>
                            <input type="text" class="form-input" wire:model.defer="form.city" required>
                            @error('form.city') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Occupation *</label>
                            <input type="text" class="form-input" wire:model.defer="form.occupation" required placeholder="Occupation">
                            @error('form.occupation') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone *</label>
                            <input type="tel" class="form-input" wire:model.defer="form.phone" required pattern="[\d٠١٢٣٤٥٦٧٨٩+\-\s()]+" inputmode="numeric" placeholder="Numbers only (Arabic or English)">
                            @error('form.phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone (2)</label>
                            <input type="tel" class="form-input" wire:model.defer="form.phone_secondary" pattern="[\d٠١٢٣٤٥٦٧٨٩+\-\s()]+" inputmode="numeric" placeholder="Numbers only (Arabic or English)">
                            @error('form.phone_secondary') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group md:col-span-2">
                            <label class="form-label">Notes</label>
                            <textarea class="form-input resize-none" rows="3" wire:model.defer="form.notes" style="padding: 0.5rem 0.75rem; line-height: 1.5; min-height: auto;"></textarea>
                            @error('form.notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn-add btn-action flex items-center gap-2">
                            @if($editingId)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Update Patient
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Patient
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Patient Details Modal --}}
    @if($showDetailsModal && $selectedPatient)
    <div class="modal-overlay" wire:click.self="closeDetailsModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Patient Details</h2>
                <button class="btn-cancel btn-action flex items-center justify-center" wire:click="closeDetailsModal" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group md:col-span-2">
                        <label class="form-label">Full Name</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 font-semibold text-base">
                            {{ $selectedPatient->full_name }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">ID Number</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 font-mono">
                            {{ $selectedPatient->id_number ?? '-' }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                            {{ $selectedPatient->date_of_birth ? \Carbon\Carbon::parse($selectedPatient->date_of_birth)->format('Y-m-d') : '-' }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 font-bold text-lg">
                            @if($selectedPatient->date_of_birth)
                                {{ \Carbon\Carbon::parse($selectedPatient->date_of_birth)->age }} years
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <div class="px-4 py-3">
                            <span class="badge-status font-semibold {{ $selectedPatient->gender === 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ ucfirst($selectedPatient->gender ?? '-') }}
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">City</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                            {{ $selectedPatient->city ?? '-' }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Occupation</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                            {{ $selectedPatient->occupation ?? '-' }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 font-mono">
                            {{ $selectedPatient->phone ?? '-' }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone (2)</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 font-mono">
                            {{ $selectedPatient->phone_secondary ?? '-' }}
                        </div>
                    </div>

                    @if($selectedPatient->notes)
                    <div class="form-group md:col-span-2">
                        <label class="form-label">Notes</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 whitespace-pre-wrap">
                            {{ $selectedPatient->notes }}
                        </div>
                    </div>
                    @endif
                </div>

                <div class="modal-footer mt-6">
                    <button type="button" class="btn-edit btn-action flex items-center gap-2" wire:click="edit({{ $selectedPatient->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Patient
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
