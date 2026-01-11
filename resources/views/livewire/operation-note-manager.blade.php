<div class="container mx-auto p-4">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1>Operation Note</h1>
                <p>Record operation details and surgical notes</p>
            </div>
            @if($operationNote)
                <button wire:click="edit({{ $operationNote->id }})" class="btn-add btn-action flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Operation Note
                </button>
            @endif
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

    {{-- Appointment Info Card --}}
    @if($appointment)
        <div class="card-modern mb-6">
            <div class="card-header">
                <h3 class="text-lg font-bold text-gray-900">Appointment Information</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Patient</p>
                        <p class="text-sm font-bold text-gray-900">{{ $appointment->patient->full_name ?? 'N/A' }}</p>
                        @if($appointment->patient->id_number)
                            <p class="text-xs text-gray-600 font-mono mt-0.5">ID: {{ $appointment->patient->id_number }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Doctor</p>
                        <p class="text-sm font-bold text-gray-900">{{ $appointment->doctor->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Date</p>
                        <p class="text-sm font-bold text-gray-900">
                            @if($appointment->appointment_date)
                                {{ $appointment->appointment_date->format('d-m-Y') }}
                                @if($appointment->appointment_time)
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
                                    <span class="text-gray-600 font-normal">at {{ $time->format('h:i A') }}</span>
                                @endif
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Operation Note Form --}}
    <div class="card-modern">
        <div class="card-header">
            <h3 class="text-xl font-bold text-gray-900">Operation Details</h3>
        </div>
        <div class="card-body">
        
        <form wire:submit.prevent="save" class="space-y-6">
            {{-- Basic Information --}}
            {{-- Operation Note is always for both eyes (OU) - Always show separate sections for OD and OS --}}
            @php
                // Force operation_eye to OU for Operation Note
                $form['operation_eye'] = 'OU';
                $operationEye = 'OU';
                $isBothEyes = true;
            @endphp

            {{-- Checkbox for same operation type --}}
            <div class="bg-yellow-50 rounded-lg p-4 border-2 border-yellow-300 mb-6">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" wire:model.live="form.same_operation_type_both_eyes" class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <div class="flex-1">
                        <span class="text-sm font-bold text-gray-900">Same operation type for both eyes / نفس نوع العملية للعينين</span>
                        @if($form['same_operation_type_both_eyes'])
                            <p class="text-xs text-gray-700 mt-2 leading-relaxed">
                                <strong class="font-semibold">مفعّل:</strong> نوع العملية سيتم نسخه من العين اليمنى (OD) إلى العين اليسرى (OS) تلقائياً.<br>
                                <strong class="font-semibold">Enabled:</strong> Operation type will be automatically copied from Right Eye (OD) to Left Eye (OS).
                            </p>
                        @endif
                    </div>
                </label>
            </div>


            {{-- Operation Type Specific Fields -- Vertical layout (one below the other) --}}
            @if($form['same_operation_type_both_eyes'])
                {{-- Same Operation Type: Show single section for both eyes (OD) --}}
                @if(($form['operation_type_od'] ?? '') === 'PRK' || ($form['operation_type_od'] ?? '') === 'Femto-LASIK' || ($form['operation_type_od'] ?? '') === 'SMILE' || ($form['operation_type_od'] ?? '') === 'PTK')
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200 mb-4">
                        <div class="bg-white rounded-lg p-3 border border-gray-200 mb-3">
                            <p class="text-sm font-semibold text-gray-800 text-center">
                                <strong>نفس نوع العملية للعينين (OD & OS) / Same operation type for both eyes (OD & OS)</strong>
                            </p>
                        </div>
                        
                        {{-- Operation Type dropdown inside shared section --}}
                        <div class="form-group">
                            <label class="form-label">Operation Type (OD & OS) *</label>
                            <select wire:model.live="form.operation_type_od" class="form-select">
                                <option value="">Select Operation Type</option>
                                <option value="PRK">PRK (Photorefractive Keratectomy)</option>
                                <option value="Femto-LASIK">Femto-LASIK (Femtosecond LASIK)</option>
                                <option value="SMILE">SMILE (Small Incision Lenticule Extraction)</option>
                                <option value="PTK">PTK (Phototherapeutic Keratectomy)</option>
                            </select>
                            @error('form.operation_type_od') <span class="text-red-600 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        @if(($form['operation_type_od'] ?? '') === 'PRK')
                            @include('livewire.operation-note-manager.tabs.partials.operation-prk', ['eye' => 'od'])
                        @endif

                        @if(($form['operation_type_od'] ?? '') === 'Femto-LASIK')
                            @include('livewire.operation-note-manager.tabs.partials.operation-femto', ['eye' => 'od'])
                        @endif

                        @if(($form['operation_type_od'] ?? '') === 'SMILE')
                            @include('livewire.operation-note-manager.tabs.partials.operation-smile', ['eye' => 'od'])
                        @endif

                        @if(($form['operation_type_od'] ?? '') === 'PTK')
                            @include('livewire.operation-note-manager.tabs.partials.operation-ptk', ['eye' => 'od'])
                        @endif
                    </div>
                @else
                    {{-- Empty placeholder when no operation type selected --}}
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200 border-dashed mb-4">
                        <div class="bg-white rounded-lg p-3 border border-gray-200 mb-3">
                            <p class="text-sm font-semibold text-gray-800 text-center">
                                <strong>نفس نوع العملية للعينين (OD & OS) / Same operation type for both eyes (OD & OS)</strong>
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Operation Type (OD & OS) *</label>
                            <select wire:model.live="form.operation_type_od" class="form-select">
                                <option value="">Select Operation Type</option>
                                <option value="PRK">PRK (Photorefractive Keratectomy)</option>
                                <option value="Femto-LASIK">Femto-LASIK (Femtosecond LASIK)</option>
                                <option value="SMILE">SMILE (Small Incision Lenticule Extraction)</option>
                                <option value="PTK">PTK (Phototherapeutic Keratectomy)</option>
                            </select>
                            @error('form.operation_type_od') <span class="text-red-600 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <p class="text-xs text-gray-500 text-center py-4">Please select operation type above</p>
                    </div>
                @endif
            @else
                {{-- Different Operation Types: Show separate sections vertically (one below the other) --}}
                {{-- Right Eye (OD) --}}
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200 mb-4">
                    <h4 class="font-semibold text-sm text-blue-800 mb-3">Right Eye (OD) - العين اليمنى</h4>
                    
                    {{-- Operation Type dropdown inside OD section --}}
                    <div class="form-group">
                        <label class="form-label">Operation Type (OD - Right Eye) *</label>
                        <select wire:model.live="form.operation_type_od" class="form-select">
                            <option value="">Select Operation Type</option>
                            <option value="PRK">PRK (Photorefractive Keratectomy)</option>
                            <option value="Femto-LASIK">Femto-LASIK (Femtosecond LASIK)</option>
                            <option value="SMILE">SMILE (Small Incision Lenticule Extraction)</option>
                            <option value="PTK">PTK (Phototherapeutic Keratectomy)</option>
                        </select>
                        @error('form.operation_type_od') <span class="text-red-600 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- Operation specific fields for OD --}}
                    @if(($form['operation_type_od'] ?? '') === 'PRK')
                        @include('livewire.operation-note-manager.tabs.partials.operation-prk', ['eye' => 'od'])
                    @endif

                    @if(($form['operation_type_od'] ?? '') === 'Femto-LASIK')
                        @include('livewire.operation-note-manager.tabs.partials.operation-femto', ['eye' => 'od'])
                    @endif

                    @if(($form['operation_type_od'] ?? '') === 'SMILE')
                        @include('livewire.operation-note-manager.tabs.partials.operation-smile', ['eye' => 'od'])
                    @endif

                    @if(($form['operation_type_od'] ?? '') === 'PTK')
                        @include('livewire.operation-note-manager.tabs.partials.operation-ptk', ['eye' => 'od'])
                    @endif
                </div>

                {{-- Left Eye (OS) --}}
                <div class="bg-green-50 rounded-lg p-4 border border-green-200 mb-4">
                    <h4 class="font-semibold text-sm text-green-800 mb-3">Left Eye (OS) - العين اليسرى</h4>
                    
                    {{-- Operation Type dropdown inside OS section --}}
                    <div class="form-group">
                        <label class="form-label">Operation Type (OS - Left Eye) *</label>
                        <select wire:model.live="form.operation_type_os" class="form-select">
                            <option value="">Select Operation Type</option>
                            <option value="PRK">PRK (Photorefractive Keratectomy)</option>
                            <option value="Femto-LASIK">Femto-LASIK (Femtosecond LASIK)</option>
                            <option value="SMILE">SMILE (Small Incision Lenticule Extraction)</option>
                            <option value="PTK">PTK (Phototherapeutic Keratectomy)</option>
                        </select>
                        @error('form.operation_type_os') <span class="text-red-600 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- Operation specific fields for OS --}}
                    @if(($form['operation_type_os'] ?? '') === 'PRK')
                        @include('livewire.operation-note-manager.tabs.partials.operation-prk', ['eye' => 'os'])
                    @endif

                    @if(($form['operation_type_os'] ?? '') === 'Femto-LASIK')
                        @include('livewire.operation-note-manager.tabs.partials.operation-femto', ['eye' => 'os'])
                    @endif

                    @if(($form['operation_type_os'] ?? '') === 'SMILE')
                        @include('livewire.operation-note-manager.tabs.partials.operation-smile', ['eye' => 'os'])
                    @endif

                    @if(($form['operation_type_os'] ?? '') === 'PTK')
                        @include('livewire.operation-note-manager.tabs.partials.operation-ptk', ['eye' => 'os'])
                    @endif
                </div>
            @endif

            {{-- Additional Notes --}}
            <div class="form-group">
                <label class="form-label">Additional Notes</label>
                <textarea wire:model="form.notes" class="form-input" rows="4" placeholder="Enter any additional notes..."></textarea>
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" wire:click="cancel" class="btn-secondary">
                    Cancel
                </button>
                <button type="submit" class="btn-primary">
                    <span wire:loading.remove wire:target="save">
                        {{ $editingId ? 'Update' : 'Save' }} Operation Note
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
            </div>
        </form>
        </div>
    </div>
</div>
