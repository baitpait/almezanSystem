<div class="space-y-6">
    <div class="alert alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            <div class="font-semibold">Default Values Pre-filled!</div>
            <div class="text-xs">Pachymetry: 550 μm (normal 540-560) | Tomography: Normal pattern</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- OD (Right Eye) --}}
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4 text-primary">OD (Right Eye)</h3>
            <div class="space-y-3">
                <div>
                    <label class="label">
                        <span class="label-text text-xs">PTA% (Percent Tissue Altered)</span>
                    </label>
                    <input type="text" wire:model="ectasiaForm.pta_percentage_od" class="input input-bordered w-full input-sm">
                </div>
                <div>
                    <label class="label">
                        <span class="label-text text-xs">RSB (Residual Stromal Bed) μm</span>
                    </label>
                    <input type="text" wire:model="ectasiaForm.rsb_od" class="input input-bordered w-full input-sm">
                </div>
                <div>
                    <label class="label">
                        <span class="label-text text-xs">Pachymetry - Thinnest Point (μm)</span>
                    </label>
                    <input type="text" wire:model="ectasiaForm.pachymetry_thinnest_od" class="input input-bordered w-full input-sm" placeholder="550">
                </div>
            </div>
        </div>

        {{-- OS (Left Eye) --}}
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4 text-secondary">OS (Left Eye)</h3>
            <div class="space-y-3">
                <div>
                    <label class="label">
                        <span class="label-text text-xs">PTA% (Percent Tissue Altered)</span>
                    </label>
                    <input type="text" wire:model="ectasiaForm.pta_percentage_os" class="input input-bordered w-full input-sm">
                </div>
                <div>
                    <label class="label">
                        <span class="label-text text-xs">RSB (Residual Stromal Bed) μm</span>
                    </label>
                    <input type="text" wire:model="ectasiaForm.rsb_os" class="input input-bordered w-full input-sm">
                </div>
                <div>
                    <label class="label">
                        <span class="label-text text-xs">Pachymetry - Thinnest Point (μm)</span>
                    </label>
                    <input type="text" wire:model="ectasiaForm.pachymetry_thinnest_os" class="input input-bordered w-full input-sm" placeholder="550">
                </div>
            </div>
        </div>
    </div>

    {{-- Tomography --}}
    <div class="card bg-base-200 p-4">
        <h3 class="font-semibold mb-4">Tomography</h3>
        <div class="space-y-3">
            <div>
                <label class="label">
                    <span class="label-text">Tomography</span>
                </label>
                <select wire:model.live="ectasiaForm.tomography_status" class="select select-bordered w-full select-sm">
                    <option value="normal">Normal pattern both eyes, no signs of ectasia</option>
                    <option value="not_normal">Not Normal</option>
                </select>
            </div>

            @if($ectasiaForm['tomography_status'] === 'not_normal')
                <div>
                    <textarea wire:model="ectasiaForm.tomography_other" class="textarea textarea-bordered w-full textarea-sm" rows="3" placeholder="Enter details..."></textarea>
                </div>
            @endif
        </div>
    </div>
</div>

