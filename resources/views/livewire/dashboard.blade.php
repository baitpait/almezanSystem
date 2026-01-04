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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Today's Appointments Professional View --}}
        @if(isset($todayAppointments) && $todayAppointments->count() > 0)
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-8">
            {{-- Professional Appointments Header --}}
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 px-6 py-8">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white font-cairo">مواعيد اليوم</h2>
                            <p class="text-blue-100 mt-1 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ today()->format('l, F j, Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        {{-- Quick Stats --}}
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 text-center border border-white/20">
                            <div class="text-2xl font-bold text-yellow-300">{{ $todayAppointments->where('visit_stage', 'waiting')->count() }}</div>
                            <div class="text-xs text-yellow-200 font-medium">في الانتظار</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 text-center border border-white/20">
                            <div class="text-2xl font-bold text-blue-300">{{ $todayAppointments->where('visit_stage', 'in_consultation')->count() }}</div>
                            <div class="text-xs text-blue-200 font-medium">في الكشف</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 text-center border border-white/20">
                            <div class="text-2xl font-bold text-green-300">{{ $todayAppointments->where('visit_stage', 'completed')->count() }}</div>
                            <div class="text-xs text-green-200 font-medium">مكتمل</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 text-center border border-white/20">
                            <div class="text-xl font-bold text-white">{{ $todayAppointments->total() }}</div>
                            <div class="text-xs text-gray-200 font-medium">المجموع</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Professional Filters Row --}}
            <div class="bg-gray-50 px-6 py-6 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Stage Filter --}}
                    <div class="form-group">
                        <label class="form-label flex items-center gap-2 font-cairo text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            مرحلة الزيارة
                        </label>
                        <select class="form-select border-gray-300 focus:border-blue-500 focus:ring-blue-500" wire:model.live="visitStageFilter">
                            <option value="">جميع المراحل</option>
                            <option value="waiting">⏳ في الانتظار</option>
                            <option value="in_consultation">👨‍⚕️ في الكشف</option>
                            <option value="completed">✅ مكتمل</option>
                            <option value="cancelled">❌ ملغي</option>
                        </select>
                    </div>

                    {{-- Visit Type Filter --}}
                    <div class="form-group">
                        <label class="form-label flex items-center gap-2 font-cairo text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            نوع الزيارة
                        </label>
                        <select class="form-select border-gray-300 focus:border-blue-500 focus:ring-blue-500" wire:model.live="visitTypeFilter">
                            <option value="">جميع الأنواع</option>
                            <option value="Assessment">🔍 تقييم</option>
                            <option value="Operation">⚕️ عملية</option>
                            <option value="Follow up">📋 متابعة</option>
                            <option value="New visit">👋 زيارة جديدة</option>
                        </select>
                    </div>

                    {{-- Doctor Filter --}}
                    <div class="form-group">
                        <label class="form-label flex items-center gap-2 font-cairo text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            الطبيب
                        </label>
                        <select class="form-select border-gray-300 focus:border-blue-500 focus:ring-blue-500" wire:model.live="doctorFilter">
                            <option value="">جميع الأطباء</option>
                            @foreach($doctors ?? [] as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Professional Appointments Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-900 uppercase tracking-wider font-cairo">
                                <div class="flex items-center justify-end gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    نوع الزيارة
                                </div>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-900 uppercase tracking-wider font-cairo">
                                <div class="flex items-center justify-end gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    الطبيب
                                </div>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-900 uppercase tracking-wider font-cairo">
                                <div class="flex items-center justify-end gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    المرحلة
                                </div>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-900 uppercase tracking-wider w-20 font-cairo">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($filteredTodayAppointments ?? $todayAppointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Visit Type --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
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
                                            title="الانتقال إلى التقييم">
                                            <span>{{ $typeIcon }}</span>
                                            <span>{{ $appointment->visit_type }}</span>
                                        </button>
                                        @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-full border {{ $typeColor }}">
                                            <span>{{ $typeIcon }}</span>
                                            <span>{{ $appointment->visit_type }}</span>
                                        </span>
                                        @endcan
                                    @elseif($appointment->visit_type === 'Operation')
                                        <a
                                            href="{{ route('operation-notes.create', ['appointmentId' => $appointment->id]) }}"
                                            class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-full border {{ $typeColor }} hover:shadow-sm transition-all cursor-pointer"
                                            title="الانتقال إلى مذكرة العملية">
                                            <span>{{ $typeIcon }}</span>
                                            <span>{{ $appointment->visit_type }}</span>
                                        </a>
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

                            {{-- Doctor --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900 font-cairo">{{ $appointment->doctor->name ?? 'غير محدد' }}</div>
                                        <div class="text-xs text-gray-500">{{ $appointment->patient->full_name }}</div>
                                    </div>
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                            </td>

                            {{-- Visit Stage --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
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
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-full border {{ $stageColor }} font-cairo">
                                        <span>{{ $stageIcon }}</span>
                                        <span>{{ ucfirst(str_replace('_', ' ', $appointment->visit_stage)) }}</span>
                                    </span>
                                @else
                                    <span class="text-gray-500 font-medium">-</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @can('update.appointments')
                                    <button class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50 transition-colors" wire:click="editAppointment({{ $appointment->id }})" title="تعديل">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    @endcan

                                    @can('create.invoices')
                                    <a href="{{ route('invoices.index', ['create' => 1, 'patient' => $appointment->patient_id]) }}" class="text-green-600 hover:text-green-900 p-1 rounded-md hover:bg-green-50 transition-colors" title="إضافة فاتورة">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.5 0-3 .75-3 2.25C9 11.75 10.5 12.5 12 12.5s3 .75 3 2.25S13.5 17 12 17m0-9V7m0 10v1m9-7a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </a>
                                    @endcan

                                    @can('delete.appointments')
                                    <button class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors" wire:click="deleteAppointment({{ $appointment->id }})" title="حذف">
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
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 font-cairo">لا توجد مواعيد اليوم</h3>
                                        <p class="text-sm text-gray-500 mt-1">جميع المواعيد مكتملة! لا توجد مواعيد مجدولة لهذا اليوم.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        {{-- No Appointments Today Message --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 font-cairo mb-2">لا توجد مواعيد اليوم</h2>
                <p class="text-gray-600 mb-6">يوم هادئ ومنظم. لا توجد مواعيد مجدولة لهذا اليوم.</p>
                @can('create.appointments')
                <a href="{{ route('appointments.index') }}?create=1" class="btn-primary-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    إضافة موعد جديد
                </a>
                @endcan
            </div>
        </div>
        @endif
    </div>
</div>
