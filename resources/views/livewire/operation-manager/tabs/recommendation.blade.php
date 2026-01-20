<div class="space-y-6">
    <div class="alert alert-info">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm">Select a decision and complete the fields for each procedure.</span>
    </div>

    @php
        $operationEye = $operationForm['operation_eye'] ?? 'OU';
        $isBothEyes = $operationEye === 'OU';
        $isRightEye = $operationEye === 'OD';
        $isLeftEye = $operationEye === 'OS';
    @endphp

    {{-- Decision & Dynamic Fields --}}
    <div class="card bg-base-200 p-4 space-y-4">
        @if($isBothEyes)
            {{-- Both Eyes (OU) - Show separate decisions for each eye --}}
            
            {{-- Same Decision Checkbox --}}
            <div class="bg-blue-50 rounded-lg p-3 border border-blue-200 mb-4">
                <label class="label cursor-pointer justify-start gap-3">
                    <input type="checkbox" wire:model.live="recommendationForm.same_decision_both_eyes" class="checkbox checkbox-primary checkbox-sm">
                    <span class="label-text text-xs font-semibold">Same decision for both eyes</span>
                </label>
                @if($recommendationForm['same_decision_both_eyes'])
                    <p class="text-xs text-gray-600 mt-2">
                        <strong>Enabled:</strong> A single shared section will be displayed for both eyes. The decision will be automatically copied from OD to OS.
                    </p>
                @endif
            </div>
            
            @if($recommendationForm['same_decision_both_eyes'])
                {{-- Same Decision: Show single shared section --}}
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="bg-white rounded-lg p-3 border border-gray-200 mb-4">
                        <p class="text-sm font-semibold text-gray-800 text-center">
                            <strong>Same decision for both eyes (OD & OS)</strong>
                        </p>
                    </div>
                    <div class="mb-4">
                        <label class="label">
                            <span class="label-text text-xs font-semibold">Decision (OD & OS)</span>
                        </label>
                        <select wire:model.live="recommendationForm.decision_od" class="select select-bordered w-full select-sm">
                            <option value="">Select</option>
                            <option value="prk">PRK</option>
                            <option value="femto_lasik">Femto Lasik</option>
                            <option value="smile">Smile</option>
                            <option value="ptk">PTK</option>
                            <option value="incompatible">Incompatible</option>
                        </select>
                    </div>

                    {{-- Shared fields for both eyes --}}
                    {{-- PRK Fields --}}
                    @if($recommendationForm['decision_od'] === 'prk' && $recommendationForm['decision_od'] === $recommendationForm['decision_os'])
                        @include('livewire.operation-manager.tabs.partials.decision-prk', ['eye' => null, 'prefix' => 'prk'])
                    @endif

                    {{-- Femto Lasik Fields --}}
                    @if($recommendationForm['decision_od'] === 'femto_lasik' && $recommendationForm['decision_od'] === $recommendationForm['decision_os'])
                        @include('livewire.operation-manager.tabs.partials.decision-femto', ['eye' => null, 'prefix' => 'femto'])
                    @endif

                    {{-- Smile Fields --}}
                    @if($recommendationForm['decision_od'] === 'smile' && $recommendationForm['decision_od'] === $recommendationForm['decision_os'])
                        @include('livewire.operation-manager.tabs.partials.decision-smile', ['eye' => null, 'prefix' => 'smile'])
                    @endif

                    {{-- PTK Fields --}}
                    @if($recommendationForm['decision_od'] === 'ptk' && $recommendationForm['decision_od'] === $recommendationForm['decision_os'])
                        @include('livewire.operation-manager.tabs.partials.decision-ptk', ['eye' => null, 'prefix' => 'ptk'])
                    @endif

                    {{-- Incompatible Fields --}}
                    @if($recommendationForm['decision_od'] === 'incompatible' && $recommendationForm['decision_od'] === $recommendationForm['decision_os'])
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <label class="label">
                                <span class="label-text text-xs">Notes (OD & OS)</span>
                            </label>
                            <textarea wire:model.live="recommendationForm.incompatible_notes" class="textarea textarea-bordered w-full textarea-sm" rows="2" placeholder="Add notes for both eyes..."></textarea>
                        </div>
                    @endif

                    {{-- Planning Section - At the bottom --}}
                    <div class="bg-white rounded-lg p-3 border border-gray-200 mt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="font-semibold text-xs text-gray-800">Planning</h5>
                            <button type="button" wire:click="getRefraction('both')" class="btn-add btn-action flex items-center gap-2 text-xs">
                                Get Refraction
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- OD Planning --}}
                            <div>
                                <label class="label py-1">
                                    <span class="label-text text-xs text-gray-600">OD (Right Eye)</span>
                                </label>
                                <div class="flex gap-2">
                                    <div class="flex-1">
                                        <label class="label label-text text-xs">Sphere</label>
                                        <input type="text" wire:model="recommendationForm.planning_sphere_od" class="input input-bordered w-full input-sm" placeholder="0.00">
                                    </div>
                                    <div class="flex-1">
                                        <label class="label label-text text-xs">Cylinder</label>
                                        <input type="text" wire:model="recommendationForm.planning_cylinder_od" class="input input-bordered w-full input-sm" placeholder="0.00">
                                    </div>
                                    <div class="flex-1">
                                        <label class="label label-text text-xs">Axis</label>
                                        <input type="text" wire:model="recommendationForm.planning_axis_od" class="input input-bordered w-full input-sm" placeholder="0">
                                    </div>
                                </div>
                            </div>
                            {{-- OS Planning --}}
                            <div>
                                <label class="label py-1">
                                    <span class="label-text text-xs text-gray-600">OS (Left Eye)</span>
                                </label>
                                <div class="flex gap-2">
                                    <div class="flex-1">
                                        <label class="label label-text text-xs">Sphere</label>
                                        <input type="text" wire:model="recommendationForm.planning_sphere_os" class="input input-bordered w-full input-sm" placeholder="0.00">
                                    </div>
                                    <div class="flex-1">
                                        <label class="label label-text text-xs">Cylinder</label>
                                        <input type="text" wire:model="recommendationForm.planning_cylinder_os" class="input input-bordered w-full input-sm" placeholder="0.00">
                                    </div>
                                    <div class="flex-1">
                                        <label class="label label-text text-xs">Axis</label>
                                        <input type="text" wire:model="recommendationForm.planning_axis_os" class="input input-bordered w-full input-sm" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Different Decisions: Show separate sections for each eye --}}
                {{-- Right Eye (OD) Decision --}}
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <h4 class="font-semibold text-sm text-blue-800 mb-3">Right Eye (OD)</h4>
                    <div class="mb-4">
                        <label class="label">
                            <span class="label-text text-xs font-semibold">Decision (OD)</span>
                        </label>
                        <select wire:model.live="recommendationForm.decision_od" class="select select-bordered w-full select-sm">
                            <option value="">Select</option>
                            <option value="prk">PRK</option>
                            <option value="femto_lasik">Femto Lasik</option>
                            <option value="smile">Smile</option>
                            <option value="ptk">PTK</option>
                            <option value="incompatible">Incompatible</option>
                        </select>
                    </div>

                    {{-- PRK Fields for OD --}}
                    @if($recommendationForm['decision_od'] === 'prk')
                        @include('livewire.operation-manager.tabs.partials.decision-prk', ['eye' => 'od', 'prefix' => 'prk'])
                    @endif

                    {{-- Femto Lasik Fields for OD --}}
                    @if($recommendationForm['decision_od'] === 'femto_lasik')
                        @include('livewire.operation-manager.tabs.partials.decision-femto', ['eye' => 'od', 'prefix' => 'femto'])
                    @endif

                    {{-- Smile Fields for OD --}}
                    @if($recommendationForm['decision_od'] === 'smile')
                        @include('livewire.operation-manager.tabs.partials.decision-smile', ['eye' => 'od', 'prefix' => 'smile'])
                    @endif

                    {{-- PTK Fields for OD --}}
                    @if($recommendationForm['decision_od'] === 'ptk')
                        @include('livewire.operation-manager.tabs.partials.decision-ptk', ['eye' => 'od', 'prefix' => 'ptk'])
                    @endif

                    {{-- Incompatible Fields for OD --}}
                    @if($recommendationForm['decision_od'] === 'incompatible')
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <label class="label">
                                <span class="label-text text-xs">Notes (OD)</span>
                            </label>
                            <textarea wire:model.live="recommendationForm.incompatible_notes_od" class="textarea textarea-bordered w-full textarea-sm" rows="2" placeholder="Add notes for OD..."></textarea>
                        </div>
                    @endif

                    {{-- Planning Section for OD - At the bottom --}}
                    <div class="bg-white rounded-lg p-3 border border-gray-200 mt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="font-semibold text-xs text-gray-800">Planning</h5>
                            <button type="button" wire:click="getRefraction('od')" class="btn-add btn-action flex items-center gap-2 text-xs">
                                Get Refraction
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="label label-text text-xs">Sphere</label>
                                <input type="text" wire:model="recommendationForm.planning_sphere_od" class="input input-bordered w-full input-sm" placeholder="0.00">
                            </div>
                            <div class="flex-1">
                                <label class="label label-text text-xs">Cylinder</label>
                                <input type="text" wire:model="recommendationForm.planning_cylinder_od" class="input input-bordered w-full input-sm" placeholder="0.00">
                            </div>
                            <div class="flex-1">
                                <label class="label label-text text-xs">Axis</label>
                                <input type="text" wire:model="recommendationForm.planning_axis_od" class="input input-bordered w-full input-sm" placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Left Eye (OS) Decision --}}
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <h4 class="font-semibold text-sm text-blue-800 mb-3">Left Eye (OS)</h4>
                    <div class="mb-4">
                        <label class="label">
                            <span class="label-text text-xs font-semibold">Decision (OS)</span>
                        </label>
                        <select wire:model.live="recommendationForm.decision_os" class="select select-bordered w-full select-sm">
                            <option value="">Select</option>
                            <option value="prk">PRK</option>
                            <option value="femto_lasik">Femto Lasik</option>
                            <option value="smile">Smile</option>
                            <option value="ptk">PTK</option>
                            <option value="incompatible">Incompatible</option>
                        </select>
                    </div>

                    {{-- PRK Fields for OS --}}
                    @if($recommendationForm['decision_os'] === 'prk')
                        @include('livewire.operation-manager.tabs.partials.decision-prk', ['eye' => 'os', 'prefix' => 'prk'])
                    @endif

                    {{-- Femto Lasik Fields for OS --}}
                    @if($recommendationForm['decision_os'] === 'femto_lasik')
                        @include('livewire.operation-manager.tabs.partials.decision-femto', ['eye' => 'os', 'prefix' => 'femto'])
                    @endif

                    {{-- Smile Fields for OS --}}
                    @if($recommendationForm['decision_os'] === 'smile')
                        @include('livewire.operation-manager.tabs.partials.decision-smile', ['eye' => 'os', 'prefix' => 'smile'])
                    @endif

                    {{-- PTK Fields for OS --}}
                    @if($recommendationForm['decision_os'] === 'ptk')
                        @include('livewire.operation-manager.tabs.partials.decision-ptk', ['eye' => 'os', 'prefix' => 'ptk'])
                    @endif

                    {{-- Incompatible Fields for OS --}}
                    @if($recommendationForm['decision_os'] === 'incompatible')
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <label class="label">
                                <span class="label-text text-xs">Notes (OS)</span>
                            </label>
                            <textarea wire:model.live="recommendationForm.incompatible_notes_os" class="textarea textarea-bordered w-full textarea-sm" rows="2" placeholder="Add notes for OS..."></textarea>
                        </div>
                    @endif

                    {{-- Planning Section for OS - At the bottom --}}
                    <div class="bg-white rounded-lg p-3 border border-gray-200 mt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="font-semibold text-xs text-gray-800">Planning</h5>
                            <button type="button" wire:click="getRefraction('os')" class="btn-add btn-action flex items-center gap-2 text-xs">
                                Get Refraction
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="label label-text text-xs">Sphere</label>
                                <input type="text" wire:model="recommendationForm.planning_sphere_os" class="input input-bordered w-full input-sm" placeholder="0.00">
                            </div>
                            <div class="flex-1">
                                <label class="label label-text text-xs">Cylinder</label>
                                <input type="text" wire:model="recommendationForm.planning_cylinder_os" class="input input-bordered w-full input-sm" placeholder="0.00">
                            </div>
                            <div class="flex-1">
                                <label class="label label-text text-xs">Axis</label>
                                <input type="text" wire:model="recommendationForm.planning_axis_os" class="input input-bordered w-full input-sm" placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @else
            {{-- Single Eye (OD or OS) - Show general decision --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="label">
                        <span class="label-text text-xs">Decision</span>
                    </label>
                    <select wire:model.live="recommendationForm.decision" class="select select-bordered w-full select-sm">
                        <option value="">Select</option>
                        <option value="prk">PRK</option>
                        <option value="femto_lasik">Femto Lasik</option>
                        <option value="smile">Smile</option>
                        <option value="ptk">PTK</option>
                        <option value="incompatible">Incompatible</option>
                    </select>
                </div>
            </div>

            {{-- PRK Fields --}}
            @if($recommendationForm['decision'] === 'prk')
                @include('livewire.operation-manager.tabs.partials.decision-prk', ['eye' => null, 'prefix' => 'prk'])
            @endif

            {{-- Femto Lasik Fields --}}
            @if($recommendationForm['decision'] === 'femto_lasik')
                @include('livewire.operation-manager.tabs.partials.decision-femto', ['eye' => null, 'prefix' => 'femto'])
            @endif

            {{-- Smile Fields --}}
            @if($recommendationForm['decision'] === 'smile')
                @include('livewire.operation-manager.tabs.partials.decision-smile', ['eye' => null, 'prefix' => 'smile'])
            @endif

            {{-- PTK Fields --}}
            @if($recommendationForm['decision'] === 'ptk')
                @include('livewire.operation-manager.tabs.partials.decision-ptk', ['eye' => null, 'prefix' => 'ptk'])
            @endif

            {{-- Incompatible Fields --}}
            @if($recommendationForm['decision'] === 'incompatible')
                <div class="space-y-3 bg-white rounded-lg p-4 border border-gray-200" wire:key="decision-incompatible">
                    <h4 class="font-semibold text-sm text-gray-800">Incompatible</h4>
                    <label class="label">
                        <span class="label-text text-xs">Notes</span>
                    </label>
                    <textarea wire:model="recommendationForm.incompatible_notes" class="textarea textarea-bordered w-full textarea-sm" rows="3" placeholder="Add notes..."></textarea>
                </div>
            @endif
        @endif
    </div>

    {{-- General Recommendation Notes (remain available) --}}
    <div class="card bg-base-200 p-4 space-y-3">
        <div>
            <label class="label">
                <span class="label-text">Recommendation Notes</span>
            </label>
            <textarea wire:model="recommendationForm.recommendation_notes" class="textarea textarea-bordered w-full textarea-sm" rows="3" placeholder="Femto-LASIK, PRK/MMC, Dry Eye treatment, etc."></textarea>
        </div>
    </div>
</div>
