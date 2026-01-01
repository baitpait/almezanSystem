    @if($isCreatePage || $isEditPage)
        {{-- Create/Edit Page --}}
        @include('livewire.operation-manager.form')
    @else
        {{-- List Page --}}
    <div class="container mx-auto p-4">
            {{-- Page Header --}}
            <div class="page-header">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
                        <h1>Assessment</h1>
                        <p>Manage surgical operations and pre-op assessments</p>
        </div>
                    <a href="{{ route('operations.create') }}" class="btn-add btn-action flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New Operation
        </a>
    </div>
            </div>

            {{-- Success Message --}}
            @if(session()->has('message'))
            <div class="alert alert-success mb-6 shadow-lg animate-fade-in">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
            @endif

            {{-- Search Container --}}
            <div class="search-container">
                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                    <div class="search-input-wrapper flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" 
                            class="form-input" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search by patient name or ID...">
                </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-700 font-medium">Status:</label>
                        <select class="form-select form-select-sm" wire:model.live="statusFilter">
                        <option value="">All Status</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="postponed">Postponed</option>
                    </select>
                </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-700 font-medium">Per Page:</label>
                        <select class="form-select form-select-sm" wire:model.live="perPage">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
            </div>
        </div>
    </div>

            {{-- Data Table --}}
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="sticky left-0 z-10 bg-white">ID</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Type</th>
                            <th>Eye</th>
                            <th style="min-width: 150px;">Date & Time</th>
                            <th>Status</th>
                            <th>Cost</th>
                            <th class="text-right sticky right-0 z-10 bg-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($operations as $operation)
                            <tr>
                            <td class="sticky left-0 z-10 bg-white font-mono text-xs text-gray-800">#{{ $operation->id }}</td>
                                <td>
                                <div class="font-medium text-gray-900">{{ $operation->patient->full_name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-600 mt-0.5">{{ $operation->patient->id_number ?? '' }}</div>
                                </td>
                            <td class="text-gray-800">{{ $operation->doctor->name ?? 'N/A' }}</td>
                                <td>
                                <span class="badge-status bg-purple-100 text-purple-800">{{ $operation->operation_type }}</span>
                                </td>
                                <td>
                                <span class="badge-status bg-gray-100 text-gray-800">{{ $operation->operation_eye }}</span>
                            </td>
                            <td style="min-width: 150px;">
                                @if($operation->appointment && $operation->appointment->appointment_date)
                                    <div class="flex flex-col gap-1">
                                        <div class="font-bold text-gray-900 text-base leading-tight whitespace-nowrap">
                                            {{ $operation->appointment->appointment_date->format('d-m-Y') }}
                                        </div>
                                        @if($operation->appointment->appointment_time)
                                        <div class="text-sm text-gray-700 font-semibold leading-tight whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($operation->appointment->appointment_time)->format('h:i A') }}
                                        </div>
                                        @endif
                                        @if($operation->appointment->duration)
                                        <div class="text-xs text-gray-600 font-medium">
                                            Duration: {{ $operation->appointment->duration }} min
                                        </div>
                                        @endif
                                    </div>
                                @elseif($operation->start_date)
                                    <div class="flex flex-col gap-1">
                                        <div class="font-bold text-gray-900 text-base leading-tight whitespace-nowrap">
                                            {{ $operation->start_date->format('d-m-Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 italic">
                                            No time set
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-sm">N/A</span>
                                @endif
                                </td>
                                <td>
                                    @php
                                    $statusClasses = [
                                        'scheduled' => 'bg-blue-100 text-blue-800',
                                        'in_progress' => 'bg-yellow-100 text-yellow-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'postponed' => 'bg-gray-100 text-gray-800',
                                        ];
                                    @endphp
                                <span class="badge-status {{ $statusClasses[$operation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $operation->status)) }}
                                    </span>
                                </td>
                            <td class="font-mono text-gray-800">{{ number_format($operation->cost, 2) }} ₪</td>
                            <td class="sticky right-0 z-10 bg-white text-right">
                                <div class="relative inline-block" data-dropdown-container="{{ $operation->id }}">
                                    <button type="button" 
                                            class="btn btn-sm btn-ghost" 
                                            onclick="toggleSimpleDropdown({{ $operation->id }}, event)"
                                            data-dropdown-trigger="{{ $operation->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    <div class="simple-dropdown-menu" 
                                         id="dropdown-menu-{{ $operation->id }}"
                                         data-dropdown-menu="{{ $operation->id }}"
                                         data-original-parent="{{ $operation->id }}"
                                         style="display: none;">
                                        <ul class="dropdown-menu-list">
                                            <li>
                                                <a href="{{ route('operations.edit', $operation->id) }}" class="dropdown-menu-item dropdown-menu-item-edit" onclick="closeSimpleDropdown({{ $operation->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                                    <span>Edit</span>
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-menu-item dropdown-menu-item-delete" wire:click="delete({{ $operation->id }})" wire:confirm="Are you sure you want to delete this operation?" onclick="closeSimpleDropdown({{ $operation->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                                    <span>Delete</span>
                                        </button>
                                            </li>
                                        </ul>
                                    </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                            <td colspan="9" class="empty-state" style="grid-column: 1 / -1;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3>No operations found</h3>
                                <p>Start by adding your first operation</p>
                            </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($operations->hasPages() || $operations->total() > 0)
            <div class="pagination-wrapper">
                @if($operations->hasPages())
                <div class="pagination-buttons">
                {{ $operations->links() }}
                </div>
                @endif
            </div>
            @endif
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click.self="$set('showModal', false)">
            <div class="bg-base-100 rounded-xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-6 border-b border-base-300">
                    <h2 class="text-xl font-bold">{{ $editingId ? 'Edit Operation' : 'New Operation' }}</h2>
                    <button wire:click="$set('showModal', false)" class="btn btn-sm btn-circle btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body with Tabs --}}
                <div class="flex-1 overflow-hidden flex flex-col">
                    {{-- Tabs Navigation --}}
                    <div class="tabs tabs-boxed p-2 m-4 mb-0 bg-base-200">
                        <a wire:click="setTab('basic')" class="tab {{ $activeTab === 'basic' ? 'tab-active' : '' }}">Basic Info</a>
                        <a wire:click="setTab('refractive')" class="tab {{ $activeTab === 'refractive' ? 'tab-active' : '' }}">Refractive</a>
                        <a wire:click="setTab('target')" class="tab {{ $activeTab === 'target' ? 'tab-active' : '' }}">Target Parameters</a>
                        <a wire:click="setTab('medical')" class="tab {{ $activeTab === 'medical' ? 'tab-active' : '' }}">Medical History</a>
                        <a wire:click="setTab('exam')" class="tab {{ $activeTab === 'exam' ? 'tab-active' : '' }}">Eye Exam</a>
                        <a wire:click="setTab('ectasia')" class="tab {{ $activeTab === 'ectasia' ? 'tab-active' : '' }}">Ectasia Risk</a>
                        <a wire:click="setTab('recommendation')" class="tab {{ $activeTab === 'recommendation' ? 'tab-active' : '' }}">Recommendation</a>
                    </div>

                    {{-- Tab Content --}}
                    <div class="flex-1 overflow-y-auto p-6">
                        {{-- Basic Info Tab --}}
                        @if($activeTab === 'basic')
                            <div class="space-y-4">
                                {{-- Patient Search --}}
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">Patient *</span>
                                    </label>
                                    <input type="text" wire:model.live.debounce.300ms="patientSearch" placeholder="Search patient..." class="input input-bordered w-full">
                                    @if($patientSearch && !$selectedPatientId)
                                        <div class="mt-2 border border-base-300 rounded-lg max-h-48 overflow-y-auto">
                                            @foreach($patients as $patient)
                                                <div wire:click="selectPatient({{ $patient->id }})" class="p-3 hover:bg-base-200 cursor-pointer border-b border-base-300 last:border-0">
                                                    <div class="font-medium">{{ $patient->full_name }}</div>
                                                    <div class="text-xs text-base-content/60">{{ $patient->id_number }} | {{ $patient->phone }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($selectedPatientId)
                                        <div class="mt-2 badge badge-success gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Selected
                                        </div>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="label">
                                            <span class="label-text">Doctor *</span>
                                        </label>
                                        <select wire:model="operationForm.doctor_id" class="select select-bordered w-full">
                                            <option value="">Select Doctor</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="label">
                                            <span class="label-text">Eye *</span>
                                        </label>
                                        <select wire:model.live="operationForm.operation_eye" class="select select-bordered w-full">
                                            <option value="OD">OD (Right)</option>
                                            <option value="OS">OS (Left)</option>
                                            <option value="OU">OU (Both)</option>
                                        </select>
                                    </div>

                                    @php
                                        $operationEye = $operationForm['operation_eye'] ?? 'OU';
                                        $isBothEyes = $operationEye === 'OU';
                                    @endphp

                                    @if($isBothEyes)
                                        {{-- Both Eyes (OU) - Show separate operation types for each eye --}}
                                        <div class="col-span-2">
                                            <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200 mb-4">
                                                <label class="label cursor-pointer justify-start gap-3">
                                                    <input type="checkbox" wire:model.live="operationForm.same_operation_type_both_eyes" class="checkbox checkbox-primary checkbox-sm">
                                                    <span class="label-text text-xs font-semibold">Same operation type for both eyes / نفس نوع العملية للعينين</span>
                                                </label>
                                                @if($operationForm['same_operation_type_both_eyes'])
                                                    <p class="text-xs text-gray-600 mt-2">
                                                        <strong>مفعّل:</strong> سيتم إظهار قسم واحد مشترك للعينين. نوع العملية سيتم نسخه من OD إلى OS تلقائياً.<br>
                                                        <strong>Enabled:</strong> A single shared section will be displayed for both eyes. Operation type will be automatically copied from OD to OS.
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        @if($operationForm['same_operation_type_both_eyes'])
                                            {{-- Same Operation Type: Show single shared section --}}
                                            <div class="col-span-2">
                                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200 mb-4">
                                                        <p class="text-sm font-semibold text-gray-800 text-center">
                                                            <strong>نفس نوع العملية للعينين (OD & OS) / Same operation type for both eyes (OD & OS)</strong>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <label class="label">
                                                            <span class="label-text text-xs font-semibold">Operation Type (OD & OS) *</span>
                                                        </label>
                                                        <select wire:model.live="operationForm.operation_type_od" class="select select-bordered w-full">
                                                            <option value="">Select</option>
                                                            <option value="LASIK">LASIK</option>
                                                            <option value="Femto-LASIK">Femto-LASIK</option>
                                                            <option value="PRK">PRK</option>
                                                            <option value="Trans-PRK">Trans-PRK</option>
                                                            <option value="SMILE">SMILE</option>
                                                            <option value="PTK">PTK</option>
                                                            <option value="Topography Guided">Topography Guided</option>
                                                            <option value="Presbyopia">Presbyopia</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{-- Different Operation Types: Show separate sections for each eye --}}
                                            <div>
                                                <label class="label">
                                                    <span class="label-text">Operation Type (OD) *</span>
                                                </label>
                                                <select wire:model.live="operationForm.operation_type_od" class="select select-bordered w-full">
                                                    <option value="">Select</option>
                                                    <option value="LASIK">LASIK</option>
                                                    <option value="Femto-LASIK">Femto-LASIK</option>
                                                    <option value="PRK">PRK</option>
                                                    <option value="Trans-PRK">Trans-PRK</option>
                                                    <option value="SMILE">SMILE</option>
                                                    <option value="PTK">PTK</option>
                                                    <option value="Topography Guided">Topography Guided</option>
                                                    <option value="Presbyopia">Presbyopia</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="label">
                                                    <span class="label-text">Operation Type (OS) *</span>
                                                </label>
                                                <select wire:model.live="operationForm.operation_type_os" class="select select-bordered w-full">
                                                    <option value="">Select</option>
                                                    <option value="LASIK">LASIK</option>
                                                    <option value="Femto-LASIK">Femto-LASIK</option>
                                                    <option value="PRK">PRK</option>
                                                    <option value="Trans-PRK">Trans-PRK</option>
                                                    <option value="SMILE">SMILE</option>
                                                    <option value="PTK">PTK</option>
                                                    <option value="Topography Guided">Topography Guided</option>
                                                    <option value="Presbyopia">Presbyopia</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        @endif
                                    @else
                                        {{-- Single Eye (OD or OS) - Show single operation type --}}
                                        <div>
                                            <label class="label">
                                                <span class="label-text">Operation Type *</span>
                                            </label>
                                            <select wire:model="operationForm.operation_type" class="select select-bordered w-full">
                                                <option value="">Select</option>
                                                <option value="LASIK">LASIK</option>
                                                <option value="Femto-LASIK">Femto-LASIK</option>
                                                <option value="PRK">PRK</option>
                                                <option value="Trans-PRK">Trans-PRK</option>
                                                <option value="SMILE">SMILE</option>
                                                <option value="PTK">PTK</option>
                                                <option value="Topography Guided">Topography Guided</option>
                                                <option value="Presbyopia">Presbyopia</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    @endif

                                    <div>
                                        <label class="label">
                                            <span class="label-text">Cost (₪) *</span>
                                        </label>
                                        <input type="number" step="0.01" wire:model="operationForm.cost" class="input input-bordered w-full">
                                    </div>

                                    <div>
                                        <label class="label">
                                            <span class="label-text">Start Date</span>
                                        </label>
                                        <input type="date" wire:model="operationForm.start_date" class="input input-bordered w-full">
                                    </div>

                                    <div>
                                        <label class="label">
                                            <span class="label-text">Pre-op Assessment Date</span>
                                        </label>
                                        <input type="date" wire:model="operationForm.pre_op_assessment_date" class="input input-bordered w-full">
                                    </div>

                                    <div>
                                        <label class="label">
                                            <span class="label-text">Status *</span>
                                        </label>
                                        <select wire:model="operationForm.status" class="select select-bordered w-full">
                                            <option value="scheduled">Scheduled</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="postponed">Postponed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Refractive Profile Tab --}}
                        @if($activeTab === 'refractive')
                            @include('livewire.operation-manager.tabs.refractive')
                        @endif

                        {{-- Target Parameters Tab --}}
                        @if($activeTab === 'target')
                            @include('livewire.operation-manager.tabs.target')
                        @endif

                        {{-- Medical History Tab --}}
                        @if($activeTab === 'medical')
                            @include('livewire.operation-manager.tabs.medical')
                        @endif

                        {{-- Eye Examination Tab --}}
                        @if($activeTab === 'exam')
                            @include('livewire.operation-manager.tabs.exam')
                        @endif

                        {{-- Ectasia Risk Assessment Tab --}}
                        @if($activeTab === 'ectasia')
                            @include('livewire.operation-manager.tabs.ectasia')
                        @endif

                        {{-- Recommendation Tab --}}
                        @if($activeTab === 'recommendation')
                            @include('livewire.operation-manager.tabs.recommendation')
                        @endif
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-between p-6 border-t border-base-300">
                    <div class="text-sm text-base-content/60">
                        <span class="font-semibold">Tip:</span> Use tabs to navigate between sections. Default values are pre-filled for quick entry.
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="$set('showModal', false)" class="btn btn-ghost">Cancel</button>
                        <button wire:click="save" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save
                        </button>
                    </div>
                </div>
                </div>
            </div>
        @endif
    @endif
