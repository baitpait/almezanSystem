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

    {{-- Operations List --}}
    <div class="data-table-container overflow-x-auto">
        <table class="data-table min-w-full">
            <thead>
                <tr>
                    <th class="hidden md:table-cell">Date</th>
                    <th class="sticky left-0 z-10 bg-gray-50 min-w-[200px]">Patient</th>
                    <th class="hidden lg:table-cell">Doctor</th>
                    <th class="hidden lg:table-cell">Operation Type</th>
                    <th class="hidden md:table-cell">Eye</th>
                    <th>Status</th>
                    <th class="hidden xl:table-cell">Cost</th>
                    <th class="text-right sticky right-0 z-10 bg-gray-50 min-w-[180px]">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($operations as $operation)
                <tr>
                    <td class="hidden md:table-cell">
                        @if($operation->start_date)
                            <div class="font-bold text-gray-900 text-base">{{ $operation->start_date->format('d-m-Y') }}</div>
                            @if($operation->start_date->isToday())
                                <span class="badge-status bg-green-100 text-green-800 text-xs font-semibold mt-1 inline-block">Today</span>
                            @elseif($operation->start_date->isPast())
                                <span class="badge-status bg-yellow-100 text-yellow-800 text-xs font-semibold mt-1 inline-block">Past</span>
                            @else
                                <span class="badge-status bg-blue-100 text-blue-800 text-xs font-semibold mt-1 inline-block">Upcoming</span>
                            @endif
                        @else
                            <span class="text-gray-500 text-sm font-medium">Not set</span>
                        @endif
                    </td>
                    <td class="sticky left-0 z-10 bg-white">
                        <div class="font-bold text-gray-900 text-base">{{ $operation->patient->full_name }}</div>
                        @if($operation->patient->id_number)
                        <div class="text-xs text-gray-600 font-mono mt-0.5">ID: {{ $operation->patient->id_number }}</div>
                        @endif
                        @if($operation->patient->phone)
                        <div class="text-xs text-gray-600 font-medium mt-0.5">{{ $operation->patient->phone }}</div>
                        @endif
                        @if($operation->start_date)
                            <div class="md:hidden mt-1">
                                <div class="font-semibold text-gray-800 text-xs">{{ $operation->start_date->format('d-m-Y') }}</div>
                                @if($operation->start_date->isToday())
                                    <span class="badge-status bg-green-100 text-green-800 text-xs font-semibold mt-0.5 inline-block">Today</span>
                                @elseif($operation->start_date->isPast())
                                    <span class="badge-status bg-yellow-100 text-yellow-800 text-xs font-semibold mt-0.5 inline-block">Past</span>
                                @else
                                    <span class="badge-status bg-blue-100 text-blue-800 text-xs font-semibold mt-0.5 inline-block">Upcoming</span>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td class="font-medium text-gray-800 hidden lg:table-cell">{{ $operation->doctor->name ?? 'N/A' }}</td>
                    <td class="hidden lg:table-cell">
                        @php
                            $typeColors = [
                                'PRK' => 'bg-blue-100 text-blue-800',
                                'Femto-LASIK' => 'bg-purple-100 text-purple-800',
                                'SMILE' => 'bg-green-100 text-green-800',
                                'PTK' => 'bg-yellow-100 text-yellow-800',
                                'LASIK' => 'bg-indigo-100 text-indigo-800',
                                'Trans-PRK' => 'bg-cyan-100 text-cyan-800',
                            ];
                            $typeColor = $typeColors[$operation->operation_type] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="badge-status {{ $typeColor }} font-semibold">{{ $operation->operation_type }}</span>
                        @if($operation->operation_type_od && $operation->operation_type_os)
                            @if($operation->operation_type_od !== $operation->operation_type_os)
                                <div class="text-xs mt-1 space-y-0.5">
                                    <div class="text-gray-700"><span class="font-semibold">OD:</span> {{ $operation->operation_type_od }}</div>
                                    <div class="text-gray-700"><span class="font-semibold">OS:</span> {{ $operation->operation_type_os }}</div>
                                </div>
                            @endif
                        @endif
                    </td>
                    <td class="hidden md:table-cell">
                        @php
                            $eyeColors = [
                                'OD' => 'bg-blue-100 text-blue-800',
                                'OS' => 'bg-green-100 text-green-800',
                                'OU' => 'bg-indigo-100 text-indigo-800',
                            ];
                            $eyeColor = $eyeColors[$operation->operation_eye] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="badge-status {{ $eyeColor }} font-semibold">{{ $operation->operation_eye }}</span>
                    </td>
                    <td>
                        @php
                            $statusColors = [
                                'scheduled' => 'bg-blue-100 text-blue-800',
                                'in_progress' => 'bg-yellow-100 text-yellow-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'postponed' => 'bg-orange-100 text-orange-800',
                            ];
                            $statusColor = $statusColors[$operation->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="badge-status {{ $statusColor }} font-semibold">{{ ucfirst(str_replace('_', ' ', $operation->status)) }}</span>
                    </td>
                    <td class="hidden xl:table-cell">
                        @if($operation->cost > 0)
                            <span class="font-bold text-gray-900">{{ number_format($operation->cost, 2) }} SAR</span>
                        @else
                            <span class="text-gray-500 text-sm font-medium">Not set</span>
                        @endif
                    </td>
                    <td class="sticky right-0 z-10 bg-white text-right">
                        <div class="flex items-center justify-end gap-2 flex-wrap whitespace-nowrap">
                            <a href="{{ route('operations.edit', ['id' => $operation->id]) }}" class="btn-edit btn-action whitespace-nowrap" title="View Operation">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="hidden sm:inline">View</span>
                            </a>
                            @if($operation->appointment)
                                <a href="{{ route('appointments.index') }}?highlight={{ $operation->appointment->id }}" class="btn-visit btn-action whitespace-nowrap" title="View Appointment">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="hidden sm:inline">Appointment</span>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-16">
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <p class="text-gray-700 font-bold text-lg mb-2">No operations found</p>
                            <p class="text-gray-500 text-sm">Try adjusting your filters or search criteria to find operations.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    @if($operations->hasPages())
    <div class="pagination-container mt-6">
        {{ $operations->links() }}
    </div>
    @endif
</div>

