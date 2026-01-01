@php
    // Determine which fields to use based on eye parameter
    $eyeSuffix = $eye ?? 'general';
    if ($eyeSuffix === 'od') {
        $flapDoneField = 'form.femto_flap_done_od';
        $excimerProfileField = 'form.femto_excimer_profile_od';
        $bclField = 'form.femto_bandage_contact_lens_od';
    } elseif ($eyeSuffix === 'os') {
        $flapDoneField = 'form.femto_flap_done_os';
        $excimerProfileField = 'form.femto_excimer_profile_os';
        $bclField = 'form.femto_bandage_contact_lens_os';
    } else {
        // Fallback to old shared fields for backward compatibility
        $flapDoneField = 'form.femto_flap_done';
        $excimerProfileField = 'form.femto_excimer_profile';
        $bclField = 'form.femto_bandage_contact_lens';
    }
@endphp

<div class="space-y-3" wire:key="femto-fields-{{ $eye ?? 'general' }}">
    <div class="bg-white rounded-lg p-4 border border-gray-200">
        <h4 class="font-semibold text-sm text-gray-800 mb-3">Femto-LASIK Details @if($eye) ({{ strtoupper($eye) }}) @endif</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="label">
                    <span class="label-text text-xs">Flap Done</span>
                </label>
                <select wire:model.live="{{ $flapDoneField }}" class="select select-bordered w-full select-sm">
                    <option value="">Select</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div>
                <label class="label">
                    <span class="label-text text-xs">Excimer Profile</span>
                </label>
                <select wire:model.live="{{ $excimerProfileField }}" class="select select-bordered w-full select-sm">
                    <option value="">Select Profile</option>
                    <option value="Aspheric Front">Aspheric Front (AF)</option>
                    <option value="Topography-guided">Topography-guided</option>
                </select>
            </div>
        </div>
        <div class="form-control mt-3">
            <label class="label cursor-pointer justify-start gap-3">
                <input type="checkbox" wire:model.live="{{ $bclField }}" class="checkbox checkbox-primary checkbox-sm">
                <span class="label-text text-xs">Bandage Contact Lens (BCL) - Usually No for LASIK</span>
            </label>
        </div>
    </div>
    
    {{-- Include Common Parameters --}}
    @include('livewire.operation-note-manager.tabs.partials.common-parameters', ['eye' => $eye ?? null])
</div>
