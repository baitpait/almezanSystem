<div class="container mx-auto p-4">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1>Dashboard</h1>
                <p>View and manage appointments</p>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="alert alert-success mb-6 shadow-lg animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    {{-- Error Message --}}
    @if (session()->has('error'))
        <div class="alert alert-error mb-6 shadow-lg animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Appointments Section --}}
    <div class="card-modern mb-6">
        <div class="card-header">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Appointments</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        @if($dateTab === 'today')
                            Today - {{ today()->format('M d, Y') }}
                        @elseif($dateTab === 'tomorrow')
                            Tomorrow - {{ today()->addDay()->format('M d, Y') }}
                        @else
                            This Week
                        @endif
                    </p>
                </div>
                {{-- Date Tabs --}}
                <div class="flex gap-2">
                    <button wire:click="setDateTab('today')" class="px-4 py-2 rounded-lg font-semibold text-sm transition-all {{ $dateTab === 'today' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Today
                    </button>
                    <button wire:click="setDateTab('tomorrow')" class="px-4 py-2 rounded-lg font-semibold text-sm transition-all {{ $dateTab === 'tomorrow' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Tomorrow
                    </button>
                    <button wire:click="setDateTab('this_week')" class="px-4 py-2 rounded-lg font-semibold text-sm transition-all {{ $dateTab === 'this_week' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        This Week
                    </button>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card-body border-b border-gray-200 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-group mb-0">
                    <label class="form-label">Visit Stage</label>
                    <select class="form-select" wire:model.live="visitStageFilter">
                        <option value="">All Stages</option>
                        <option value="waiting">Waiting</option>
                        <option value="in_consultation">In Consultation</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Visit Type</label>
                    <select class="form-select" wire:model.live="visitTypeFilter">
                        <option value="">All Types</option>
                        <option value="Assessment">Assessment</option>
                        <option value="Operation">Operation</option>
                        <option value="Follow up">Follow up</option>
                        <option value="New visit">New visit</option>
                    </select>
                </div>

                @if(!auth()->user()->isDoctor())
                <div class="form-group mb-0">
                    <label class="form-label">Doctor</label>
                    <select class="form-select" wire:model.live="doctorFilter">
                        <option value="">All Doctors</option>
                        @foreach($doctors ?? [] as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>

        {{-- Appointments by Type Tabs --}}
        @if(isset($filteredTodayAppointments) && $filteredTodayAppointments->count() > 0)
            <div class="card-body border-b border-gray-200 bg-white">
                <div class="flex gap-2 overflow-x-auto pb-2">
                    <button wire:click="$set('visitTypeFilter', '')" class="px-4 py-2 rounded-lg font-semibold text-sm transition-all whitespace-nowrap {{ empty($visitTypeFilter) ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All ({{ $filteredTodayAppointments->count() }})
                    </button>
                    <button wire:click="$set('visitTypeFilter', 'Assessment')" class="px-4 py-2 rounded-lg font-semibold text-sm transition-all whitespace-nowrap {{ $visitTypeFilter === 'Assessment' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Assessment ({{ $appointmentsByType['Assessment']->count() }})
                    </button>
                    <button wire:click="$set('visitTypeFilter', 'Operation')" class="px-4 py-2 rounded-lg font-semibold text-sm transition-all whitespace-nowrap {{ $visitTypeFilter === 'Operation' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Operation ({{ $appointmentsByType['Operation']->count() }})
                    </button>
                    <button wire:click="$set('visitTypeFilter', 'Follow up')" class="px-4 py-2 rounded-lg font-semibold text-sm transition-all whitespace-nowrap {{ $visitTypeFilter === 'Follow up' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Follow up ({{ $appointmentsByType['Follow up']->count() }})
                    </button>
                    <button wire:click="$set('visitTypeFilter', 'New visit')" class="px-4 py-2 rounded-lg font-semibold text-sm transition-all whitespace-nowrap {{ $visitTypeFilter === 'New visit' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        New visit ({{ $appointmentsByType['New visit']->count() }})
                    </button>
                </div>
            </div>

            {{-- Appointments Table --}}
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Visit Type</th>
                            <th>Doctor / Patient</th>
                            <th>Date / Time</th>
                            <th>Stage</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($filteredTodayAppointments as $appointment)
                        <tr>
                            {{-- Visit Type --}}
                            <td>
                                @if($appointment->visit_type)
                                    @php
                                        $typeColors = [
                                            'Assessment' => 'bg-blue-100 text-blue-800',
                                            'Operation' => 'bg-yellow-100 text-yellow-800',
                                            'Follow up' => 'bg-purple-100 text-purple-800',
                                            'New visit' => 'bg-green-100 text-green-800',
                                        ];
                                        $typeColor = $typeColors[$appointment->visit_type] ?? 'bg-gray-100 text-gray-800';
                                    @endphp

                                    <span class="badge-status {{ $typeColor }}">
                                        {{ $appointment->visit_type }}
                                    </span>
                                @else
                                    <span class="text-gray-500 font-medium">-</span>
                                @endif
                            </td>

                            {{-- Doctor / Patient --}}
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $appointment->doctor->name ?? 'Not Specified' }}</div>
                                        <div class="text-xs text-gray-500">{{ $appointment->patient->full_name }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Date / Time --}}
                            <td>
                                <div class="text-sm font-medium text-gray-900">{{ $appointment->appointment_date }}</div>
                                <div class="text-xs text-gray-500">{{ $appointment->appointment_time }}</div>
                            </td>

                            {{-- Visit Stage --}}
                            <td>
                                @if($appointment->visit_stage)
                                    @php
                                        $stageColors = [
                                            'waiting' => 'bg-yellow-100 text-yellow-800',
                                            'in_consultation' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $stageColor = $stageColors[$appointment->visit_stage] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="badge-status {{ $stageColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->visit_stage)) }}
                                    </span>
                                @else
                                    <span class="text-gray-500 font-medium">-</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @can('update.appointments')
                                    <button class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50 transition-colors" wire:click="editAppointment({{ $appointment->id }})" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    @endcan

                                    @can('create.invoices')
                                    <a href="{{ route('invoices.index', ['create' => 1, 'patient' => $appointment->patient_id]) }}" class="text-green-600 hover:text-green-900 p-1 rounded-md hover:bg-green-50 transition-colors" title="Add Invoice">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.5 0-3 .75-3 2.25C9 11.75 10.5 12.5 12 12.5s3 .75 3 2.25S13.5 17 12 17m0-9V7m0 10v1m9-7a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </a>
                                    @endcan

                                    @can('delete.appointments')
                                    <button class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors" wire:click="deleteAppointment({{ $appointment->id }})" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="empty-state">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h3>No Appointments</h3>
                                    <p>No appointments match the selected filter.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            {{-- Empty State --}}
            <div class="card-body">
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3>
                        @if($dateTab === 'today')
                            No Appointments Today
                        @elseif($dateTab === 'tomorrow')
                            No Appointments Tomorrow
                        @else
                            No Appointments This Week
                        @endif
                    </h3>
                    <p>No appointments scheduled for the selected period.</p>
                    @can('create.appointments')
                    <div class="mt-6">
                        <a href="{{ route('appointments.index') }}?create=1" class="btn-add btn-action flex items-center gap-2 inline-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Add New Appointment
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        @endif
    </div>
</div>
