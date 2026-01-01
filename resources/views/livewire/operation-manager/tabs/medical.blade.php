<div class="space-y-6">
    {{-- Vision Stability Section --}}
    <div class="card bg-base-200 p-4">
        <h3 class="font-semibold mb-4 text-lg">Vision Stability</h3>
        <div class="overflow-x-auto">
            <table class="table table-sm w-full">
                <thead>
                    <tr class="bg-base-300">
                        <th class="text-left">Question</th>
                        <th class="text-center w-24">Yes</th>
                        <th class="text-center w-24">No</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-medium">C/O glare, halos or squint</td>
                        <td class="text-center">
                            <input type="radio" name="glare_halos_squint" wire:model="medicalForm.glare_halos_squint" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="glare_halos_squint" wire:model="medicalForm.glare_halos_squint" value="" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Refraction stable more than 1 year</td>
                        <td class="text-center">
                            <input type="radio" name="refraction_stable_1year" wire:model="medicalForm.refraction_stable_1year" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="refraction_stable_1year" wire:model="medicalForm.refraction_stable_1year" value="" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Contact lens use</td>
                        <td class="text-center">
                            <input type="radio" name="contact_lens_use" wire:model="medicalForm.contact_lens_use" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="contact_lens_use" wire:model="medicalForm.contact_lens_use" value="" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Medical History Section --}}
    <div class="card bg-base-200 p-4">
        <h3 class="font-semibold mb-4 text-lg">Medical History</h3>
        <div class="overflow-x-auto">
            <table class="table table-sm w-full">
                <thead>
                    <tr class="bg-base-300">
                        <th class="text-left">Question</th>
                        <th class="text-center w-24">Yes</th>
                        <th class="text-center w-24">No</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-medium">Diabetes</td>
                        <td class="text-center">
                            <input type="radio" name="diabetes" wire:model="medicalForm.diabetes" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="diabetes" wire:model="medicalForm.diabetes" value="" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Chronic disease (joint pain, skin, hormones)</td>
                        <td class="text-center">
                            <input type="radio" name="chronic_disease" wire:model="medicalForm.chronic_disease" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="chronic_disease" wire:model="medicalForm.chronic_disease" value="" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Herpes keratitis</td>
                        <td class="text-center">
                            <input type="radio" name="herpes_keratitis" wire:model="medicalForm.herpes_keratitis" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="herpes_keratitis" wire:model="medicalForm.herpes_keratitis" value="" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Glaucoma</td>
                        <td class="text-center">
                            <input type="radio" name="glaucoma" wire:model="medicalForm.glaucoma" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="glaucoma" wire:model="medicalForm.glaucoma" value="" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Family Hx of Keratoconus</td>
                        <td class="text-center">
                            <input type="radio" name="family_history_keratoconus" wire:model="medicalForm.family_history_keratoconus" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="family_history_keratoconus" wire:model="medicalForm.family_history_keratoconus" value="" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Eye rubber</td>
                        <td class="text-center">
                            <input type="radio" name="eye_rubber" wire:model="medicalForm.eye_rubber" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="eye_rubber" wire:model="medicalForm.eye_rubber" value="" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Pregnancy</td>
                        <td class="text-center">
                            <input type="radio" name="pregnancy" wire:model="medicalForm.pregnancy" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="pregnancy" wire:model="medicalForm.pregnancy" value="" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Ocular surgery</td>
                        <td class="text-center">
                            <input type="radio" name="ocular_surgery" wire:model.live="medicalForm.ocular_surgery" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="ocular_surgery" wire:model.live="medicalForm.ocular_surgery" value="0" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                    @if($medicalForm['ocular_surgery'] == '1' || $medicalForm['ocular_surgery'] === true || $medicalForm['ocular_surgery'] == 1)
                        <tr wire:key="ocular_surgery_details">
                            <td colspan="3" class="bg-blue-50 p-3 border border-blue-200">
                                <input type="text" wire:model="medicalForm.ocular_surgery_details" class="input input-bordered w-full input-sm" placeholder="Enter ocular surgery details...">
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td class="font-medium">Family History of ocular disease</td>
                        <td class="text-center">
                            <input type="radio" name="family_history_ocular_disease_yes" wire:model.live="medicalForm.family_history_ocular_disease_yes" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="family_history_ocular_disease_yes" wire:model.live="medicalForm.family_history_ocular_disease_yes" value="0" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                    @if($medicalForm['family_history_ocular_disease_yes'] == '1' || $medicalForm['family_history_ocular_disease_yes'] === true || $medicalForm['family_history_ocular_disease_yes'] == 1)
                        <tr wire:key="family_history_details">
                            <td colspan="3" class="bg-blue-50 p-3 border border-blue-200">
                                <input type="text" wire:model="medicalForm.family_history_ocular_disease" class="input input-bordered w-full input-sm" placeholder="Enter family history of ocular disease details...">
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td class="font-medium">Any current medication (Ex. Roaccutane, etc)</td>
                        <td class="text-center">
                            <input type="radio" name="current_medications_yes" wire:model.live="medicalForm.current_medications_yes" value="1" class="radio radio-primary radio-sm">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="current_medications_yes" wire:model.live="medicalForm.current_medications_yes" value="0" class="radio radio-primary radio-sm">
                        </td>
                    </tr>
                    @if($medicalForm['current_medications_yes'] == '1' || $medicalForm['current_medications_yes'] === true || $medicalForm['current_medications_yes'] == 1)
                        <tr wire:key="current_medications_details">
                            <td colspan="3" class="bg-blue-50 p-3 border border-blue-200">
                                <input type="text" wire:model="medicalForm.current_medications" class="input input-bordered w-full input-sm" placeholder="Enter current medications details...">
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
