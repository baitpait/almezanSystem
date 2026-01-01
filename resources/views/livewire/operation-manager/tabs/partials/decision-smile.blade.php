@php
    // Determine which fields to use based on eye parameter
    $eyeSuffix = $eye ?? 'general';
    if ($eyeSuffix === 'od') {
        $monovisionEyeField = 'recommendationForm.smile_monovision_eye_od';
        $targetField = 'recommendationForm.smile_target_od';
    } elseif ($eyeSuffix === 'os') {
        $monovisionEyeField = 'recommendationForm.smile_monovision_eye_os';
        $targetField = 'recommendationForm.smile_target_os';
    } else {
        // Fallback to old shared fields for backward compatibility
        $monovisionEyeField = 'recommendationForm.smile_monovision_eye';
        $targetField = 'recommendationForm.smile_target';
    }
@endphp

<div class="space-y-3 bg-white rounded-lg p-4 border border-gray-200" wire:key="decision-smile-{{ $eye ?? 'general' }}">
    <h4 class="font-semibold text-sm text-gray-800">Smile Details @if($eye) ({{ strtoupper($eye) }}) @endif</h4>
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
