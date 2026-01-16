<div class="container mx-auto p-4">
    <!-- Calendar Styles -->
    <style>
        .calendar-container {
            max-width: 100%;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }
        .calendar-day-header {
            background-color: #f3f4f6;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            color: #374151;
            border: 1px solid #e5e7eb;
        }
        .calendar-day {
            position: relative;
            min-height: 120px;
            padding: 8px;
            border: 1px solid #e5e7eb;
            background-color: white;
        }
        .calendar-day:not(.bg-white) {
            background-color: #f9fafb;
        }
        .day-number {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .appointment-item {
            font-size: 11px;
            padding: 4px;
            border-radius: 4px;
            background-color: #dbeafe;
            color: #1e40af;
            border-left: 2px solid #3b82f6;
            margin-bottom: 2px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .appointment-item:hover {
            background-color: #bfdbfe;
        }
    </style>
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
                <h1>Appointment Management</h1>
                <p>Manage and schedule patient appointments</p>
        </div>
            @can('create.appointments')
            <button class="btn-add btn-action flex items-center gap-2" wire:click="create">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Appointment
        </button>
        @endcan
        </div>
    </div>

    {{-- View Mode Buttons --}}
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <button class="{{ $viewMode === 'list' ? 'btn-primary' : 'btn-secondary' }}"
                    wire:click="setViewMode('list')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                List View
            </button>
            <button class="{{ $viewMode === 'calendar' ? 'btn-primary' : 'btn-secondary' }}"
                    wire:click="setViewMode('calendar')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Calendar View
            </button>
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

    {{-- Error Message --}}
    @if (session()->has('error'))
        <div class="alert alert-error mb-6 shadow-lg animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif
    
    {{-- List View --}}
    @if($viewMode === 'list')
        {{-- Search Bar and Filters --}}
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
                                   placeholder="Search by patient name, doctor, or visit type...">
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

            {{-- Filters Row: Stage, Visit Type, Doctor --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Visit Stage Filter --}}
                <div>
                    <label class="form-label">Stage</label>
                    <select class="form-select" wire:model.live="visitStageFilter">
                        <option value="">All Stages</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="waiting">Waiting</option>
                        <option value="in_consultation">In Consultation</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                {{-- Visit Type Filter --}}
                <div>
                    <label class="form-label">Visit Type</label>
                    <select class="form-select" wire:model.live="visitTypeFilter">
                        <option value="">All Types</option>
                        <option value="Assessment">Assessment</option>
                        <option value="Operation">Operation</option>
                        <option value="Follow up">Follow up</option>
                        <option value="New visit">New visit</option>
                    </select>
                </div>

                {{-- Doctor Filter --}}
                <div>
                    <label class="form-label">Doctor</label>
                    <select class="form-select" wire:model.live="doctorFilter">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Date Filter Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 mt-4">
            {{-- Date Filter --}}
            <div class="md:col-span-1">
                <label class="form-label">Date Filter</label>
                <select class="form-select" wire:model.live="dateFilter">
                    <option value="">All Dates</option>
                    <option value="today">Today</option>
                    <option value="this_week">This Week</option>
                    <option value="this_month">This Month</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="past">Past</option>
                    <option value="date_range">Between Dates</option>
                </select>
            </div>

            {{-- Date Range Inputs --}}
            @if($dateFilter === 'date_range')
            <div class="md:col-span-2">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label">From Date</label>
                        <input type="date" 
                               class="form-input" 
                               wire:model.live="dateFrom"
                               max="{{ $dateTo ? $dateTo : '' }}">
                    </div>
                    <div>
                        <label class="form-label">To Date</label>
                        <input type="date" 
                               class="form-input" 
                               wire:model.live="dateTo"
                               min="{{ $dateFrom ? $dateFrom : '' }}">
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Calendar View --}}
    @if($viewMode === 'calendar')
        <div class="calendar-container bg-white rounded-lg shadow-md p-6">
            <div class="calendar-header flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Doctor Appointments Calendar</h3>
                <div class="text-sm text-gray-600">
                    {{ today()->format('F Y') }}
                </div>
            </div>

            <div class="calendar-grid grid grid-cols-7 gap-1">
                {{-- Day Headers --}}
                @php
                    $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                @endphp
                @foreach($days as $day)
                    <div class="calendar-day-header bg-gray-100 p-3 text-center font-semibold text-gray-700 border">
                        {{ $day }}
                    </div>
                @endforeach

                {{-- Calendar Days --}}
                @php
                    $startOfMonth = today()->startOfMonth();
                    $endOfMonth = today()->endOfMonth();
                    $startDate = $startOfMonth->copy()->startOfWeek();
                    $endDate = $endOfMonth->copy()->endOfWeek();
                    $currentDate = $startDate->copy();
                @endphp

                @while($currentDate->lte($endDate))
                    @php
                        $isCurrentMonth = $currentDate->month === today()->month;
                        $isToday = $currentDate->isToday();
                        $dateKey = $currentDate->format('Y-m-d');
                        $dayAppointments = $calendarData[$dateKey] ?? [];
                    @endphp

                    <div class="calendar-day {{ $isCurrentMonth ? 'bg-white' : 'bg-gray-50' }} {{ $isToday ? 'ring-2 ring-blue-500' : '' }} min-h-[120px] p-2 border border-gray-200 hover:bg-gray-50 transition-colors">
                        <div class="day-number text-sm font-medium {{ $isCurrentMonth ? 'text-gray-900' : 'text-gray-400' }} mb-2">
                            {{ $currentDate->format('j') }}
                        </div>

                        @if(count($dayAppointments) > 0)
                            <div class="appointments space-y-1">
                                @foreach($dayAppointments as $appointment)
                                    <div class="appointment-item text-xs p-1 rounded bg-blue-100 text-blue-800 border-l-2 border-blue-500"
                                         title="{{ $appointment->patient->full_name }} - {{ $appointment->visit_type }} - {{ $appointment->appointment_time }}">
                                        <div class="font-medium">{{ $appointment->appointment_time }}</div>
                                        <div class="truncate">{{ $appointment->patient->full_name }}</div>
                                        <div class="text-blue-600">{{ $appointment->doctor->name }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @php
                        $currentDate->addDay();
                    @endphp
                @endwhile
            </div>
        </div>
    @endif

    {{-- Appointments List --}}
    @if($viewMode === 'list')
        <div class="data-table-container overflow-x-auto">
        <table class="data-table min-w-full">
                    <thead>
                <tr>
                            <th>Visit Type</th>
                    <th class="sticky left-0 z-10 bg-gray-50 min-w-[200px]">Patient</th>
                    <th class="min-w-[180px]">Doctor</th>
                    <th style="min-width: 150px;">Date & Time</th>
                            <th>Visit Stage</th>
                    <th class="text-right sticky right-0 z-10 bg-gray-50 w-[8%] min-w-[50px]">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                <tr>
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
                            <div class="flex items-center gap-2 flex-wrap">
                                        @if($appointment->visit_type === 'Assessment')
                                    @can('view.assessment')
                                    <button 
                                        class="badge-status {{ $typeColor }} cursor-pointer hover:opacity-80 transition-opacity" 
                                        wire:click="goToAssessment({{ $appointment->id }})" 
                                        title="Click to go to Assessment">
                                        {{ $appointment->visit_type }}
                                            </button>
                                    @else
                                    <span class="badge-status {{ $typeColor }}">
                                        {{ $appointment->visit_type }}
                                    </span>
                                    @endcan
                                        @elseif($appointment->visit_type === 'Operation')
                                    <span class="badge-status {{ $typeColor }}">
                                        {{ $appointment->visit_type }}
                                    </span>
                                @else
                                    <span class="badge-status {{ $typeColor }}">{{ $appointment->visit_type }}</span>
                                        @endif
                                    </div>
                                @else
                            <span class="text-gray-500 font-medium">-</span>
                        @endif
                    </td>
                    <td class="sticky left-0 z-10 bg-white">
                        <div class="font-bold text-gray-900 text-base">{{ $appointment->patient->full_name }}</div>
                    </td>
                    <td class="font-medium text-gray-800">{{ $appointment->doctor->name ?? 'N/A' }}</td>
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
                                            // Try H:i:s format first (07:24:00)
                                            $time = \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time);
                                        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                                            // Fallback to H:i format (07:24)
                                            try {
                                                $time = \Carbon\Carbon::createFromFormat('H:i', $appointment->appointment_time);
                                            } catch (\Carbon\Exceptions\InvalidFormatException $e2) {
                                                // If both fail, try parsing as time string
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
                                @if($appointment->visit_stage)
                                    @php
                                        $stageColors = [
                                    'scheduled' => 'bg-purple-100 text-purple-800',
                                    'waiting' => 'bg-yellow-100 text-yellow-800',
                                    'in_consultation' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                $stageColor = $stageColors[$appointment->visit_stage] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                            <span class="badge-status {{ $stageColor }} whitespace-nowrap">{{ ucfirst(str_replace('_', ' ', $appointment->visit_stage)) }}</span>
                                @else
                            <span class="text-gray-500 font-medium">-</span>
                                @endif
                            </td>
                    <td class="sticky right-0 z-10 bg-white text-right">
                        <div class="relative inline-block" data-dropdown-container="{{ $appointment->id }}">
                            <button type="button" 
                                    class="btn btn-sm btn-ghost" 
                                    onclick="toggleSimpleDropdown({{ $appointment->id }}, event)"
                                    data-dropdown-trigger="{{ $appointment->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <div class="simple-dropdown-menu" 
                                 id="dropdown-menu-{{ $appointment->id }}"
                                 data-dropdown-menu="{{ $appointment->id }}"
                                 data-original-parent="{{ $appointment->id }}"
                                 style="display: none;">
                                <ul class="dropdown-menu-list">
                                    @can('update.appointments')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-edit" wire:click="edit({{ $appointment->id }})" onclick="closeSimpleDropdown({{ $appointment->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </button>
                                    </li>
                                    @endcan
                                    @if($canViewInvoices ?? false)
                                    <li>
                                        <a href="{{ route('invoices.index', ['patient' => $appointment->patient_id]) }}"
                                           class="dropdown-menu-item dropdown-menu-item-view"
                                           onclick="closeSimpleDropdown({{ $appointment->id }});">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span>Invoices</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($canCreateInvoices ?? auth()->user()->can('create.invoices'))
                                    <li>
                                        <a href="{{ route('invoices.index', ['create' => 1, 'patient' => $appointment->patient_id]) }}"
                                           class="dropdown-menu-item dropdown-menu-item-visit"
                                           onclick="closeSimpleDropdown({{ $appointment->id }});">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span>Add Invoice</span>
                                        </a>
                                    </li>
                                    @endif
                                    @can('delete.appointments')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-delete" wire:click="delete({{ $appointment->id }})" wire:confirm="Are you sure you want to delete this appointment?" onclick="closeSimpleDropdown({{ $appointment->id }})">
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
                    <td colspan="6" class="empty-state" style="grid-column: 1 / -1;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                        <h3>No appointments found</h3>
                        <p>Start by adding your first appointment</p>
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
    @endif

    {{-- Modal for Create/Edit Appointment --}}
    @if($showModal)
    <div class="modal-overlay" wire:click.self="resetForm">
        <div class="modal-container max-w-6xl max-h-[90vh] overflow-y-auto">
            <div class="modal-header">
                <h2 class="modal-title">{{ $editingId ? 'Edit Appointment' : 'New Appointment' }}</h2>
                <button class="btn-cancel btn-action flex items-center justify-center" wire:click="resetForm" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">

            <form wire:submit.prevent="save" autocomplete="off">
                {{-- Patient Selection Section --}}
                    <div class="card-modern mb-6">
                        <div class="card-header">
                            <h3 class="font-semibold text-base flex items-center gap-2 text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Patient Information
                        </h3>
                        </div>
                        <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Patient Search --}}
                            @if(!$patientPreSelected)
                                <div class="form-group md:col-span-2">
                                    <label class="form-label">Search Patient</label>
                                <div class="flex gap-2">
                                        <div class="search-input-wrapper flex-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        <input type="text" 
                                                class="form-input" 
                                            wire:model.live.debounce.300ms="patientSearch" 
                                            placeholder="Search by name, ID number, or phone...">
                                        </div>
                                        <button type="button" class="btn-add btn-action flex items-center gap-2" wire:click="openPatientModal">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                            Add Patient
                                    </button>
                                </div>
                                
                                {{-- Patient Search Results --}}
                                @if(!empty($patientSearch) && count($patients) > 0 && !$selectedPatientId)
                                    <div class="mt-2 border border-gray-300 rounded-lg max-h-48 overflow-y-auto bg-white shadow-lg z-50 relative" wire:ignore.self>
                                    @foreach($patients as $patient)
                                        <div class="p-3 hover:bg-blue-50 cursor-pointer border-b border-gray-200 last:border-0 transition-colors select-none" 
                                         wire:key="patient-{{ $patient->id }}"
                                         wire:click.prevent.stop="selectPatient({{ $patient->id }})"
                                         style="user-select: none; -webkit-user-select: none;">
                                            <div class="font-semibold text-gray-900">{{ $patient->full_name }}</div>
                                        @if($patient->id_number)
                                        <div class="mt-1">
                                                <span class="text-xs font-mono bg-gray-100 text-gray-700 px-2 py-1 rounded">ID: {{ $patient->id_number }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                                
                                @error('form.patient_id') 
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif
                            
                            {{-- Selected Patient Badge --}}
                            @if($selectedPatientId)
                                <div class="form-group md:col-span-2">
                                    <div class="alert alert-success py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-xs font-semibold">Selected: {{ $patientSearch }}</span>
                                    </div>
                                </div>
                                @endif

                            {{-- Patient Details (Read-only) --}}
                                <div class="form-group">
                                    <label class="form-label">Patient Name</label>
                                    <input type="text" class="form-input bg-gray-50" 
                                    value="{{ $patientSearch }}" disabled>
                            </div>

                                <div class="form-group">
                                    <label class="form-label">Last Visit Date</label>
                                    <input type="text" class="form-input bg-gray-50" 
                                    value="{{ $selectedPatientData['last_visit'] ?? 'N/A' }}" disabled>
                                @if(isset($selectedPatientData['days_between']) && $selectedPatientData['days_between'] !== null)
                                        <p class="text-xs mt-1 font-semibold {{ $selectedPatientData['days_between'] == 0 ? 'text-yellow-600' : 'text-blue-600' }}">
                                        @if($selectedPatientData['days_between'] == 0)
                                            ⚠️ Same day
                                        @elseif($selectedPatientData['days_between'] == 1)
                                            📅 1 day between visits
                                        @else
                                            📅 {{ $selectedPatientData['days_between'] }} days between visits
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Appointment Details Section --}}
                    <div class="card-modern mb-6">
                        <div class="card-header">
                            <h3 class="font-semibold text-base flex items-center gap-2 text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Appointment Details
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Schedule Date *</label>
                                    <input type="date" class="form-input" 
                                        wire:model="form.appointment_date" required>
                                    @error('form.appointment_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                                <div class="form-group">
                                    <label class="form-label">Time *</label>
                                    <input type="time" class="form-input" 
                                    wire:model="form.appointment_time" required>
                                    @error('form.appointment_time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                                <div class="form-group">
                                    <label class="form-label">Duration (minutes) *</label>
                                    <input type="number" class="form-input" 
                                wire:model="form.duration" min="5" max="480" value="30" required>
                                    @error('form.duration') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                        </div>
                    </div>
                </div>

                    {{-- Doctor, Visit Stage and Visit Type in one row --}}
                    <div class="card-modern mb-6">
                        <div class="card-header">
                            <h3 class="font-semibold text-base flex items-center gap-2 text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                                Doctor & Visit Details
                    </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Doctor *</label>
                                    <select class="form-select w-full" wire:model="form.doctor_id" required style="min-width: 100%;">
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                                    @error('form.doctor_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                                <div class="form-group">
                                    <label class="form-label">Visit Stage</label>
                                    <select class="form-select w-full" wire:model.defer="form.visit_stage" style="min-width: 100%;" {{ $editingId && in_array($form['visit_stage'] ?? '', ['completed', 'cancelled']) ? '' : 'disabled' }}>
                                        <option value="">Auto (Based on Date)</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="waiting">Waiting</option>
                            <option value="in_consultation">In Consultation</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                                    <p class="text-xs text-gray-500 mt-1">Visit stage is automatically set based on appointment date. You can manually set it to "Completed" or "Cancelled".</p>
                                    @error('form.visit_stage') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                                <div class="form-group">
                                    <label class="form-label">Visit Type *</label>
                                    <select class="form-select w-full" wire:model.live="form.visit_type" required style="min-width: 100%;">
                            <option value="">Select Visit Type</option>
                            <option value="Assessment">Assessment</option>
                            <option value="Operation">Operation</option>
                            <option value="Follow up">Follow up</option>
                            <option value="New visit">New visit</option>
                        </select>
                                    @error('form.visit_type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        
                        {{-- Warning if changing from Assessment with data --}}
                        @if($showOperationWarning && $operationHasData)
                            <div class="alert alert-warning mt-4 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="text-xs">
                                <strong>تحذير:</strong> هذا الموعد مرتبط بعملية تحتوي على بيانات. سيتم إلغاء الربط فقط (البيانات محفوظة).
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                    {{-- Notes Section --}}
                    <div class="form-group mb-6">
                        <label class="form-label">Notes</label>
                        <textarea class="form-input resize-none" 
                        wire:model="form.notes" rows="3" placeholder="Additional notes..."></textarea>
                        @error('form.notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel btn-action" wire:click="resetForm">Cancel</button>
                        <button type="submit" class="btn-add btn-action flex items-center gap-2">
                            @if($editingId)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Update Appointment
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                                Create Appointment
                            @endif
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
    @endif

    {{-- Quick Add Patient Modal --}}
    @if($showPatientModal)
    <div class="modal-overlay" wire:click.self="closePatientModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Add Patient</h2>
                <button class="btn-cancel btn-action flex items-center justify-center" wire:click="closePatientModal" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
            <form wire:submit.prevent="savePatient" autocomplete="off">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group md:col-span-2">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-input" wire:model.defer="patientForm.full_name" required maxlength="255">
                            @error('patientForm.full_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                        <div class="form-group">
                            <label class="form-label">ID Number *</label>
                            <input type="text" class="form-input" wire:model.defer="patientForm.id_number" maxlength="50" required>
                            @error('patientForm.id_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                        <div class="form-group">
                            <label class="form-label">Date of Birth *</label>
                            <input type="date" class="form-input" wire:model.defer="patientForm.date_of_birth" required>
                            @error('patientForm.date_of_birth') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                        <div class="form-group">
                            <label class="form-label">Gender *</label>
                            <select class="form-select" wire:model.defer="patientForm.gender" required>
                                <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                            @error('patientForm.gender') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                        <div class="form-group">
                            <label class="form-label">City *</label>
                            <input type="text" class="form-input" wire:model.defer="patientForm.city" maxlength="255" required>
                            @error('patientForm.city') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                        <div class="form-group">
                            <label class="form-label">Occupation *</label>
                            <input type="text" class="form-input" wire:model.defer="patientForm.occupation" maxlength="255" required placeholder="Occupation">
                            @error('patientForm.occupation') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                        <div class="form-group">
                            <label class="form-label">Phone *</label>
                            <input type="tel" class="form-input" wire:model.defer="patientForm.phone" maxlength="32" required pattern="[\d٠١٢٣٤٥٦٧٨٩+\-\s()]+" inputmode="numeric" placeholder="Numbers only (Arabic or English)">
                            @error('patientForm.phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                        <div class="form-group">
                            <label class="form-label">Phone (2)</label>
                            <input type="tel" class="form-input" wire:model.defer="patientForm.phone_secondary" maxlength="32" pattern="[\d٠١٢٣٤٥٦٧٨٩+\-\s()]+" inputmode="numeric" placeholder="Numbers only (Arabic or English)">
                            @error('patientForm.phone_secondary') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                        <div class="form-group md:col-span-2">
                            <label class="form-label">Notes</label>
                            <textarea class="form-input resize-none" rows="3" wire:model.defer="patientForm.notes" maxlength="1000" style="padding: 0.5rem 0.75rem; line-height: 1.5; min-height: auto;"></textarea>
                            @error('patientForm.notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel btn-action" wire:click="closePatientModal">Cancel</button>
                        <button type="submit" class="btn-add btn-action flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Patient
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
    @endif

    {{-- Modal for Add Invoice --}}
    @if($showInvoiceModal)
    <div class="modal modal-open">
        <div class="modal-box max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-base-300">
                <div>
                    <h2 class="text-xl font-bold">Create Invoice</h2>
                    <p class="text-xs text-base-content/70 mt-0.5">Generate invoice for this appointment</p>
                </div>
                <button class="btn btn-xs btn-circle btn-ghost" wire:click="closeInvoiceModal">✕</button>
            </div>

            <form wire:submit.prevent="saveInvoice" autocomplete="off">
                <div class="mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Invoice Date <span class="text-error">*</span></span>
                    </label>
                    <input type="date" class="input input-bordered w-full" wire:model.defer="invoiceForm.invoice_date" required>
                    @error('invoiceForm.invoice_date') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Amounts Section --}}
                <div class="mb-4">
                    <h3 class="font-semibold text-lg mb-3">Amounts</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Subtotal</span>
                            </label>
                            <input type="number" step="0.01" class="input input-bordered w-full" 
                                   wire:model.live="invoiceForm.subtotal" placeholder="0.00">
                            @error('invoiceForm.subtotal') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Discount</span>
                            </label>
                            <input type="number" step="0.01" class="input input-bordered w-full" 
                                   wire:model.live="invoiceForm.discount" placeholder="0.00">
                            @error('invoiceForm.discount') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Tax</span>
                            </label>
                            <input type="number" step="0.01" class="input input-bordered w-full" 
                                   wire:model.live="invoiceForm.tax" placeholder="0.00">
                            @error('invoiceForm.tax') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Total Amount</span>
                            </label>
                            <input type="text" class="input input-bordered w-full font-bold text-lg" 
                                   value="{{ number_format($invoiceForm['total_amount'], 2) }}" readonly>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Paid Amount</span>
                            </label>
                            <input type="number" step="0.01" class="input input-bordered w-full" 
                                   wire:model.live="invoiceForm.paid_amount" placeholder="0.00">
                            @error('invoiceForm.paid_amount') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Remaining Amount</span>
                            </label>
                            <input type="text" class="input input-bordered w-full font-bold text-lg text-warning" 
                                   value="{{ number_format($invoiceForm['remaining_amount'], 2) }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Status <span class="text-error">*</span></span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="invoiceForm.status">
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                            <option value="draft">Draft</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('invoiceForm.status') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Payment Method</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="invoiceForm.payment_method">
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                        @error('invoiceForm.payment_method') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Notes</span>
                    </label>
                    <textarea class="textarea textarea-bordered w-full" 
                              wire:model.defer="invoiceForm.notes" 
                              rows="3" 
                              placeholder="Additional notes..."></textarea>
                    @error('invoiceForm.notes') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" wire:click="closeInvoiceModal">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Create Invoice
                    </button>
                </div>
            </form>
        </div>
        <div class="modal-backdrop" wire:click="closeInvoiceModal"></div>
    </div>
    @endif
</div>
