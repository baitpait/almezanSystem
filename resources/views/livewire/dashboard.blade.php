<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    {{-- Logo Header --}}
    <div class="bg-white shadow-sm border-b border-gray-200 mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-center">
                <div class="flex items-center justify-center w-32 h-20 bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl shadow-lg border border-gray-200">
                    <img src="{{ asset('images/logo.svg') }}" alt="Golden Metal Group Logo" class="h-16 w-auto object-contain">
                </div>
            </div>
        </div>
    </div>

    {{-- Main Dashboard Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        {{-- Appointments Section with Tabs --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-8">
            {{-- Header with Tabs --}}
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 px-6 py-6">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Appointments</h2>
                            <p class="text-blue-100 mt-1">
                                @if($dateTab === 'today')
                                    Today - {{ today()->format('Y-m-d') }}
                                @elseif($dateTab === 'tomorrow')
                                    Tomorrow - {{ today()->addDay()->format('Y-m-d') }}
                                @else
                                    This Week
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Date Tabs --}}
                <div class="mt-6 flex gap-2">
                    <button wire:click="setDateTab('today')" class="px-4 py-2 rounded-lg font-medium transition-all {{ $dateTab === 'today' ? 'bg-white text-blue-600 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        Today
                    </button>
                    <button wire:click="setDateTab('tomorrow')" class="px-4 py-2 rounded-lg font-medium transition-all {{ $dateTab === 'tomorrow' ? 'bg-white text-blue-600 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        Tomorrow
                    </button>
                    <button wire:click="setDateTab('this_week')" class="px-4 py-2 rounded-lg font-medium transition-all {{ $dateTab === 'this_week' ? 'bg-white text-blue-600 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        This Week
                    </button>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-gray-50 px-6 py-6 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label class="form-label flex items-center gap-2 text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Visit Stage
                        </label>
                        <select class="form-select border-gray-300 focus:border-blue-500 focus:ring-blue-500" wire:model.live="visitStageFilter">
                            <option value="">All Stages</option>
                            <option value="waiting">Waiting</option>
                            <option value="in_consultation">In Consultation</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label flex items-center gap-2 text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Visit Type
                        </label>
                        <select class="form-select border-gray-300 focus:border-blue-500 focus:ring-blue-500" wire:model.live="visitTypeFilter">
                            <option value="">All Types</option>
                            <option value="Assessment">Assessment</option>
                            <option value="Operation">Operation</option>
                            <option value="Follow up">Follow up</option>
                            <option value="New visit">New visit</option>
                        </select>
                    </div>

                    @if(!auth()->user()->isDoctor())
                    <div class="form-group">
                        <label class="form-label flex items-center gap-2 text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Doctor
                        </label>
                        <select class="form-select border-gray-300 focus:border-blue-500 focus:ring-blue-500" wire:model.live="doctorFilter">
                            <option value="">All Doctors</option>
                            @foreach($doctors ?? [] as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Appointments by Type --}}
            @if(isset($filteredTodayAppointments) && $filteredTodayAppointments->count() > 0)
                {{-- Tabs for Visit Types --}}
                <div class="bg-white border-b border-gray-200">
                    <div class="flex gap-2 px-6 py-4 overflow-x-auto">
                        <button wire:click="$set('visitTypeFilter', '')" class="px-4 py-2 rounded-lg font-medium transition-all whitespace-nowrap {{ empty($visitTypeFilter) ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            All ({{ $filteredTodayAppointments->count() }})
                        </button>
                        <button wire:click="$set('visitTypeFilter', 'Assessment')" class="px-4 py-2 rounded-lg font-medium transition-all whitespace-nowrap {{ $visitTypeFilter === 'Assessment' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            🔍 Assessment ({{ $appointmentsByType['Assessment']->count() }})
                        </button>
                        <button wire:click="$set('visitTypeFilter', 'Operation')" class="px-4 py-2 rounded-lg font-medium transition-all whitespace-nowrap {{ $visitTypeFilter === 'Operation' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            ⚕️ Operation ({{ $appointmentsByType['Operation']->count() }})
                        </button>
                        <button wire:click="$set('visitTypeFilter', 'Follow up')" class="px-4 py-2 rounded-lg font-medium transition-all whitespace-nowrap {{ $visitTypeFilter === 'Follow up' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            📋 Follow up ({{ $appointmentsByType['Follow up']->count() }})
                        </button>
                        <button wire:click="$set('visitTypeFilter', 'New visit')" class="px-4 py-2 rounded-lg font-medium transition-all whitespace-nowrap {{ $visitTypeFilter === 'New visit' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            👋 New visit ({{ $appointmentsByType['New visit']->count() }})
                        </button>
                    </div>
                </div>

                {{-- Appointments Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider">Visit Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider">Doctor / Patient</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider">Date / Time</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider">Stage</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider w-20">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($filteredTodayAppointments as $appointment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                {{-- Visit Type --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($appointment->visit_type)
                                        @php
                                            $typeColors = [
                                                'Assessment' => 'bg-blue-100 text-blue-800 border-blue-300',
                                                'Operation' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                                'Follow up' => 'bg-purple-100 text-purple-800 border-purple-300',
                                                'New visit' => 'bg-green-100 text-green-800 border-green-300',
                                            ];
                                            $typeIcons = [
                                                'Assessment' => '🔍',
                                                'Operation' => '⚕️',
                                                'Follow up' => '📋',
                                                'New visit' => '👋',
                                            ];
                                            $typeColor = $typeColors[$appointment->visit_type] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                            $typeIcon = $typeIcons[$appointment->visit_type] ?? '📝';
                                        @endphp

                                        @if($appointment->visit_type === 'Assessment')
                                            @can('view.assessment')
                                            <button
                                                class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-full border {{ $typeColor }} hover:shadow-sm transition-all cursor-pointer"
                                                wire:click="goToAssessment({{ $appointment->id }})"
                                                title="Go to Assessment">
                                                <span>{{ $typeIcon }}</span>
                                                <span>{{ $appointment->visit_type }}</span>
                                            </button>
                                            @else
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-full border {{ $typeColor }}">
                                                <span>{{ $typeIcon }}</span>
                                                <span>{{ $appointment->visit_type }}</span>
                                            </span>
                                            @endcan
                                        @else
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-full border {{ $typeColor }}">
                                                <span>{{ $typeIcon }}</span>
                                                <span>{{ $appointment->visit_type }}</span>
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-500 font-medium">-</span>
                                    @endif
                                </td>

                                {{-- Doctor / Patient --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $appointment->doctor->name ?? 'Not Specified' }}</div>
                                            <div class="text-xs text-gray-500">{{ $appointment->patient->full_name }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Date / Time --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->appointment_date }}</div>
                                    <div class="text-xs text-gray-500">{{ $appointment->appointment_time }}</div>
                                </td>

                                {{-- Visit Stage --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($appointment->visit_stage)
                                        @php
                                            $stageColors = [
                                                'waiting' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                                'in_consultation' => 'bg-blue-100 text-blue-800 border-blue-300',
                                                'completed' => 'bg-green-100 text-green-800 border-green-300',
                                                'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                                            ];
                                            $stageIcons = [
                                                'waiting' => '⏳',
                                                'in_consultation' => '👨‍⚕️',
                                                'completed' => '✅',
                                                'cancelled' => '❌',
                                            ];
                                            $stageColor = $stageColors[$appointment->visit_stage] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                            $stageIcon = $stageIcons[$appointment->visit_stage] ?? '❓';
                                        @endphp
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-full border {{ $stageColor }}">
                                            <span>{{ $stageIcon }}</span>
                                            <span>{{ ucfirst(str_replace('_', ' ', $appointment->visit_stage)) }}</span>
                                        </span>
                                    @else
                                        <span class="text-gray-500 font-medium">-</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        @can('update.appointments')
                                        <button class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50 transition-colors" wire:click="editAppointment({{ $appointment->id }})" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        @endcan

                                        @can('create.invoices')
                                        <a href="{{ route('invoices.index', ['create' => 1, 'patient' => $appointment->patient_id]) }}" class="text-green-600 hover:text-green-900 p-1 rounded-md hover:bg-green-50 transition-colors" title="Add Invoice">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.5 0-3 .75-3 2.25C9 11.75 10.5 12.5 12 12.5s3 .75 3 2.25S13.5 17 12 17m0-9V7m0 10v1m9-7a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </a>
                                        @endcan

                                        @can('delete.appointments')
                                        <button class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors" wire:click="deleteAppointment({{ $appointment->id }})" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">No Appointments</h3>
                                            <p class="text-sm text-gray-500 mt-1">No appointments match the selected filter.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                {{-- Empty State --}}
                <div class="p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                            @if($dateTab === 'today')
                                No Appointments Today
                            @elseif($dateTab === 'tomorrow')
                                No Appointments Tomorrow
                            @else
                                No Appointments This Week
                            @endif
                        </h2>
                        <p class="text-gray-600 mb-6">A quiet and organized day. No appointments scheduled.</p>
                        <div class="flex flex-wrap gap-3 justify-center">
                            @can('create.appointments')
                            <a href="{{ route('appointments.index') }}?create=1" class="btn-add btn-action flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Add New Appointment</span>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
