@php
    // Determine which fields to use based on eye parameter
    $eyeSuffix = $eye ?? 'general';
    if ($eyeSuffix === 'od') {
        $excimerProfileField = 'recommendationForm.femto_excimer_profile_od';
        $monovisionEyeField = 'recommendationForm.femto_monovision_eye_od';
        $targetField = 'recommendationForm.femto_target_od';
    } elseif ($eyeSuffix === 'os') {
        $excimerProfileField = 'recommendationForm.femto_excimer_profile_os';
        $monovisionEyeField = 'recommendationForm.femto_monovision_eye_os';
        $targetField = 'recommendationForm.femto_target_os';
    } else {
        // Fallback to old shared fields for backward compatibility
        $excimerProfileField = 'recommendationForm.femto_excimer_profile';
        $monovisionEyeField = 'recommendationForm.femto_monovision_eye';
        $targetField = 'recommendationForm.femto_target';
    }
@endphp

<div class="space-y-3 bg-white rounded-lg p-4 border border-gray-200" wire:key="decision-femto-{{ $eye ?? 'general' }}">
    <h4 class="font-semibold text-sm text-gray-800">Femto Lasik Details @if($eye) ({{ strtoupper($eye) }}) @endif</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="label">
                <span class="label-text text-xs">Excimer profile</span>
            </label>
            <select wire:model.live="{{ $excimerProfileField }}" class="select select-bordered w-full select-sm">
                <option value="">Select</option>
                <option value="wfo">WFO</option>
                <option value="topo_guided">Topo-guided</option>
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
