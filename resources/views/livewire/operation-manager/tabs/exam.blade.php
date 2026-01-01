<div class="space-y-6">
    {{-- Eye Examination Table --}}
    <div class="card bg-base-200 p-4">
        <h3 class="font-semibold mb-4 text-lg">Exam:</h3>
        <div class="overflow-x-auto">
            <table class="table table-sm w-full">
                <thead>
                    <tr class="bg-base-300">
                        <th class="text-center w-1/3">OD</th>
                        <th class="text-center w-1/3"></th>
                        <th class="text-center w-1/3">OS</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- 1. IOP --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_iop" class="input input-bordered w-full input-sm text-center" placeholder="">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">IOP</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_iop" class="input input-bordered w-full input-sm text-center" placeholder="">
                        </td>
                    </tr>
                    {{-- 2. TBUT --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_tbut" class="input input-bordered w-full input-sm text-center" placeholder="">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">TBUT</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_tbut" class="input input-bordered w-full input-sm text-center" placeholder="">
                        </td>
                    </tr>
                    {{-- 3. Schirmer --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_schirmer" class="input input-bordered w-full input-sm text-center" placeholder="">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">Schirmer</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_schirmer" class="input input-bordered w-full input-sm text-center" placeholder="">
                        </td>
                    </tr>
                    {{-- 4. Eyelids --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_lids" class="input input-bordered w-full input-sm text-center" placeholder="Normal">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">Eyelids</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_lids" class="input input-bordered w-full input-sm text-center" placeholder="Normal">
                        </td>
                    </tr>
                    {{-- 5. Conjunctiva --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_conjunctiva" class="input input-bordered w-full input-sm text-center" placeholder="Normal">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">Conjunctiva</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_conjunctiva" class="input input-bordered w-full input-sm text-center" placeholder="Normal">
                        </td>
                    </tr>
                    {{-- 6. Cornea --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_cornea" class="input input-bordered w-full input-sm text-center" placeholder="Clear">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">Cornea</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_cornea" class="input input-bordered w-full input-sm text-center" placeholder="Clear">
                        </td>
                    </tr>
                    {{-- 7. Anterior chamber --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_anterior_chamber" class="input input-bordered w-full input-sm text-center" placeholder="Deep and quiet">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">Anterior chamber</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_anterior_chamber" class="input input-bordered w-full input-sm text-center" placeholder="Deep and quiet">
                        </td>
                    </tr>
                    {{-- 8. Iris/ pupil --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_iris_pupil" class="input input-bordered w-full input-sm text-center" placeholder="Normal">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">Iris/ pupil</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_iris_pupil" class="input input-bordered w-full input-sm text-center" placeholder="Normal">
                        </td>
                    </tr>
                    {{-- 9. Lens --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_lens" class="input input-bordered w-full input-sm text-center" placeholder="Clear">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">Lens</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_lens" class="input input-bordered w-full input-sm text-center" placeholder="Clear">
                        </td>
                    </tr>
                    {{-- 10. vitreous --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_vitreous" class="input input-bordered w-full input-sm text-center" placeholder="Clear">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">vitreous</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_vitreous" class="input input-bordered w-full input-sm text-center" placeholder="Clear">
                        </td>
                    </tr>
                    {{-- 11. Disc --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_optic_disc" class="input input-bordered w-full input-sm text-center" placeholder="Normal">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">Disc</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_optic_disc" class="input input-bordered w-full input-sm text-center" placeholder="Normal">
                        </td>
                    </tr>
                    {{-- 12. Macula --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_macula" class="input input-bordered w-full input-sm text-center" placeholder="Normal">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">Macula</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_macula" class="input input-bordered w-full input-sm text-center" placeholder="Normal">
                        </td>
                    </tr>
                    {{-- 13. Retina --}}
                    <tr>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.od_retina" class="input input-bordered w-full input-sm text-center" placeholder="Normal">
                        </td>
                        <td class="font-semibold text-base text-center align-middle" style="color: #111827 !important;">Retina</td>
                        <td class="text-center">
                            <input type="text" wire:model="examForm.os_retina" class="input input-bordered w-full input-sm text-center" placeholder="Normal">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
