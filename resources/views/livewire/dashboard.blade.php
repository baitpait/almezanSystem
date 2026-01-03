<div>
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-base-content">
                    Welcome back, {{ auth()->user()->name }}
                </h1>
                <p class="text-base-content/70 mt-1">
                    {{ now()->format('l, d F Y') }} •
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                        System Active
                    </span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                {{-- Quick Actions based on role --}}
                @if($userRole === 'admin')
                    @can('create.patients')
                    <a href="{{ route('patients.index') }}?create=1" class="btn btn-sm btn-primary gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Patient
                    </a>
                    @endcan
                @elseif($userRole === 'doctor')
                    @can('create.appointments')
                    <a href="{{ route('appointments.index') }}?create=1" class="btn btn-sm btn-primary gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Schedule Appointment
                    </a>
                    @endcan
                @else
                    @can('create.appointments')
                    <a href="{{ route('appointments.index') }}?create=1" class="btn btn-sm btn-primary gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        New Appointment
                    </a>
                    @endcan
                @endif
            </div>
        </div>
    </div>

    {{-- Statistics Cards - Role Based --}}
    @if($userRole === 'admin')
        {{-- Admin Statistics --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
            {{-- Total Patients --}}
            <div class="card bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200 hover:shadow-lg transition-all duration-200">
                <div class="card-body p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs text-blue-600 font-semibold uppercase tracking-wider mb-1">Patients</p>
                            <p class="text-2xl font-bold text-blue-900">{{ number_format($stats['total_patients'] ?? 0) }}</p>
                            <p class="text-xs text-blue-600 mt-1">Total registered</p>
                        </div>
                        <div class="bg-blue-500 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Today's Appointments --}}
            <div class="card bg-gradient-to-br from-green-50 to-green-100 border-green-200 hover:shadow-lg transition-all duration-200">
                <div class="card-body p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs text-green-600 font-semibold uppercase tracking-wider mb-1">Today</p>
                            <p class="text-2xl font-bold text-green-900">{{ $stats['today_appointments'] ?? 0 }}</p>
                            <p class="text-xs text-green-600 mt-1">Appointments</p>
                        </div>
                        <div class="bg-green-500 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
            </div>
        </div>
    </div>

            {{-- Active Users --}}
            <div class="card bg-gradient-to-br from-indigo-50 to-indigo-100 border-indigo-200 hover:shadow-lg transition-all duration-200">
                <div class="card-body p-4">
                    <div class="flex-1">
                        <p class="text-xs text-indigo-600 font-semibold uppercase tracking-wider mb-1">Users</p>
                        <p class="text-2xl font-bold text-indigo-900">{{ $stats['active_users'] ?? 0 }}</p>
                        <p class="text-xs text-indigo-600 mt-1">Active staff</p>
                    </div>
                </div>
            </div>

            {{-- Scheduled Operations --}}
            <div class="card bg-gradient-to-br from-red-50 to-red-100 border-red-200 hover:shadow-lg transition-all duration-200">
                <div class="card-body p-4">
                    <div class="flex-1">
                        <p class="text-xs text-red-600 font-semibold uppercase tracking-wider mb-1">Operations</p>
                        <p class="text-2xl font-bold text-red-900">{{ $stats['scheduled_operations'] ?? 0 }}</p>
                        <p class="text-xs text-red-600 mt-1">Scheduled</p>
                    </div>
                </div>
            </div>
        </div>

    @elseif($userRole === 'doctor')
        {{-- Doctor Statistics --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- My Today's Appointments --}}
            <div class="card bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200 hover:shadow-lg transition-all duration-200">
                <div class="card-body p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs text-blue-600 font-semibold uppercase tracking-wider mb-1">My Schedule</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $stats['my_today_appointments'] ?? 0 }}</p>
                            <p class="text-xs text-blue-600 mt-1">Today</p>
                        </div>
                        <div class="bg-blue-500 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upcoming Appointments --}}
            <div class="card bg-gradient-to-br from-green-50 to-green-100 border-green-200 hover:shadow-lg transition-all duration-200">
                <div class="card-body p-4">
                    <div class="flex-1">
                        <p class="text-xs text-green-600 font-semibold uppercase tracking-wider mb-1">Upcoming</p>
                        <p class="text-2xl font-bold text-green-900">{{ $stats['my_upcoming_appointments'] ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1">Appointments</p>
                    </div>
                </div>
            </div>

            {{-- Active Patients --}}
            <div class="card bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200 hover:shadow-lg transition-all duration-200">
                <div class="card-body p-4">
                    <div class="flex-1">
                        <p class="text-xs text-purple-600 font-semibold uppercase tracking-wider mb-1">My Patients</p>
                        <p class="text-2xl font-bold text-purple-900">{{ $stats['my_active_patients'] ?? 0 }}</p>
                        <p class="text-xs text-purple-600 mt-1">Active cases</p>
                    </div>
                </div>
            </div>

            {{-- Completed Assessments --}}
            <div class="card bg-gradient-to-br from-teal-50 to-teal-100 border-teal-200 hover:shadow-lg transition-all duration-200">
                <div class="card-body p-4">
                    <div class="flex-1">
                        <p class="text-xs text-teal-600 font-semibold uppercase tracking-wider mb-1">Completed</p>
                        <p class="text-2xl font-bold text-teal-900">{{ $stats['completed_assessments'] ?? 0 }}</p>
                        <p class="text-xs text-teal-600 mt-1">This month</p>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- Secretary Statistics --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Today's Appointments --}}
            <div class="card bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200 hover:shadow-lg transition-all duration-200">
                <div class="card-body p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs text-blue-600 font-semibold uppercase tracking-wider mb-1">Today's Work</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $stats['today_appointments'] }}</p>
                            <p class="text-xs text-blue-600 mt-1">Total appointments</p>
                        </div>
                        <div class="bg-blue-500 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Waiting Patients --}}
            <div class="card bg-gradient-to-br from-orange-50 to-orange-100 border-orange-200 hover:shadow-lg transition-all duration-200">
                <div class="card-body p-4">
                    <div class="flex-1">
                        <p class="text-xs text-orange-600 font-semibold uppercase tracking-wider mb-1">Waiting</p>
                        <p class="text-2xl font-bold text-orange-900">{{ $stats['waiting_patients'] ?? 0 }}</p>
                        <p class="text-xs text-orange-600 mt-1">In queue</p>
                    </div>
                </div>
            </div>

        </div>
    @endif

    {{-- Admin Alerts --}}
    @if($userRole === 'admin' && isset($alerts) && count($alerts) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            @foreach($alerts as $alert)
                <div class="alert alert-{{ $alert['type'] === 'warning' ? 'warning' : ($alert['type'] === 'danger' ? 'error' : 'info') }} shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            @if($alert['icon'] === 'clock')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif($alert['icon'] === 'exclamation-triangle')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm">{{ $alert['message'] }}</h4>
                            @if(isset($alert['action']))
                                <a href="{{ $alert['action'] }}" class="text-xs underline hover:no-underline mt-1 inline-block">View Details →</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Quick Actions for Secretary --}}
    @if($userRole !== 'admin' && $userRole !== 'doctor')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @if(isset($quickActions['can_create_patients']) && $quickActions['can_create_patients'])
                <a href="{{ route('patients.index') }}?create=1" class="card bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white shadow-lg hover:shadow-xl transition-all duration-200 group">
                    <div class="card-body p-6 text-center">
                        <div class="bg-white/20 p-3 rounded-full w-16 h-16 mx-auto mb-3 group-hover:bg-white/30 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-1">Add Patient</h3>
                        <p class="text-blue-100 text-sm">Register new patient</p>
                    </div>
                </a>
            @endif

            @if(isset($quickActions['can_create_appointments']) && $quickActions['can_create_appointments'])
                <a href="{{ route('appointments.index') }}?create=1" class="card bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white shadow-lg hover:shadow-xl transition-all duration-200 group">
                    <div class="card-body p-6 text-center">
                        <div class="bg-white/20 p-3 rounded-full w-16 h-16 mx-auto mb-3 group-hover:bg-white/30 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-1">New Appointment</h3>
                        <p class="text-green-100 text-sm">Schedule appointment</p>
                    </div>
                </a>
            @endif
        </div>
    @endif

    {{-- Main Content Grid - Role Based --}}
    @if($userRole === 'admin')
        {{-- Admin Dashboard Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Doctors' Schedules --}}
            <div class="lg:col-span-2">
                <div class="card bg-base-100 shadow-md border border-base-300">
                    <div class="card-header">
                        <h3 class="font-semibold text-base flex items-center gap-2 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Today's Doctor Schedules
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="space-y-1 max-h-96 overflow-y-auto">
                            @forelse($doctors as $doctor)
                                <div class="border-b border-base-200 last:border-b-0">
                                    <div class="p-4 hover:bg-base-50 transition-colors">
                                        <div class="flex items-center gap-3 mb-3">
                                            @if($doctor->photo)
                                                <div class="avatar">
                                                    <div class="w-10 h-10 rounded-full">
                                                        <img src="{{ asset('storage/' . $doctor->photo) }}" alt="{{ $doctor->name }}" />
                                                    </div>
                                                </div>
                                            @else
                                                <div class="avatar placeholder">
                                                    <div class="bg-primary text-primary-content rounded-full w-10 h-10">
                                                        <span class="text-sm font-bold">{{ substr($doctor->name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-sm">{{ $doctor->name }}</h4>
                                                @if($doctor->phone)
                                                    <p class="text-xs text-base-content/60">{{ $doctor->phone }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="space-y-2 pl-13">
                                            @php
                                                $doctorAppointments = $todayAppointments[$doctor->id] ?? collect();
                                            @endphp
                                            @forelse($doctorAppointments->take(3) as $appointment)
                                                <div class="flex items-center gap-2 text-xs">
                                                    <span class="font-medium text-primary">{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time)->format('h:i A') }}</span>
                                                    <span class="text-base-content/70">-</span>
                                                    <span class="truncate">{{ $appointment->patient->full_name }}</span>
                                                    @if($appointment->visit_type)
                                                        <span class="badge badge-xs badge-outline">{{ $appointment->visit_type }}</span>
                                                    @endif
                                                </div>
                                            @empty
                                                <p class="text-xs text-base-content/50">No appointments today</p>
                                            @endforelse
                                            @if($doctorAppointments->count() > 3)
                                                <p class="text-xs text-primary font-medium">+{{ $doctorAppointments->count() - 3 }} more appointments</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-base-content/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p>No doctors found</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Today's Patient Queue --}}
            <div class="lg:col-span-1">
                <div class="card bg-base-100 shadow-md border border-base-300">
                    <div class="card-header">
                        <h3 class="font-semibold text-base flex items-center gap-2 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Patient Queue
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="max-h-96 overflow-y-auto">
                            @forelse($todayQueue as $appointment)
                                @php
                                    $statusColor = match($appointment->status) {
                                        'completed' => 'bg-green-500',
                                        'scheduled' => 'bg-blue-500',
                                        default => 'bg-yellow-500',
                                    };
                                    $statusText = match($appointment->status) {
                                        'completed' => 'Completed',
                                        'scheduled' => 'In Progress',
                                        default => 'Waiting',
                                    };
                                @endphp
                                <div class="p-4 border-b border-base-200 last:border-b-0 hover:bg-base-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-primary text-primary-content rounded-full w-8 h-8">
                                                <span class="text-xs font-bold">{{ substr($appointment->patient->full_name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-sm truncate">{{ $appointment->patient->full_name }}</p>
                                            <p class="text-xs text-base-content/60">{{ $appointment->doctor->name }}</p>
                                            <p class="text-xs text-primary font-medium">{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time)->format('h:i A') }}</p>
                                        </div>
                                        <div class="flex flex-col items-end gap-1">
                                            <div class="w-2 h-2 rounded-full {{ $statusColor }}"></div>
                                            <span class="text-xs text-base-content/70">{{ $statusText }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-base-content/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm">No patients in queue</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif($userRole === 'doctor')
        {{-- Doctor Dashboard Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- My Today's Schedule --}}
            <div class="lg:col-span-1">
                <div class="card bg-base-100 shadow-md border border-base-300">
                    <div class="card-header">
                        <h3 class="font-semibold text-base flex items-center gap-2 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            My Today's Schedule
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="max-h-96 overflow-y-auto">
                            @forelse($myTodayAppointments as $appointment)
                                <div class="p-4 border-b border-base-200 last:border-b-0 hover:bg-blue-50 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="text-center min-w-16">
                                            <div class="text-lg font-bold text-primary">{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time)->format('H:i') }}</div>
                                            <div class="text-xs text-base-content/60">{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time)->format('A') }}</div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="font-semibold text-sm">{{ $appointment->patient->full_name }}</h4>
                                                @if($appointment->visit_type)
                                                    <span class="badge badge-xs badge-outline">{{ $appointment->visit_type }}</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-base-content/60">{{ $appointment->patient->phone ?? 'No phone' }}</p>
                                            @if($appointment->notes)
                                                <p class="text-xs text-base-content/70 mt-1">{{ Str::limit($appointment->notes, 50) }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center">
                                            <span class="badge badge-sm {{ $appointment->status === 'completed' ? 'badge-success' : 'badge-primary' }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-base-content/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-3 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h4 class="font-semibold text-base mb-1">No Appointments Today</h4>
                                    <p class="text-sm">Enjoy your day off!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Patients --}}
            <div class="lg:col-span-1">
                <div class="card bg-base-100 shadow-md border border-base-300">
                    <div class="card-header">
                        <h3 class="font-semibold text-base flex items-center gap-2 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Recent Patients
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="max-h-96 overflow-y-auto">
                            @forelse($recentPatients as $patient)
                                <div class="p-4 border-b border-base-200 last:border-b-0 hover:bg-purple-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-purple-500 text-white rounded-full w-10 h-10">
                                                <span class="text-sm font-bold">{{ substr($patient->full_name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-sm">{{ $patient->full_name }}</h4>
                                            <p class="text-xs text-base-content/60">{{ $patient->phone ?? 'No phone' }}</p>
                                            @if($patient->appointments && $patient->appointments->first())
                                                <p class="text-xs text-base-content/70">Last visit: {{ $patient->appointments->first()->appointment_date->format('M d, Y') }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs text-base-content/60">{{ $patient->appointments ? $patient->appointments->count() : 0 }} visits</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-base-content/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p class="text-sm">No recent patients</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- Secretary Dashboard Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Today's Appointments Overview --}}
            <div class="lg:col-span-1">
                <div class="card bg-base-100 shadow-md border border-base-300">
                    <div class="card-header">
                        <h3 class="font-semibold text-base flex items-center gap-2 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Today's Appointments
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="max-h-96 overflow-y-auto">
                            @forelse($todayQueue as $appointment)
                                <div class="p-4 border-b border-base-200 last:border-b-0 hover:bg-green-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-primary text-primary-content rounded-full w-10 h-10">
                                                <span class="text-sm font-bold">{{ substr($appointment->patient->full_name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-sm">{{ $appointment->patient->full_name }}</h4>
                                            <p class="text-xs text-base-content/60">{{ $appointment->doctor->name }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs font-medium text-primary">{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time)->format('h:i A') }}</span>
                                                @if($appointment->visit_type)
                                                    <span class="badge badge-xs badge-outline">{{ $appointment->visit_type }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="badge badge-sm {{ $appointment->status === 'completed' ? 'badge-success' : 'badge-primary' }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-base-content/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-3 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h4 class="font-semibold text-base mb-1">No Appointments Today</h4>
                                    <p class="text-sm">All caught up!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending Tasks --}}
            <div class="lg:col-span-1">
                <div class="card bg-base-100 shadow-md border border-base-300">
                    <div class="card-header">
                        <h3 class="font-semibold text-base flex items-center gap-2 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pending Tasks
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="max-h-96 overflow-y-auto">
                            @forelse($pendingInvoices as $invoice)
                                <div class="p-4 border-b border-base-200 last:border-b-0 hover:bg-yellow-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-yellow-500 text-white rounded-full w-8 h-8">
                                                <span class="text-xs font-bold">$</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-sm">{{ $invoice->patient->full_name }}</h4>
                                            <p class="text-xs text-base-content/60">Invoice #{{ $invoice->id }}</p>
                                            <p class="text-xs text-base-content/70">{{ $invoice->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-semibold text-sm text-yellow-600">{{ number_format($invoice->total_amount, 0) }}</div>
                                            <div class="text-xs text-base-content/60">{{ $invoice->status }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-base-content/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm">All invoices are paid!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
