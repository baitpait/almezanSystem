@php
    // Determine which fields to use based on eye parameter
    $eyeSuffix = $eye ?? 'general';
    if ($eyeSuffix === 'od') {
        $epithelialRemovalField = 'recommendationForm.ptk_epithelial_removal_od';
        $excimerProfileField = 'recommendationForm.ptk_excimer_profile_od';
        $monovisionEyeField = 'recommendationForm.ptk_monovision_eye_od';
        $targetField = 'recommendationForm.ptk_target_od';
    } elseif ($eyeSuffix === 'os') {
        $epithelialRemovalField = 'recommendationForm.ptk_epithelial_removal_os';
        $excimerProfileField = 'recommendationForm.ptk_excimer_profile_os';
        $monovisionEyeField = 'recommendationForm.ptk_monovision_eye_os';
        $targetField = 'recommendationForm.ptk_target_os';
    } else {
        // Fallback to old shared fields for backward compatibility
        $epithelialRemovalField = 'recommendationForm.ptk_epithelial_removal';
        $excimerProfileField = 'recommendationForm.ptk_excimer_profile';
        $monovisionEyeField = 'recommendationForm.ptk_monovision_eye';
        $targetField = 'recommendationForm.ptk_target';
    }
@endphp

<div class="space-y-3 bg-white rounded-lg p-4 border border-gray-200" wire:key="decision-ptk-{{ $eye ?? 'general' }}">
    <h4 class="font-semibold text-sm text-gray-800">PTK Details @if($eye) ({{ strtoupper($eye) }}) @endif</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="label">
                <span class="label-text text-xs">Epithelial removal</span>
            </label>
            <select wire:model.live="{{ $epithelialRemovalField }}" class="select select-bordered w-full select-sm">
                <option value="">Select</option>
                <option value="alcohol_20">Alcohol 20%</option>
                <option value="trans_prk">Trans PRK</option>
                <option value="mechanical_removal">Mechanical removal</option>
            </select>
        </div>
        <div>
            <label class="label">
                <span class="label-text text-xs">Excimer profile</span>
            </label>
            <select wire:model.live="{{ $excimerProfileField }}" class="select select-bordered w-full select-sm">
                <option value="">Select</option>
                <option value="normal">Normal</option>
                <option value="topography_guided">Topography-guided</option>
            </select>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="label">
                <span class="label-text text-xs">Monovision eye</span>
            </label>
            <select wire:model.live="{{ $monovisionEyeField }}" class="select select-bordered w-full select-sm">
                <option value="">Select</option>
                <option value="none">None / No Monovision</option>
                <option value="OD">R / OD</option>
                <option value="OS">L / OS</option>
            </select>
        </div>
        <div>
            <label class="label">
                <span class="label-text text-xs">Target</span>
            </label>
            <input type="text" wire:model.live="{{ $targetField }}" class="input input-bordered w-full input-sm" placeholder="Enter target">
        </div>
    </div>
</div>
