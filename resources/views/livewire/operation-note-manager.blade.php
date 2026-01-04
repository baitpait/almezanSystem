<div class="space-y-6">
    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    {{-- Error Message --}}
    @if (session()->has('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Appointment Info --}}
    @if($appointment)
        <div class="card bg-base-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-lg">Appointment Information</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Patient: <span class="font-medium">{{ $appointment->patient->full_name ?? 'N/A' }}</span> | 
                        Doctor: <span class="font-medium">{{ $appointment->doctor->name ?? 'N/A' }}</span> | 
                        Date: <span class="font-medium">{{ $appointment->appointment_date?->format('Y-m-d') ?? 'N/A' }}</span>
                    </p>
                </div>
                @if($operationNote)
                    <button wire:click="edit({{ $operationNote->id }})" class="btn-primary-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Operation Note
                    </button>
                @endif
            </div>
        </div>
    @endif

    {{-- Operation Note Form --}}
    <div class="card bg-base-200 p-6">
        <h3 class="font-semibold text-xl mb-6">Operation Note</h3>
        
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
            <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200 mb-4">
                <label class="label cursor-pointer justify-start gap-3">
                    <input type="checkbox" wire:model.live="form.same_operation_type_both_eyes" class="checkbox checkbox-primary checkbox-sm">
                    <span class="label-text text-xs font-semibold">Same operation type for both eyes / نفس نوع العملية للعينين</span>
                </label>
                @if($form['same_operation_type_both_eyes'])
                    <p class="text-xs text-gray-600 mt-2">
                        <strong>مفعّل:</strong> نوع العملية سيتم نسخه من العين اليمنى (OD) إلى العين اليسرى (OS) تلقائياً.<br>
                        <strong>Enabled:</strong> Operation type will be automatically copied from Right Eye (OD) to Left Eye (OS).
                    </p>
                @endif
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
                        <div class="mb-4">
                            <label class="label">
                                <span class="label-text font-semibold text-xs">Operation Type (OD & OS) *</span>
                            </label>
                            <select wire:model.live="form.operation_type_od" class="select select-bordered w-full select-sm">
                                <option value="">Select Operation Type</option>
                                <option value="PRK">PRK (Photorefractive Keratectomy)</option>
                                <option value="Femto-LASIK">Femto-LASIK (Femtosecond LASIK)</option>
                                <option value="SMILE">SMILE (Small Incision Lenticule Extraction)</option>
                                <option value="PTK">PTK (Phototherapeutic Keratectomy)</option>
                            </select>
                            @error('form.operation_type_od') <span class="text-error text-xs">{{ $message }}</span> @enderror
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
                        <div class="mb-4">
                            <label class="label">
                                <span class="label-text font-semibold text-xs">Operation Type (OD & OS) *</span>
                            </label>
                            <select wire:model.live="form.operation_type_od" class="select select-bordered w-full select-sm">
                                <option value="">Select Operation Type</option>
                                <option value="PRK">PRK (Photorefractive Keratectomy)</option>
                                <option value="Femto-LASIK">Femto-LASIK (Femtosecond LASIK)</option>
                                <option value="SMILE">SMILE (Small Incision Lenticule Extraction)</option>
                                <option value="PTK">PTK (Phototherapeutic Keratectomy)</option>
                            </select>
                            @error('form.operation_type_od') <span class="text-error text-xs">{{ $message }}</span> @enderror
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
                    <div class="mb-4">
                        <label class="label">
                            <span class="label-text font-semibold text-xs">Operation Type (OD - Right Eye) *</span>
                        </label>
                        <select wire:model.live="form.operation_type_od" class="select select-bordered w-full select-sm">
                            <option value="">Select Operation Type</option>
                            <option value="PRK">PRK (Photorefractive Keratectomy)</option>
                            <option value="Femto-LASIK">Femto-LASIK (Femtosecond LASIK)</option>
                            <option value="SMILE">SMILE (Small Incision Lenticule Extraction)</option>
                            <option value="PTK">PTK (Phototherapeutic Keratectomy)</option>
                        </select>
                        @error('form.operation_type_od') <span class="text-error text-xs">{{ $message }}</span> @enderror
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
                    <div class="mb-4">
                        <label class="label">
                            <span class="label-text font-semibold text-xs">Operation Type (OS - Left Eye) *</span>
                        </label>
                        <select wire:model.live="form.operation_type_os" class="select select-bordered w-full select-sm">
                            <option value="">Select Operation Type</option>
                            <option value="PRK">PRK (Photorefractive Keratectomy)</option>
                            <option value="Femto-LASIK">Femto-LASIK (Femtosecond LASIK)</option>
                            <option value="SMILE">SMILE (Small Incision Lenticule Extraction)</option>
                            <option value="PTK">PTK (Phototherapeutic Keratectomy)</option>
                        </select>
                        @error('form.operation_type_os') <span class="text-error text-xs">{{ $message }}</span> @enderror
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
            <div>
                <label class="label">
                    <span class="label-text font-semibold">Additional Notes</span>
                </label>
                <textarea wire:model="form.notes" class="textarea textarea-bordered w-full" rows="4" placeholder="Enter any additional notes..."></textarea>
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" wire:click="resetForm" class="btn btn-ghost">Cancel</button>
                <button type="submit" class="btn-primary">
                    <span wire:loading.remove wire:target="save">
                        {{ $editingId ? 'Update' : 'Save' }} Operation Note
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <span class="loading loading-spinner loading-sm"></span>
                        Saving...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
