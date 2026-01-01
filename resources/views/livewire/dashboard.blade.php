<div>
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-base-content">Dashboard</h1>
                <p class="text-base-content/70 mt-1">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="btn btn-sm btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button class="btn btn-sm btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Patients --}}
        <div class="card bg-base-100 shadow-md border border-base-300 hover:shadow-lg transition-shadow">
            <div class="card-body p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-base-content/60 font-medium mb-1">Total Patients</p>
                        <p class="text-2xl font-bold text-primary">{{ $stats['total_patients'] }}</p>
                    </div>
                    <div class="bg-primary/10 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Today's Appointments --}}
        <div class="card bg-base-100 shadow-md border border-base-300 hover:shadow-lg transition-shadow">
            <div class="card-body p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-base-content/60 font-medium mb-1">Today's Appointments</p>
                        <p class="text-2xl font-bold text-secondary">{{ $stats['today_appointments'] }}</p>
                    </div>
                    <div class="bg-secondary/10 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="card bg-base-100 shadow-md border border-base-300 hover:shadow-lg transition-shadow">
            <div class="card-body p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-base-content/60 font-medium mb-1">Total Revenue</p>
                        <p class="text-2xl font-bold text-success">{{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                    <div class="bg-success/10 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Invoices --}}
        <div class="card bg-base-100 shadow-md border border-base-300 hover:shadow-lg transition-shadow">
            <div class="card-body p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-base-content/60 font-medium mb-1">Pending Invoices</p>
                        <p class="text-2xl font-bold text-warning">{{ $stats['pending_invoices'] }}</p>
                    </div>
                    <div class="bg-warning/10 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Doctors' Schedules --}}
        <div class="lg:col-span-2 space-y-4">
            @forelse($doctors as $doctor)
                <div class="card bg-base-100 shadow-md border border-base-300">
                    <div class="card-body p-5">
                        <div class="flex items-center gap-3 mb-4">
                            @if($doctor->photo)
                                <div class="avatar">
                                    <div class="w-12 h-12 rounded-full">
                                        <img src="{{ asset('storage/' . $doctor->photo) }}" alt="{{ $doctor->name }}" />
                                    </div>
                                </div>
                            @else
                                <div class="avatar placeholder">
                                    <div class="bg-primary text-primary-content rounded-full w-12 h-12">
                                        <span class="text-lg font-bold">{{ substr($doctor->name, 0, 1) }}</span>
                                    </div>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-lg">{{ $doctor->name }}'s Schedule</h3>
                                @if($doctor->specialization)
                                    <p class="text-sm text-base-content/60">{{ $doctor->specialization }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-2">
                            @php
                                $doctorAppointments = $todayAppointments[$doctor->id] ?? collect();
                            @endphp
                            @forelse($doctorAppointments as $appointment)
                                <div class="flex items-center gap-3 p-3 rounded-lg border {{ $appointment->status === 'scheduled' ? 'border-l-4 border-l-primary bg-primary/5' : 'border-base-300 bg-base-200' }}">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-semibold text-sm">{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time)->format('h:i A') }}</span>
                                            @if($appointment->visit_type)
                                                <span class="badge badge-sm badge-outline">{{ $appointment->visit_type }}</span>
                                            @endif
                                        </div>
                                        <p class="text-sm font-medium text-base-content">{{ $appointment->patient->full_name }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-base-content/50 text-center py-4">No appointments scheduled for today</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="card bg-base-100 shadow-md border border-base-300">
                    <div class="card-body p-5">
                        <p class="text-base-content/50 text-center">No doctors found</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Today's Patient Queue --}}
        <div class="lg:col-span-1">
            <div class="card bg-base-100 shadow-md border border-base-300">
                <div class="card-body p-5">
                    <h2 class="text-xl font-bold mb-4">Today's Patient Queue</h2>
                    <div class="space-y-3">
                        @forelse($todayQueue as $appointment)
                            @php
                                $statusColor = match($appointment->status) {
                                    'completed' => 'bg-success',
                                    'scheduled' => 'bg-primary',
                                    default => 'bg-warning',
                                };
                                $statusText = match($appointment->status) {
                                    'completed' => 'Completed',
                                    'scheduled' => 'In-Progress',
                                    default => 'Waiting',
                                };
                            @endphp
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-base-200 hover:bg-base-300 transition-colors">
                                <div class="avatar placeholder">
                                    <div class="bg-primary text-primary-content rounded-full w-10 h-10">
                                        <span class="text-sm font-bold">{{ substr($appointment->patient->full_name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm truncate">{{ $appointment->patient->full_name }}</p>
                                    <p class="text-xs text-base-content/60">{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time)->format('h:i A') }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $statusColor }}"></div>
                                    <span class="text-xs text-base-content/70">{{ $statusText }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-base-content/50 text-center py-4">No patients in queue today</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Appointments Table --}}
    <div class="card bg-base-100 shadow-md border border-base-300 mt-6">
        <div class="card-body p-5">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
                <div>
                    <h2 class="text-xl font-bold text-base-content">Recent Appointments</h2>
                    <p class="text-sm text-base-content/60 mt-1">Upcoming scheduled appointments</p>
                </div>
                <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    View All
                </a>
            </div>
            
            {{-- Mobile View - Cards --}}
            <div class="block md:hidden space-y-3">
                @forelse($recentAppointments as $appointment)
                <div class="card bg-base-200 shadow">
                    <div class="card-body p-3">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="font-semibold text-sm">{{ $appointment->patient->full_name }}</h3>
                                <p class="text-xs text-base-content/60 mt-1">{{ $appointment->doctor->name }}</p>
                            </div>
                            <span class="badge badge-outline badge-info badge-sm">{{ ucfirst($appointment->status) }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <span class="text-base-content/60">Date:</span>
                                <span class="font-semibold ml-1">{{ $appointment->appointment_date->format('d-m-Y') }}</span>
                            </div>
                            <div>
                                <span class="text-base-content/60">Time:</span>
                                <span class="font-semibold ml-1">{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time)->format('h:i A') }}</span>
                            </div>
                            @if($appointment->visit_type)
                            <div class="col-span-2">
                                <span class="text-base-content/60">Visit Type:</span>
                                <span class="font-semibold ml-1">{{ $appointment->visit_type }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-base-content/50 py-4 text-sm">No upcoming appointments</div>
                @endforelse
            </div>

            {{-- Desktop View - Table --}}
            <div class="hidden md:block overflow-x-auto rounded-lg border border-base-300">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th class="font-semibold text-base-content">Patient</th>
                            <th class="font-semibold text-base-content">Doctor</th>
                            <th class="font-semibold text-base-content">Date</th>
                            <th class="font-semibold text-base-content">Time</th>
                            <th class="font-semibold text-base-content">Visit Type</th>
                            <th class="font-semibold text-base-content">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAppointments as $appointment)
                        <tr class="hover:bg-base-200 transition-colors border-b border-base-300">
                            <td class="font-semibold text-base-content">{{ $appointment->patient->full_name }}</td>
                            <td class="text-base-content/80">{{ $appointment->doctor->name }}</td>
                            <td class="text-base-content/70">{{ $appointment->appointment_date->format('d-m-Y') }}</td>
                            <td class="text-base-content/70">{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time)->format('h:i A') }}</td>
                            <td>
                                <span class="badge badge-ghost badge-sm">{{ $appointment->visit_type ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-outline badge-info badge-sm font-medium">{{ ucfirst($appointment->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-base-content/50 py-4">No upcoming appointments</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
