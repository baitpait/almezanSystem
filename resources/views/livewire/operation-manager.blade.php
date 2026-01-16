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
                <div class="flex flex-col gap-4">
                    {{-- Search, Status, and Per Page Row --}}
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
                                        placeholder="Search by patient name or ID...">
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <label class="form-label">Status</label>
                                <select class="form-select" wire:model.live="statusFilter" style="width: 150px; min-width: 150px;">
                                    <option value="">All Stages</option>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="waiting">Waiting</option>
                                    <option value="in_consultation">In Consultation</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="flex-shrink-0">
                                <label class="form-label">Per Page</label>
                                <select class="form-select" wire:model.live="perPage" style="width: 80px; min-width: 80px;">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="sticky left-0 z-10 bg-white">Patient</th>
                            <th>Doctor</th>
                            <th style="min-width: 150px;">Date & Time</th>
                            <th>Status</th>
                            <th class="text-right sticky right-0 z-10 bg-white" style="min-width: 100px; max-width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            @php
                                $operation = $appointment->operation;
                            @endphp
                            <tr>
                                <td class="sticky left-0 z-10 bg-white">
                                <div class="font-medium text-gray-900">{{ $appointment->patient->full_name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-600 mt-0.5">{{ $appointment->patient->id_number ?? '' }}</div>
                                </td>
                            <td class="text-gray-800">{{ $appointment->doctor->name ?? 'N/A' }}</td>
                            <td style="min-width: 150px;">
                                @if($appointment->appointment_date)
                                    <div class="flex flex-col gap-1">
                                        <div class="font-bold text-gray-900 text-base leading-tight whitespace-nowrap">
                                            {{ $appointment->appointment_date->format('d-m-Y') }}
                                        </div>
                                        @if($appointment->appointment_time)
                                        <div class="text-sm text-gray-700 font-semibold leading-tight whitespace-nowrap">
                                            @php
                                                try {
                                                    $time = \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time);
                                                } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                                                    try {
                                                        $time = \Carbon\Carbon::createFromFormat('H:i', $appointment->appointment_time);
                                                    } catch (\Carbon\Exceptions\InvalidFormatException $e2) {
                                                        $time = \Carbon\Carbon::parse($appointment->appointment_time);
                                                    }
                                                }
                                            @endphp
                                            {{ $time->format('h:i A') }}
                                        </div>
                                        @endif
                                        @if($appointment->duration)
                                        <div class="text-xs text-gray-600 font-medium">
                                            Duration: {{ $appointment->duration }} min
                                        </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-500 text-sm">N/A</span>
                                @endif
                                </td>
                                <td>
                                    @php
                                    $stageColors = [
                                        'scheduled' => 'bg-blue-100 text-blue-800',
                                        'waiting' => 'bg-yellow-100 text-yellow-800',
                                        'in_consultation' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $stageLabels = [
                                        'scheduled' => 'Scheduled',
                                        'waiting' => 'Waiting',
                                        'in_consultation' => 'In Consultation',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ];
                                    $visitStage = $appointment->visit_stage ?? 'scheduled';
                                    @endphp
                                <span class="badge-status {{ $stageColors[$visitStage] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $stageLabels[$visitStage] ?? ucfirst(str_replace('_', ' ', $visitStage)) }}
                                    </span>
                                </td>
                            <td class="sticky right-0 z-10 bg-white text-right" style="min-width: 100px; max-width: 120px;">
                                <button type="button" 
                                        class="btn-add btn-action flex items-center gap-1.5 px-3 py-1.5 text-sm whitespace-nowrap"
                                        wire:click="viewOperation({{ $appointment->id }})"
                                        title="View Operation">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>View</span>
                                </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                            <td colspan="5" class="text-center py-16">
                                <div class="empty-state">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                    <p class="text-gray-700 font-bold text-lg mb-2">No assessments found</p>
                                    <p class="text-gray-500 text-sm">Try adjusting your filters or search criteria to find assessments.</p>
                                </div>
                            </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($appointments->hasPages() || $appointments->total() > 0)
            <div class="pagination-wrapper">
                @if($appointments->hasPages())
                <div class="pagination-buttons">
                {{ $appointments->links() }}
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
                        <button wire:click="cancel" class="btn btn-ghost">Cancel</button>
                        <button wire:click="save" class="btn-primary">
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
