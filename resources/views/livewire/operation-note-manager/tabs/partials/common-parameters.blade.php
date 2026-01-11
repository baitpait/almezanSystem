@php
    // For Operation Note, monovision_eye is shared (not per-eye)
    $monovisionField = 'form.monovision_eye';
    
    // Check if operation type is PTK - hide Monovision Eye and title for PTK
    $operationType = '';
    $isSameType = $form['same_operation_type_both_eyes'] ?? false;
    
    if ($eye === 'od') {
        $operationType = $form['operation_type_od'] ?? '';
    } elseif ($eye === 'os') {
        $operationType = $form['operation_type_os'] ?? '';
    } else {
        $operationType = $form['operation_type_od'] ?? $form['operation_type_os'] ?? $form['operation_type'] ?? '';
    }
    
    $isPTK = ($operationType === 'PTK');
    $isPRK = ($operationType === 'PRK');
    
    // Show MMC only for PTK and PRK operations
    // If same_operation_type_both_eyes is checked and we're in OD section, check OD type
    // Otherwise check the current eye's operation type
    if ($isSameType && $eye === 'od') {
        $checkType = $form['operation_type_od'] ?? '';
        $showMMC = ($checkType === 'PTK' || $checkType === 'PRK');
    } else {
        $showMMC = ($isPTK || $isPRK);
    }
    
    // Determine title based on eye and same_operation_type_both_eyes - hide if PTK
    $title = '';
    if (!$isPTK) {
        $isSameType = $form['same_operation_type_both_eyes'] ?? false;
        if ($isSameType) {
            $title = 'Common Parameters (OD & OS - Both Eyes)';
        } elseif ($eye === 'od') {
            $title = 'Common Parameters (OD - Right Eye)';
        } elseif ($eye === 'os') {
            $title = 'Common Parameters (OS - Left Eye)';
        } else {
            $title = 'Common Parameters';
        }
    }
@endphp

<div class="space-y-4 bg-gray-50 rounded-lg p-4 border border-gray-200 mt-4">
    @if(!empty($title))
        <h4 class="font-semibold text-sm text-gray-800 mb-4">{{ $title }}</h4>
    @endif
    
    {{-- Monovision Eye and Target Section -- Only show if NOT PTK --}}
    @if(!$isPTK)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="label">
                    <span class="label-text text-xs font-medium">Monovision Eye</span>
                </label>
                <select wire:model.live="{{ $monovisionField }}" class="select select-bordered w-full select-sm">
                    <option value="">Select</option>
                    <option value="none">None / No Monovision</option>
                    <option value="OD">R / OD</option>
                    <option value="OS">L / OS</option>
                </select>
            </div>
            <div>
                <label class="label">
                    <span class="label-text text-xs font-medium">Target</span>
                </label>
                @if($eye === 'od')
                    {{-- OD Target - show based on operation_type_od --}}
                    @if(($form['operation_type_od'] ?? '') === 'PRK')
                        <input type="text" wire:model.live="form.prk_target_od" class="input input-bordered w-full input-sm" placeholder="Enter target">
                    @elseif(($form['operation_type_od'] ?? '') === 'Femto-LASIK')
                        <input type="text" wire:model.live="form.femto_target_od" class="input input-bordered w-full input-sm" placeholder="Enter target">
                    @elseif(($form['operation_type_od'] ?? '') === 'SMILE')
                        <input type="text" wire:model.live="form.smile_target_od" class="input input-bordered w-full input-sm" placeholder="Enter target">
                    @else
                        <input type="text" wire:model.live="form.prk_target_od" class="input input-bordered w-full input-sm" placeholder="Enter target" disabled>
                    @endif
                @elseif($eye === 'os')
                    {{-- OS Target - show based on operation_type_os --}}
                    @if(($form['operation_type_os'] ?? '') === 'PRK')
                        <input type="text" wire:model.live="form.prk_target_os" class="input input-bordered w-full input-sm" placeholder="Enter target">
                    @elseif(($form['operation_type_os'] ?? '') === 'Femto-LASIK')
                        <input type="text" wire:model.live="form.femto_target_os" class="input input-bordered w-full input-sm" placeholder="Enter target">
                    @elseif(($form['operation_type_os'] ?? '') === 'SMILE')
                        <input type="text" wire:model.live="form.smile_target_os" class="input input-bordered w-full input-sm" placeholder="Enter target">
                    @else
                        <input type="text" wire:model.live="form.prk_target_os" class="input input-bordered w-full input-sm" placeholder="Enter target" disabled>
                    @endif
                @else
                    <input type="text" wire:model.live="form.prk_target_od" class="input input-bordered w-full input-sm" placeholder="Enter target">
                @endif
            </div>
        </div>
    @endif

    {{-- MMC 0.02% Section -- Only for PRK and PTK operations --}}
    @if($showMMC)
    <div class="border-t border-gray-300 pt-4">
        @php
            // Determine MMC fields based on eye and same_operation_type_both_eyes
            if ($isSameType && $eye === 'od') {
                // When same type is checked and we're in OD section, show for both eyes
                $mmcField = 'form.mmc_0_02_percent_od';
                $mmcDurationField = 'form.mmc_duration_sec_od';
                $mmcChecked = $form['mmc_0_02_percent_od'] ?? false;
                $showLabel = 'MMC 0.02% (Mitomycin C) - OD & OS';
            } elseif ($eye === 'od') {
                $mmcField = 'form.mmc_0_02_percent_od';
                $mmcDurationField = 'form.mmc_duration_sec_od';
                $mmcChecked = $form['mmc_0_02_percent_od'] ?? false;
                $showLabel = 'MMC 0.02% (Mitomycin C) - OD';
            } elseif ($eye === 'os') {
                $mmcField = 'form.mmc_0_02_percent_os';
                $mmcDurationField = 'form.mmc_duration_sec_os';
                $mmcChecked = $form['mmc_0_02_percent_os'] ?? false;
                $showLabel = 'MMC 0.02% (Mitomycin C) - OS';
            } else {
                $mmcField = 'form.mmc_0_02_percent_od';
                $mmcDurationField = 'form.mmc_duration_sec_od';
                $mmcChecked = $form['mmc_0_02_percent_od'] ?? false;
                $showLabel = 'MMC 0.02% (Mitomycin C)';
            }
        @endphp
        <div class="form-control">
            <label class="label cursor-pointer justify-start gap-3 py-2 hover:bg-gray-100 rounded px-2 -mx-2 transition-colors">
                <input type="checkbox" wire:model.live="{{ $mmcField }}" class="checkbox checkbox-primary checkbox-sm">
                <span class="label-text text-xs">{{ $showLabel }}</span>
            </label>
            @if($mmcChecked)
                <div class="mt-2 ml-8 flex items-center gap-2">
                    <input type="number" wire:model.live="{{ $mmcDurationField }}" class="input input-bordered input-sm w-24" placeholder="Duration" min="0" step="1">
                    <span class="text-xs text-gray-600">sec</span>
                </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Eye Drops Section --}}
    <div class="border-t border-gray-300 pt-4">
        <h5 class="font-semibold text-xs text-gray-700 mb-3">Eye Drops</h5>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="form-control">
                <label class="label cursor-pointer justify-start gap-3 py-2 hover:bg-gray-100 rounded px-2 -mx-2 transition-colors">
                    <input type="checkbox" wire:model.live="form.eye_drops_vigamox" class="checkbox checkbox-primary checkbox-sm">
                    <span class="label-text text-xs">Vigamox (Moxifloxacin)</span>
                </label>
            </div>

            <div class="form-control">
                <label class="label cursor-pointer justify-start gap-3 py-2 hover:bg-gray-100 rounded px-2 -mx-2 transition-colors">
                    <input type="checkbox" wire:model.live="form.eye_drops_pred_forte" class="checkbox checkbox-primary checkbox-sm">
                    <span class="label-text text-xs">Pred Forte (Prednisolone Acetate)</span>
                </label>
            </div>

            <div class="form-control">
                <label class="label cursor-pointer justify-start gap-3 py-2 hover:bg-gray-100 rounded px-2 -mx-2 transition-colors">
                    <input type="checkbox" wire:model.live="form.eye_drops_other" class="checkbox checkbox-primary checkbox-sm">
                    <span class="label-text text-xs">Other Eye Drops</span>
                </label>
            </div>
        </div>
        @if($form['eye_drops_other'] ?? false)
            <div class="mt-3">
                <textarea wire:model.live="form.eye_drops_other_details" class="textarea textarea-bordered w-full textarea-sm" rows="2" placeholder="Specify other eye drops..."></textarea>
            </div>
        @endif
    </div>
</div>
