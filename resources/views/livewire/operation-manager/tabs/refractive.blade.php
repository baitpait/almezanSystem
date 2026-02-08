<div class="space-y-6">
    <div class="alert alert-info">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm">Fill only the fields you need. Leave empty fields blank.</span>
    </div>

    {{-- Basic Info --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="label">
                <span class="label-text">Optometrist</span>
            </label>
            <input type="text" wire:model="refractiveForm.optometrist" class="form-input" placeholder="Enter optometrist name">
        </div>
        <div>
            <label class="label">
                <span class="label-text">Eyeglasses Age</span>
            </label>
            <input type="text" wire:model="refractiveForm.eyeglasses_age" class="form-input" placeholder="Enter age in years">
        </div>
        <div>
            <label class="label">
                <span class="label-text">Time with Current RX</span>
            </label>
            <input type="text" wire:model="refractiveForm.time_with_current_rx" class="form-input" placeholder="Enter duration">
        </div>
    </div>

    {{-- Contact Lenses --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="label">
                <span class="label-text">Contact Lenses</span>
            </label>
            <select wire:model="refractiveForm.contact_lenses" class="form-select form-select-sm">
                <option value="No">No</option>
                <option value="Soft">Soft</option>
                <option value="Hard">Hard</option>
            </select>
        </div>
        <div>
            <label class="label">
                <span class="label-text">Time without Lenses</span>
            </label>
            <input type="text" wire:model="refractiveForm.time_without_lenses" class="form-input" placeholder="Enter duration">
        </div>
    </div>

    {{-- Current Eyeglasses --}}
    <div class="divider">Current Eyeglasses</div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- OD --}}
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4">OD (Right Eye)</h3>
            <div class="grid grid-cols-4 gap-3">
                <div>
                    <label class="label label-text text-xs">Sphere</label>
                    <input type="text" wire:model="refractiveForm.current_eyeglasses_od_sphere" class="form-input" placeholder="0.00" dir="ltr" inputmode="decimal" autocomplete="off">
                </div>
                <div>
                    <label class="label label-text text-xs">Cylinder</label>
                    <input type="text" wire:model="refractiveForm.current_eyeglasses_od_cylinder" class="form-input" placeholder="0.00" dir="ltr" inputmode="decimal" autocomplete="off">
                </div>
                <div>
                    <label class="label label-text text-xs">Axis</label>
                    <input type="text" wire:model="refractiveForm.current_eyeglasses_od_axis" class="form-input" placeholder="0" dir="ltr" inputmode="numeric" autocomplete="off">
                </div>
                <div>
                    <label class="label label-text text-xs">Vision</label>
                    <input type="text" wire:model="refractiveForm.current_eyeglasses_od_vision" class="form-input" placeholder="20/20" dir="ltr" autocomplete="off">
                </div>
            </div>
        </div>

        {{-- OS --}}
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4">OS (Left Eye)</h3>
            <div class="grid grid-cols-4 gap-3">
                <div>
                    <label class="label label-text text-xs">Sphere</label>
                    <input type="text" wire:model="refractiveForm.current_eyeglasses_os_sphere" class="form-input" placeholder="0.00" dir="ltr" inputmode="decimal" autocomplete="off">
                </div>
                <div>
                    <label class="label label-text text-xs">Cylinder</label>
                    <input type="text" wire:model="refractiveForm.current_eyeglasses_os_cylinder" class="form-input" placeholder="0.00" dir="ltr" inputmode="decimal" autocomplete="off">
                </div>
                <div>
                    <label class="label label-text text-xs">Axis</label>
                    <input type="text" wire:model="refractiveForm.current_eyeglasses_os_axis" class="form-input" placeholder="0" dir="ltr" inputmode="numeric" autocomplete="off">
                </div>
                <div>
                    <label class="label label-text text-xs">Vision</label>
                    <input type="text" wire:model="refractiveForm.current_eyeglasses_os_vision" class="form-input" placeholder="20/20" dir="ltr" autocomplete="off">
                </div>
            </div>
        </div>
    </div>

    {{-- Dry Auto-Ref (Sphere, Cylinder, Axis only - no Vision) --}}
    <div class="divider">Dry Auto-Ref</div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- OD --}}
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4">OD (Right Eye)</h3>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="label label-text text-xs">Sphere</label>
                    <input type="text" wire:model="refractiveForm.dry_auto_ref_od_sphere" class="form-input" placeholder="0.00" dir="ltr" inputmode="decimal" autocomplete="off">
                </div>
                <div>
                    <label class="label label-text text-xs">Cylinder</label>
                    <input type="text" wire:model="refractiveForm.dry_auto_ref_od_cylinder" class="form-input" placeholder="0.00" dir="ltr" inputmode="decimal" autocomplete="off">
                </div>
                <div>
                    <label class="label label-text text-xs">Axis</label>
                    <input type="text" wire:model="refractiveForm.dry_auto_ref_od_axis" class="form-input" placeholder="0" dir="ltr" inputmode="numeric" autocomplete="off">
                </div>
            </div>
        </div>
        {{-- OS --}}
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4">OS (Left Eye)</h3>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="label label-text text-xs">Sphere</label>
                    <input type="text" wire:model="refractiveForm.dry_auto_ref_os_sphere" class="form-input" placeholder="0.00" dir="ltr" inputmode="decimal" autocomplete="off">
                </div>
                <div>
                    <label class="label label-text text-xs">Cylinder</label>
                    <input type="text" wire:model="refractiveForm.dry_auto_ref_os_cylinder" class="form-input" placeholder="0.00" dir="ltr" inputmode="decimal" autocomplete="off">
                </div>
                <div>
                    <label class="label label-text text-xs">Axis</label>
                    <input type="text" wire:model="refractiveForm.dry_auto_ref_os_axis" class="form-input" placeholder="0" dir="ltr" inputmode="numeric" autocomplete="off">
                </div>
            </div>
        </div>
    </div>

    {{-- Manifest Refraction --}}
    <div class="divider">Manifest Refraction</div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- OD --}}
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4">OD (Right Eye)</h3>
            <div class="space-y-3">
                {{-- UDVA in separate row --}}
                <div>
                    <label class="label label-text text-xs">UDVA</label>
                    <input type="text" wire:model="refractiveForm.manifest_refraction_od_udva" class="form-input" placeholder="Enter UDVA">
                </div>
                {{-- Fields in one row horizontally --}}
                <div class="grid grid-cols-5 gap-2">
                    <div>
                        <label class="label label-text text-xs">Sphere</label>
                        <input type="text" wire:model="refractiveForm.manifest_refraction_od_sphere" class="form-input" placeholder="0.00">
                    </div>
                    <div>
                        <label class="label label-text text-xs">Cylinder</label>
                        <input type="text" wire:model="refractiveForm.manifest_refraction_od_cylinder" class="form-input" placeholder="0.00">
                    </div>
                    <div>
                        <label class="label label-text text-xs">Axis</label>
                        <input type="text" wire:model="refractiveForm.manifest_refraction_od_axis" class="form-input" placeholder="0">
                    </div>
                    <div>
                        <label class="label label-text text-xs">BSCVA</label>
                        <input type="text" wire:model="refractiveForm.manifest_refraction_od_bscva" class="form-input" placeholder="20/20">
                    </div>
                    <div>
                        <label class="label label-text text-xs">R/G</label>
                        <select wire:model="refractiveForm.manifest_refraction_od_rg" class="form-select">
                            <option value="">—</option>
                            <option value="R=g">R=g</option>
                            <option value="R">R</option>
                            <option value="G">G</option>
                        </select>
                    </div>
                </div>
                {{-- DCNVA 40cm and Add J1 in separate row --}}
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="label label-text text-xs">DCNVA 40cm</label>
                        <input type="text" wire:model="refractiveForm.manifest_refraction_od_dcnva_40cm" class="form-input">
                    </div>
                    <div>
                        <label class="label label-text text-xs">Add J1</label>
                        <input type="text" wire:model="refractiveForm.manifest_refraction_od_add_j1" class="form-input">
                    </div>
                </div>
            </div>
        </div>

        {{-- OS --}}
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4">OS (Left Eye)</h3>
            <div class="space-y-3">
                {{-- UDVA in separate row --}}
                <div>
                    <label class="label label-text text-xs">UDVA</label>
                    <input type="text" wire:model="refractiveForm.manifest_refraction_os_udva" class="form-input" placeholder="Enter UDVA">
                </div>
                {{-- Fields in one row horizontally --}}
                <div class="grid grid-cols-5 gap-2">
                    <div>
                        <label class="label label-text text-xs">Sphere</label>
                        <input type="text" wire:model="refractiveForm.manifest_refraction_os_sphere" class="form-input" placeholder="0.00">
                    </div>
                    <div>
                        <label class="label label-text text-xs">Cylinder</label>
                        <input type="text" wire:model="refractiveForm.manifest_refraction_os_cylinder" class="form-input" placeholder="0.00">
                    </div>
                    <div>
                        <label class="label label-text text-xs">Axis</label>
                        <input type="text" wire:model="refractiveForm.manifest_refraction_os_axis" class="form-input" placeholder="0">
                    </div>
                    <div>
                        <label class="label label-text text-xs">BSCVA</label>
                        <input type="text" wire:model="refractiveForm.manifest_refraction_os_bscva" class="form-input" placeholder="20/20">
                    </div>
                    <div>
                        <label class="label label-text text-xs">R/G</label>
                        <select wire:model="refractiveForm.manifest_refraction_os_rg" class="form-select">
                            <option value="">—</option>
                            <option value="R=g">R=g</option>
                            <option value="R">R</option>
                            <option value="G">G</option>
                        </select>
                    </div>
                </div>
                {{-- DCNVA 40cm and Add J1 in separate row --}}
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="label label-text text-xs">DCNVA 40cm</label>
                        <input type="text" wire:model="refractiveForm.manifest_refraction_os_dcnva_40cm" class="form-input">
                    </div>
                    <div>
                        <label class="label label-text text-xs">Add J1</label>
                        <input type="text" wire:model="refractiveForm.manifest_refraction_os_add_j1" class="form-input">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dominant Eye & Monovision --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="label">
                <span class="label-text">Dominant Eye</span>
            </label>
            <select wire:model="refractiveForm.dominant_eye" class="form-select form-select-sm">
                <option value="">Select</option>
                <option value="OD">OD (Right)</option>
                <option value="OS">OS (Left)</option>
            </select>
        </div>
        <div>
            <label class="label">
                <span class="label-text">Simulation for Monovision</span>
            </label>
            <input type="text" wire:model="refractiveForm.simulation_for_monovision" class="form-input" placeholder="Enter simulation details">
        </div>
    </div>

    {{-- Refraction After Dilatation --}}
    <div class="divider">Refraction After Dilatation</div>
    
    {{-- Dilation Type Selection --}}
    <div class="mb-4 flex gap-4">
        <label class="label cursor-pointer gap-2">
            <input type="radio" name="refraction_after_dilation_type" value="Mydramide" wire:model="refractiveForm.refraction_after_dilation_type" class="radio radio-primary">
            <span class="label-text">Mydramide</span>
        </label>
        <label class="label cursor-pointer gap-2">
            <input type="radio" name="refraction_after_dilation_type" value="CYCLO" wire:model="refractiveForm.refraction_after_dilation_type" class="radio radio-primary">
            <span class="label-text">CYCLO</span>
        </label>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- OD --}}
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4">OD (Right Eye)</h3>
            <div class="grid grid-cols-4 gap-2">
                <div>
                    <label class="label label-text text-xs">Sphere</label>
                    <input type="text" wire:model="refractiveForm.refraction_after_dilation_od_sphere" class="form-input" placeholder="0.00">
                </div>
                <div>
                    <label class="label label-text text-xs">Cylinder</label>
                    <input type="text" wire:model="refractiveForm.refraction_after_dilation_od_cylinder" class="form-input" placeholder="0.00">
                </div>
                <div>
                    <label class="label label-text text-xs">Axis</label>
                    <input type="text" wire:model="refractiveForm.refraction_after_dilation_od_axis" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="label label-text text-xs">Vision</label>
                    <input type="text" wire:model="refractiveForm.refraction_after_dilation_od_vision" class="form-input" placeholder="20/20">
                </div>
            </div>
        </div>

        {{-- OS --}}
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4">OS (Left Eye)</h3>
            <div class="grid grid-cols-4 gap-2">
                <div>
                    <label class="label label-text text-xs">Sphere</label>
                    <input type="text" wire:model="refractiveForm.refraction_after_dilation_os_sphere" class="form-input" placeholder="0.00">
                </div>
                <div>
                    <label class="label label-text text-xs">Cylinder</label>
                    <input type="text" wire:model="refractiveForm.refraction_after_dilation_os_cylinder" class="form-input" placeholder="0.00">
                </div>
                <div>
                    <label class="label label-text text-xs">Axis</label>
                    <input type="text" wire:model="refractiveForm.refraction_after_dilation_os_axis" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="label label-text text-xs">Vision</label>
                    <input type="text" wire:model="refractiveForm.refraction_after_dilation_os_vision" class="form-input" placeholder="20/20">
                </div>
            </div>
        </div>
    </div>

    {{-- Pupil Diameter --}}
    <div class="divider">Pupil Diameter</div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- OD --}}
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4">OD (Right Eye)</h3>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="label label-text text-xs">Mesopic</label>
                    <input type="text" wire:model="refractiveForm.pupil_diameter_od_mesopic" class="form-input">
                </div>
                <div>
                    <label class="label label-text text-xs">Scotopic</label>
                    <input type="text" wire:model="refractiveForm.pupil_diameter_od_scotopic" class="form-input">
                </div>
            </div>
        </div>

        {{-- OS --}}
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4">OS (Left Eye)</h3>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="label label-text text-xs">Mesopic</label>
                    <input type="text" wire:model="refractiveForm.pupil_diameter_os_mesopic" class="form-input">
                </div>
                <div>
                    <label class="label label-text text-xs">Scotopic</label>
                    <input type="text" wire:model="refractiveForm.pupil_diameter_os_scotopic" class="form-input">
                </div>
            </div>
        </div>
    </div>

</div>

