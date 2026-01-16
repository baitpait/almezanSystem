<div class="container mx-auto p-4">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('operations.index') }}" class="btn btn-sm btn-ghost mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
                <h1>{{ $editingId ? 'Assessment' : 'New Assessment' }}</h1>
                <p>Complete all required information for the pre-operative assessment</p>
            </div>
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

    {{-- Main Form Card --}}
    <div class="card-modern">
        {{-- Professional Tabs Navigation --}}
        <div class="card-body">
                <div class="flex items-center gap-2 sm:gap-3 overflow-x-auto px-4 pb-2" style="scrollbar-width: thin; scrollbar-color: #cbd5e1 #f1f5f9; -webkit-overflow-scrolling: touch;">
                    <button wire:click="setTab('basic')" 
                            class="flex items-center gap-1 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm whitespace-nowrap transition-all duration-200 flex-shrink-0 min-w-fit font-semibold {{ $activeTab === 'basic' ? 'bg-blue-50 text-black shadow-sm border-2 border-blue-300' : 'bg-gray-100 text-black hover:bg-gray-200 border-2 border-transparent' }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="hidden sm:inline">Basic Info</span>
                        <span class="sm:hidden">Basic</span>
                    </button>
                    <button wire:click="setTab('refractive')" 
                            class="flex items-center gap-1 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm whitespace-nowrap transition-all duration-200 flex-shrink-0 min-w-fit font-semibold {{ $activeTab === 'refractive' ? 'bg-blue-50 text-black shadow-sm border-2 border-blue-300' : 'bg-gray-100 text-black hover:bg-gray-200 border-2 border-transparent' }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Refractive
                    </button>
                    <button wire:click="setTab('medical')" 
                            class="flex items-center gap-1 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm whitespace-nowrap transition-all duration-200 flex-shrink-0 min-w-fit font-semibold {{ $activeTab === 'medical' ? 'bg-blue-50 text-black shadow-sm border-2 border-blue-300' : 'bg-gray-100 text-black hover:bg-gray-200 border-2 border-transparent' }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="hidden md:inline">Medical History</span>
                        <span class="md:hidden">Medical</span>
                    </button>
                    <button wire:click="setTab('exam')" 
                            class="flex items-center gap-1 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm whitespace-nowrap transition-all duration-200 flex-shrink-0 min-w-fit font-semibold {{ $activeTab === 'exam' ? 'bg-blue-50 text-black shadow-sm border-2 border-blue-300' : 'bg-gray-100 text-black hover:bg-gray-200 border-2 border-transparent' }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Eye Exam
                    </button>
                    <button wire:click="setTab('ectasia')" 
                            class="flex items-center gap-1 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm whitespace-nowrap transition-all duration-200 flex-shrink-0 min-w-fit font-semibold {{ $activeTab === 'ectasia' ? 'bg-blue-50 text-black shadow-sm border-2 border-blue-300' : 'bg-gray-100 text-black hover:bg-gray-200 border-2 border-transparent' }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span class="hidden lg:inline">Ectasia Risk</span>
                        <span class="lg:hidden">Ectasia</span>
                    </button>
                    <button wire:click="setTab('recommendation')" 
                            class="flex items-center gap-1 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm whitespace-nowrap transition-all duration-200 flex-shrink-0 min-w-fit font-semibold {{ $activeTab === 'recommendation' ? 'bg-blue-50 text-black shadow-sm border-2 border-blue-300' : 'bg-gray-100 text-black hover:bg-gray-200 border-2 border-transparent' }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="hidden lg:inline">Recommendation</span>
                        <span class="lg:hidden">Rec</span>
                    </button>
                    <button wire:click="setTab('files')" 
                            class="flex items-center gap-1 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm whitespace-nowrap transition-all duration-200 flex-shrink-0 min-w-fit font-semibold {{ $activeTab === 'files' ? 'bg-blue-50 text-black shadow-sm border-2 border-blue-300' : 'bg-gray-100 text-black hover:bg-gray-200 border-2 border-transparent' }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Files</span>
                    </button>
                </div>
            </div>

        {{-- Tab Content --}}
        <div class="mt-6 px-4">

                {{-- Basic Info Tab --}}
                @if($activeTab === 'basic')
                    <div class="space-y-6">
                        <div class="card-modern">
                            <div class="card-header">
                                <h3 class="font-semibold text-base flex items-center gap-2 text-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Patient Information
                                </h3>
                            </div>
                            <div class="card-body">
                            
                            @php
                                $patient = null;
                                if ($editingId) {
                                    $operation = \App\Models\Operation::find($editingId);
                                    $patient = $operation?->patient;
                                } elseif ($selectedPatientId) {
                                    $patient = \App\Models\Patient::find($selectedPatientId);
                                }
                            @endphp

                            @if($patient)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label">Full Name</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 font-medium">
                                            {{ $patient->full_name }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">ID Number</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 font-mono">
                                            {{ $patient->id_number ?? '-' }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Date of Birth</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                            {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('Y-m-d') : '-' }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Gender</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $patient->gender === 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                {{ ucfirst($patient->gender ?? '-') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">City</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                            {{ $patient->city ?? '-' }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Occupation</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                            {{ $patient->occupation ?? '-' }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                            {{ $patient->phone ?? '-' }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Phone (2)</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                                            {{ $patient->phone_secondary ?? '-' }}
                                        </div>
                                    </div>

                                    @if($patient->notes)
                                    <div class="form-group md:col-span-2">
                                        <label class="form-label">Notes</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 whitespace-pre-wrap">
                                            {{ $patient->notes }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <div class="empty-state">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <h3>No Patient Selected</h3>
                                    <p>Patient information will be displayed here once an operation is selected.</p>
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Other Tabs --}}
                @if($activeTab === 'refractive')
                    @include('livewire.operation-manager.tabs.refractive')
                @endif


                @if($activeTab === 'medical')
                    @include('livewire.operation-manager.tabs.medical')
                @endif

                @if($activeTab === 'exam')
                    @include('livewire.operation-manager.tabs.exam')
                @endif

                @if($activeTab === 'ectasia')
                    @include('livewire.operation-manager.tabs.ectasia')
                @endif

                @if($activeTab === 'recommendation')
                    @include('livewire.operation-manager.tabs.recommendation')
                @endif

                @if($activeTab === 'files')
                    @include('livewire.operation-manager.tabs.files')
                @endif
            </div>

            {{-- Footer Actions --}}
            <div class="card-footer">
                <div class="flex justify-end gap-3">
                    <a href="{{ route('operations.index') }}" class="btn-cancel btn-action">
                        Cancel
                    </a>
                    <button type="button" 
                            wire:click="save" 
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="btn-add btn-action flex items-center gap-2">
                        <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Save
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-sm"></span>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

