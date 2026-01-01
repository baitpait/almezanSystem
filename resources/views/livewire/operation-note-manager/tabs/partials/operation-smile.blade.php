@php
    // Determine which fields to use based on eye parameter
    $eyeSuffix = $eye ?? 'general';
    if ($eyeSuffix === 'od') {
        $separationField = 'form.smile_complete_lenticule_separation_od';
        $extractionField = 'form.smile_complete_lenticule_extraction_od';
    } elseif ($eyeSuffix === 'os') {
        $separationField = 'form.smile_complete_lenticule_separation_os';
        $extractionField = 'form.smile_complete_lenticule_extraction_os';
    } else {
        // Fallback to old shared fields for backward compatibility
        $separationField = 'form.smile_complete_lenticule_separation';
        $extractionField = 'form.smile_complete_lenticule_extraction';
    }
@endphp

<div class="space-y-3" wire:key="smile-fields-{{ $eye ?? 'general' }}">
    <div class="bg-white rounded-lg p-4 border border-gray-200">
        <h4 class="font-semibold text-sm text-gray-800 mb-3">SMILE Details @if($eye) ({{ strtoupper($eye) }}) @endif</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="label">
                    <span class="label-text text-xs">Complete Lenticule Separation</span>
                </label>
                <select wire:model.live="{{ $separationField }}" class="select select-bordered w-full select-sm">
                    <option value="">Select</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div>
                <label class="label">
                    <span class="label-text text-xs">Complete Lenticule Extraction</span>
                </label>
                <select wire:model.live="{{ $extractionField }}" class="select select-bordered w-full select-sm">
                    <option value="">Select</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>
    </div>
    
    {{-- Include Common Parameters --}}
    @include('livewire.operation-note-manager.tabs.partials.common-parameters', ['eye' => $eye ?? null])
</div>
