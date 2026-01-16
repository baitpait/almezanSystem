@php
    // Determine which fields to use based on eye parameter
    $eyeSuffix = $eye ?? 'general';
    if ($eyeSuffix === 'od') {
        $epithelialRemovalField = 'form.ptk_epithelial_removal_od';
        $excimerProfileField = 'form.ptk_excimer_profile_od';
        $bclField = 'form.ptk_bandage_contact_lens_od';
    } elseif ($eyeSuffix === 'os') {
        $epithelialRemovalField = 'form.ptk_epithelial_removal_os';
        $excimerProfileField = 'form.ptk_excimer_profile_os';
        $bclField = 'form.ptk_bandage_contact_lens_os';
    } else {
        // Fallback to old shared fields for backward compatibility
        $epithelialRemovalField = 'form.ptk_epithelial_removal';
        $excimerProfileField = 'form.ptk_excimer_profile';
        $bclField = 'form.ptk_bandage_contact_lens';
    }
@endphp

<div class="space-y-3" wire:key="ptk-fields-{{ $eye ?? 'general' }}">
    <div class="bg-white rounded-lg p-4 border border-gray-200">
        <h4 class="font-semibold text-sm text-gray-800 mb-3">PTK Details @if($eye) ({{ strtoupper($eye) }}) @endif</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="label">
                    <span class="label-text text-xs">Epithelial Removal Method</span>
                </label>
                <select wire:model.live="{{ $epithelialRemovalField }}" class="select select-bordered w-full select-sm">
                    <option value="">Select Method</option>
                    <option value="Alcohol">Alcohol</option>
                    <option value="Mechanical">Mechanical</option>
                    <option value="Trans-PTK">Trans-PTK</option>
                </select>
            </div>
            <div>
                <label class="label">
                    <span class="label-text text-xs">Excimer Profile</span>
                </label>
                <select wire:model.live="{{ $excimerProfileField }}" class="select select-bordered w-full select-sm">
                    <option value="">Select Profile</option>
                    <option value="Aspheric Front">Aberration Free (AF)</option>
                    <option value="Topography-guided">Topography-guided</option>
                </select>
            </div>
        </div>
        <div class="form-control mt-3">
            <label class="label cursor-pointer justify-start gap-3">
                <input type="checkbox" wire:model.live="{{ $bclField }}" class="checkbox checkbox-primary checkbox-sm">
                <span class="label-text text-xs">Bandage Contact Lens (BCL)</span>
            </label>
        </div>
    </div>
    
    {{-- Include Common Parameters --}}
    @include('livewire.operation-note-manager.tabs.partials.common-parameters', ['eye' => $eye ?? null])
</div>
