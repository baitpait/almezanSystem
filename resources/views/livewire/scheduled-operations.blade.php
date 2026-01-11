<div class="container mx-auto p-4 lg:p-6">
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1>Scheduled Operations</h1>
            <p>View and manage scheduled operations</p>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="alert alert-success mb-6 shadow-lg animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-semibold">{{ session('message') }}</span>
        </div>
    @endif

    {{-- Filters --}}
    <div class="search-container">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
            {{-- Search --}}
            <div class="md:col-span-1">
                <label class="form-label">Search</label>
                <div class="search-input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Search by patient name, ID, or phone...">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="md:col-span-1">
                <label class="form-label">Status</label>
                <select class="form-select" wire:model.live="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="postponed">Postponed</option>
                </select>
            </div>

            {{-- Date Filter --}}
            <div class="md:col-span-1">
                <label class="form-label">Date Filter</label>
                <select class="form-select" wire:model.live="dateFilter">
                    <option value="upcoming">Upcoming</option>
                    <option value="today">Today</option>
                    <option value="past">Past</option>
                    <option value="all">All</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Appointments List --}}
    <div class="data-table-container overflow-x-auto">
        <table class="data-table min-w-full">
            <thead>
                <tr>
                    <th class="hidden md:table-cell">Date & Time</th>
                    <th class="sticky left-0 z-10 bg-gray-50 min-w-[200px]">Patient</th>
                    <th class="hidden lg:table-cell">Doctor</th>
                    <th class="hidden md:table-cell">Duration</th>
                    <th>Status</th>
                    <th class="text-right sticky right-0 z-10 bg-gray-50" style="min-width: 100px; max-width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                <tr>
                    <td class="hidden md:table-cell">
                        @if($appointment->appointment_date)
                            <div class="font-bold text-gray-900 text-base">{{ $appointment->appointment_date->format('d-m-Y') }}</div>
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
                            <div class="text-sm text-gray-600 font-medium mt-0.5">{{ $time->format('h:i A') }}</div>
                            @if($appointment->appointment_date->isToday())
                                <span class="badge-status bg-green-100 text-green-800 text-xs font-semibold mt-1 inline-block">Today</span>
                            @elseif($appointment->appointment_date->isPast())
                                <span class="badge-status bg-yellow-100 text-yellow-800 text-xs font-semibold mt-1 inline-block">Past</span>
                            @else
                                <span class="badge-status bg-blue-100 text-blue-800 text-xs font-semibold mt-1 inline-block">Upcoming</span>
                            @endif
                        @else
                            <span class="text-gray-500 text-sm font-medium">Not set</span>
                        @endif
                    </td>
                    <td class="sticky left-0 z-10 bg-white">
                        <div class="font-bold text-gray-900 text-base">{{ $appointment->patient->full_name }}</div>
                        @if($appointment->patient->id_number)
                        <div class="text-xs text-gray-600 font-mono mt-0.5">ID: {{ $appointment->patient->id_number }}</div>
                        @endif
                        @if($appointment->patient->phone)
                        <div class="text-xs text-gray-600 font-medium mt-0.5">{{ $appointment->patient->phone }}</div>
                        @endif
                        @if($appointment->appointment_date)
                            <div class="md:hidden mt-1">
                                <div class="font-semibold text-gray-800 text-xs">{{ $appointment->appointment_date->format('d-m-Y') }}</div>
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
                                <div class="text-xs text-gray-600">{{ $time->format('h:i A') }}</div>
                                @if($appointment->appointment_date->isToday())
                                    <span class="badge-status bg-green-100 text-green-800 text-xs font-semibold mt-0.5 inline-block">Today</span>
                                @elseif($appointment->appointment_date->isPast())
                                    <span class="badge-status bg-yellow-100 text-yellow-800 text-xs font-semibold mt-0.5 inline-block">Past</span>
                                @else
                                    <span class="badge-status bg-blue-100 text-blue-800 text-xs font-semibold mt-0.5 inline-block">Upcoming</span>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td class="font-medium text-gray-800 hidden lg:table-cell">{{ $appointment->doctor->name ?? 'N/A' }}</td>
                    <td class="hidden md:table-cell">
                        @if($appointment->duration)
                            <span class="text-gray-700 font-medium">{{ $appointment->duration }} min</span>
                        @else
                            <span class="text-gray-500 text-sm font-medium">Not set</span>
                        @endif
                    </td>
                    <td>
                        @if($appointment->operation)
                            @php
                                $statusColors = [
                                    'scheduled' => 'bg-blue-100 text-blue-800',
                                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'postponed' => 'bg-orange-100 text-orange-800',
                                ];
                                $statusColor = $statusColors[$appointment->operation->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="badge-status {{ $statusColor }} font-semibold">{{ ucfirst(str_replace('_', ' ', $appointment->operation->status)) }}</span>
                        @else
                            <span class="badge-status bg-gray-100 text-gray-800 font-semibold">No Operation</span>
                        @endif
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
                    <td colspan="6" class="text-center py-16">
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <p class="text-gray-700 font-bold text-lg mb-2">No appointments found</p>
                            <p class="text-gray-500 text-sm">Try adjusting your filters or search criteria to find appointments.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    @if($appointments->hasPages())
    <div class="pagination-container mt-6">
        {{ $appointments->links() }}
    </div>
    @endif
</div>

