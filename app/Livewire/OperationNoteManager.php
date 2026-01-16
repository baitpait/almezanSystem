<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\OperationNote;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Livewire\Component;

class OperationNoteManager extends Component
{
    public $appointmentId = null;
    public $editingId = null;
    public $showModal = false;

    // Form Data
    public array $form = [
        'appointment_id' => null,
        'patient_id' => null,
        'doctor_id' => null,
        'operation_type' => '',
        'operation_type_od' => '',
        'operation_type_os' => '',
        'operation_eye' => 'OU',
        'monovision_eye' => '',
        'same_operation_type_both_eyes' => false,
        'eye_drops_vigamox' => false,
        'eye_drops_pred_forte' => false,
        'eye_drops_other' => false,
        'eye_drops_other_details' => '',
        // New separate Eye Drops fields for OD and OS
        'eye_drops_vigamox_od' => false,
        'eye_drops_vigamox_os' => false,
        'eye_drops_pred_forte_od' => false,
        'eye_drops_pred_forte_os' => false,
        'eye_drops_other_od' => false,
        'eye_drops_other_os' => false,
        'eye_drops_other_details_od' => '',
        'eye_drops_other_details_os' => '',
        // Old shared fields (kept for backward compatibility)
        'prk_epithelial_removal' => '',
        'prk_excimer_profile' => '',
        'prk_mmc_0_02_percent' => false,
        'prk_bandage_contact_lens' => false,
        'femto_flap_done' => null,
        'femto_excimer_profile' => '',
        'femto_bandage_contact_lens' => false,
        'smile_complete_lenticule_separation' => null,
        'smile_complete_lenticule_extraction' => null,
        'ptk_epithelial_removal' => '',
        'ptk_excimer_profile' => '',
        'ptk_mmc_0_02_percent' => false,
        'ptk_bandage_contact_lens' => false,
        // New separate fields for OD
        'prk_epithelial_removal_od' => '',
        'prk_excimer_profile_od' => '',
        'prk_mmc_0_02_percent_od' => false,
        'prk_bandage_contact_lens_od' => false,
        'femto_flap_done_od' => null,
        'femto_excimer_profile_od' => '',
        'femto_bandage_contact_lens_od' => false,
        'smile_complete_lenticule_separation_od' => null,
        'smile_complete_lenticule_extraction_od' => null,
        'ptk_epithelial_removal_od' => '',
        'ptk_excimer_profile_od' => '',
        'ptk_mmc_0_02_percent_od' => false,
        'ptk_bandage_contact_lens_od' => false,
        // New separate fields for OS
        'prk_epithelial_removal_os' => '',
        'prk_excimer_profile_os' => '',
        'prk_mmc_0_02_percent_os' => false,
        'prk_bandage_contact_lens_os' => false,
        'femto_flap_done_os' => null,
        'femto_excimer_profile_os' => '',
        'femto_bandage_contact_lens_os' => false,
        'smile_complete_lenticule_separation_os' => null,
        'smile_complete_lenticule_extraction_os' => null,
        'ptk_epithelial_removal_os' => '',
        'ptk_excimer_profile_os' => '',
        'ptk_mmc_0_02_percent_os' => false,
        'ptk_bandage_contact_lens_os' => false,
        // Target fields for each operation type
        'prk_target_od' => '',
        'prk_target_os' => '',
        'femto_target_od' => '',
        'femto_target_os' => '',
        'smile_target_od' => '',
        'smile_target_os' => '',
        'ptk_target_od' => '',
        'ptk_target_os' => '',
        // General MMC fields for all operation types
        'mmc_0_02_percent_od' => false,
        'mmc_duration_sec_od' => '',
        'mmc_0_02_percent_os' => false,
        'mmc_duration_sec_os' => '',
        'notes' => '',
    ];

    public function mount($appointmentId = null): void
    {
        // Force operation_eye to OU for Operation Note (always both eyes)
        $this->form['operation_eye'] = 'OU';
        
        // Get appointmentId from route parameter or query string
        $appointmentId = $appointmentId ?? request()->route('appointmentId') ?? request()->query('appointmentId');
        
        if ($appointmentId) {
            $this->appointmentId = $appointmentId;
            $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($appointmentId);
            
            // Auto-fill patient and doctor from appointment
            $this->form['appointment_id'] = $appointment->id;
            $this->form['patient_id'] = $appointment->patient_id;
            $this->form['doctor_id'] = $appointment->doctor_id;
            
            // Check if operation note already exists
            $existingNote = OperationNote::where('appointment_id', $appointmentId)->first();
            if ($existingNote) {
                $this->edit($existingNote->id);
            }
        }
    }

    /**
     * Cancel and redirect to Scheduled Operations page
     * 
     * Business Purpose: When user clicks Cancel button, update visit_stage back to appropriate state
     * (waiting/scheduled/completed) based on appointment date, then redirect to Scheduled Operations page.
     */
    public function cancel(): void
    {
        // Update visit_stage back to appropriate state based on appointment date
        if ($this->form['appointment_id'] ?? null) {
            try {
                $appointment = \App\Models\Appointment::find($this->form['appointment_id']);
                if ($appointment && !in_array($appointment->visit_stage, ['completed', 'cancelled'])) {
                    // Calculate appropriate visit_stage based on appointment date
                    $newVisitStage = \App\Models\Appointment::calculateVisitStage($appointment->appointment_date);
                    $appointment->update(['visit_stage' => $newVisitStage]);
                }
            } catch (\Exception $e) {
                // Log error but don't prevent redirect
                \Log::error('OperationNoteManager cancel error: ' . $e->getMessage());
            }
        }
        
        $this->redirect(route('scheduled-operations.index'));
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->form = [
            'appointment_id' => $this->form['appointment_id'] ?? null,
            'patient_id' => $this->form['patient_id'] ?? null,
            'doctor_id' => $this->form['doctor_id'] ?? null,
            'operation_type' => '',
            'operation_type_od' => '',
            'operation_type_os' => '',
            'operation_eye' => 'OU',
            'monovision_eye' => '',
            'same_operation_type_both_eyes' => false,
            'eye_drops_vigamox' => false,
            'eye_drops_pred_forte' => false,
            'eye_drops_other' => false,
            'eye_drops_other_details' => '',
            // New separate Eye Drops fields for OD and OS
            'eye_drops_vigamox_od' => false,
            'eye_drops_vigamox_os' => false,
            'eye_drops_pred_forte_od' => false,
            'eye_drops_pred_forte_os' => false,
            'eye_drops_other_od' => false,
            'eye_drops_other_os' => false,
            'eye_drops_other_details_od' => '',
            'eye_drops_other_details_os' => '',
            // Old shared fields
            'prk_epithelial_removal' => '',
            'prk_excimer_profile' => '',
            'prk_mmc_0_02_percent' => false,
            'prk_bandage_contact_lens' => false,
            'femto_flap_done' => null,
            'femto_excimer_profile' => '',
            'femto_bandage_contact_lens' => false,
            'smile_complete_lenticule_separation' => null,
            'smile_complete_lenticule_extraction' => null,
            'ptk_epithelial_removal' => '',
            'ptk_excimer_profile' => '',
            'ptk_mmc_0_02_percent' => false,
            'ptk_bandage_contact_lens' => false,
            // New separate fields for OD
            'prk_epithelial_removal_od' => '',
            'prk_excimer_profile_od' => '',
            'prk_mmc_0_02_percent_od' => false,
            'prk_bandage_contact_lens_od' => false,
            'femto_flap_done_od' => null,
            'femto_excimer_profile_od' => '',
            'femto_bandage_contact_lens_od' => false,
            'smile_complete_lenticule_separation_od' => null,
            'smile_complete_lenticule_extraction_od' => null,
            'ptk_epithelial_removal_od' => '',
            'ptk_excimer_profile_od' => '',
            'ptk_mmc_0_02_percent_od' => false,
            'ptk_bandage_contact_lens_od' => false,
            // New separate fields for OS
            'prk_epithelial_removal_os' => '',
            'prk_excimer_profile_os' => '',
            'prk_mmc_0_02_percent_os' => false,
            'prk_bandage_contact_lens_os' => false,
            'femto_flap_done_os' => null,
            'femto_excimer_profile_os' => '',
            'femto_bandage_contact_lens_os' => false,
            'smile_complete_lenticule_separation_os' => null,
            'smile_complete_lenticule_extraction_os' => null,
            'ptk_epithelial_removal_os' => '',
            'ptk_excimer_profile_os' => '',
            'ptk_mmc_0_02_percent_os' => false,
            'ptk_bandage_contact_lens_os' => false,
            // Target fields for each operation type
            'prk_target_od' => '',
            'prk_target_os' => '',
            'femto_target_od' => '',
            'femto_target_os' => '',
            'smile_target_od' => '',
            'smile_target_os' => '',
            'ptk_target_od' => '',
            'ptk_target_os' => '',
            // General MMC fields for all operation types
            'mmc_0_02_percent_od' => false,
            'mmc_duration_sec_od' => '',
            'mmc_0_02_percent_os' => false,
            'mmc_duration_sec_os' => '',
            'notes' => '',
        ];
        $this->showModal = false;
    }

    public function updatedFormOperationType($value): void
    {
        // Clear operation-specific fields when operation type changes
        $this->form['prk_epithelial_removal'] = '';
        $this->form['prk_excimer_profile'] = '';
        $this->form['prk_mmc_0_02_percent'] = false;
        $this->form['prk_bandage_contact_lens'] = false;
        $this->form['femto_flap_done'] = null;
        $this->form['femto_excimer_profile'] = '';
        $this->form['femto_bandage_contact_lens'] = false;
        $this->form['smile_complete_lenticule_separation'] = null;
        $this->form['smile_complete_lenticule_extraction'] = null;
        $this->form['ptk_epithelial_removal'] = '';
        $this->form['ptk_excimer_profile'] = '';
        $this->form['ptk_mmc_0_02_percent'] = false;
        $this->form['ptk_bandage_contact_lens'] = false;
    }

    public function updatedFormOperationEye($value): void
    {
        if ($value === 'OU') {
            // For both eyes (OU): initialize operation_type_od and operation_type_os from operation_type if not set
            if (!empty($this->form['operation_type'])) {
                if (empty($this->form['operation_type_od'])) {
                    $this->form['operation_type_od'] = $this->form['operation_type'];
                }
                if (empty($this->form['operation_type_os'])) {
                    $this->form['operation_type_os'] = $this->form['operation_type'];
                }
            }
            // If both are the same, set same_operation_type_both_eyes
            if (!empty($this->form['operation_type_od']) && 
                !empty($this->form['operation_type_os']) &&
                $this->form['operation_type_od'] === $this->form['operation_type_os']) {
                $this->form['same_operation_type_both_eyes'] = true;
            }
        } elseif ($value === 'OD') {
            // For single eye (OD): use operation_type_od, clear operation_type_os
            if (!empty($this->form['operation_type_od'])) {
                $this->form['operation_type'] = $this->form['operation_type_od'];
            } elseif (!empty($this->form['operation_type'])) {
                // If operation_type exists but operation_type_od doesn't, copy it
                $this->form['operation_type_od'] = $this->form['operation_type'];
            }
            $this->form['operation_type_os'] = ''; // Clear OS
            $this->form['same_operation_type_both_eyes'] = false;
        } elseif ($value === 'OS') {
            // For single eye (OS): use operation_type_os, clear operation_type_od
            if (!empty($this->form['operation_type_os'])) {
                $this->form['operation_type'] = $this->form['operation_type_os'];
            } elseif (!empty($this->form['operation_type'])) {
                // If operation_type exists but operation_type_os doesn't, copy it
                $this->form['operation_type_os'] = $this->form['operation_type'];
            }
            $this->form['operation_type_od'] = ''; // Clear OD
            $this->form['same_operation_type_both_eyes'] = false;
        }
    }

    public function updatedFormOperationTypeOd($value): void
    {
        $this->form['operation_type_od'] = $value;
        
        // If same_operation_type_both_eyes is checked, sync OS operation type and copy OD fields to OS
        if ($this->form['same_operation_type_both_eyes']) {
            $this->form['operation_type_os'] = $value;
            
            // Copy OD-specific fields to OS-specific fields
            if ($value === 'PRK') {
                $this->form['prk_epithelial_removal_os'] = $this->form['prk_epithelial_removal_od'] ?? '';
                $this->form['prk_excimer_profile_os'] = $this->form['prk_excimer_profile_od'] ?? '';
                $this->form['prk_mmc_0_02_percent_os'] = $this->form['prk_mmc_0_02_percent_od'] ?? false;
                $this->form['prk_bandage_contact_lens_os'] = $this->form['prk_bandage_contact_lens_od'] ?? false;
                $this->form['prk_target_os'] = $this->form['prk_target_od'] ?? '';
            } elseif ($value === 'Femto-LASIK') {
                $this->form['femto_flap_done_os'] = $this->form['femto_flap_done_od'] ?? null;
                $this->form['femto_excimer_profile_os'] = $this->form['femto_excimer_profile_od'] ?? '';
                $this->form['femto_bandage_contact_lens_os'] = $this->form['femto_bandage_contact_lens_od'] ?? false;
                $this->form['femto_target_os'] = $this->form['femto_target_od'] ?? '';
            } elseif ($value === 'SMILE') {
                $this->form['smile_complete_lenticule_separation_os'] = $this->form['smile_complete_lenticule_separation_od'] ?? null;
                $this->form['smile_complete_lenticule_extraction_os'] = $this->form['smile_complete_lenticule_extraction_od'] ?? null;
                $this->form['smile_target_os'] = $this->form['smile_target_od'] ?? '';
            } elseif ($value === 'PTK') {
                $this->form['ptk_epithelial_removal_os'] = $this->form['ptk_epithelial_removal_od'] ?? '';
                $this->form['ptk_excimer_profile_os'] = $this->form['ptk_excimer_profile_od'] ?? '';
                $this->form['ptk_mmc_0_02_percent_os'] = $this->form['ptk_mmc_0_02_percent_od'] ?? false;
                $this->form['ptk_bandage_contact_lens_os'] = $this->form['ptk_bandage_contact_lens_od'] ?? false;
                $this->form['ptk_target_os'] = $this->form['ptk_target_od'] ?? '';
                // Copy general MMC fields
                $this->form['mmc_0_02_percent_os'] = $this->form['mmc_0_02_percent_od'] ?? false;
                $this->form['mmc_duration_sec_os'] = $this->form['mmc_duration_sec_od'] ?? '';
            }
        }
    }

    public function updatedFormOperationTypeOs($value): void
    {
        $this->form['operation_type_os'] = $value;
        
        // If same_operation_type_both_eyes is checked but values are different, uncheck it
        if ($this->form['same_operation_type_both_eyes'] && 
            $value !== $this->form['operation_type_od']) {
            $this->form['same_operation_type_both_eyes'] = false;
        }
    }

    public function updatedFormSameOperationTypeBothEyes($value): void
    {
        if ($value) {
            // Copy OD operation type to OS
            if (!empty($this->form['operation_type_od'])) {
                $this->form['operation_type_os'] = $this->form['operation_type_od'];
                
                // Copy OD-specific fields to OS-specific fields
                $operationType = $this->form['operation_type_od'];
                
                // Copy general MMC fields (applies to all operation types)
                // Only copy if OS fields are empty to prevent overwriting user-entered data
                if (empty($this->form['mmc_0_02_percent_os'])) {
                    $this->form['mmc_0_02_percent_os'] = $this->form['mmc_0_02_percent_od'] ?? false;
                }
                if (empty($this->form['mmc_duration_sec_os'])) {
                    $this->form['mmc_duration_sec_os'] = $this->form['mmc_duration_sec_od'] ?? '';
                }
                
                if ($operationType === 'PRK') {
                    // Only copy if OS fields are empty
                    if (empty($this->form['prk_epithelial_removal_os'])) {
                        $this->form['prk_epithelial_removal_os'] = $this->form['prk_epithelial_removal_od'] ?? '';
                    }
                    if (empty($this->form['prk_excimer_profile_os'])) {
                        $this->form['prk_excimer_profile_os'] = $this->form['prk_excimer_profile_od'] ?? '';
                    }
                    if (empty($this->form['prk_mmc_0_02_percent_os'])) {
                        $this->form['prk_mmc_0_02_percent_os'] = $this->form['prk_mmc_0_02_percent_od'] ?? false;
                    }
                    if (empty($this->form['prk_bandage_contact_lens_os'])) {
                        $this->form['prk_bandage_contact_lens_os'] = $this->form['prk_bandage_contact_lens_od'] ?? false;
                    }
                    if (empty($this->form['prk_target_os'])) {
                        $this->form['prk_target_os'] = $this->form['prk_target_od'] ?? '';
                    }
                } elseif ($operationType === 'Femto-LASIK') {
                    // Only copy if OS fields are empty
                    if (empty($this->form['femto_flap_done_os'])) {
                        $this->form['femto_flap_done_os'] = $this->form['femto_flap_done_od'] ?? null;
                    }
                    if (empty($this->form['femto_excimer_profile_os'])) {
                        $this->form['femto_excimer_profile_os'] = $this->form['femto_excimer_profile_od'] ?? '';
                    }
                    if (empty($this->form['femto_bandage_contact_lens_os'])) {
                        $this->form['femto_bandage_contact_lens_os'] = $this->form['femto_bandage_contact_lens_od'] ?? false;
                    }
                    if (empty($this->form['femto_target_os'])) {
                        $this->form['femto_target_os'] = $this->form['femto_target_od'] ?? '';
                    }
                } elseif ($operationType === 'SMILE') {
                    // Only copy if OS fields are empty
                    if (empty($this->form['smile_complete_lenticule_separation_os'])) {
                        $this->form['smile_complete_lenticule_separation_os'] = $this->form['smile_complete_lenticule_separation_od'] ?? null;
                    }
                    if (empty($this->form['smile_complete_lenticule_extraction_os'])) {
                        $this->form['smile_complete_lenticule_extraction_os'] = $this->form['smile_complete_lenticule_extraction_od'] ?? null;
                    }
                    if (empty($this->form['smile_target_os'])) {
                        $this->form['smile_target_os'] = $this->form['smile_target_od'] ?? '';
                    }
                } elseif ($operationType === 'PTK') {
                    // Only copy if OS fields are empty
                    if (empty($this->form['ptk_epithelial_removal_os'])) {
                        $this->form['ptk_epithelial_removal_os'] = $this->form['ptk_epithelial_removal_od'] ?? '';
                    }
                    if (empty($this->form['ptk_excimer_profile_os'])) {
                        $this->form['ptk_excimer_profile_os'] = $this->form['ptk_excimer_profile_od'] ?? '';
                    }
                    if (empty($this->form['ptk_mmc_0_02_percent_os'])) {
                        $this->form['ptk_mmc_0_02_percent_os'] = $this->form['ptk_mmc_0_02_percent_od'] ?? false;
                    }
                    if (empty($this->form['ptk_bandage_contact_lens_os'])) {
                        $this->form['ptk_bandage_contact_lens_os'] = $this->form['ptk_bandage_contact_lens_od'] ?? false;
                    }
                    if (empty($this->form['ptk_target_os'])) {
                        $this->form['ptk_target_os'] = $this->form['ptk_target_od'] ?? '';
                    }
                }
                
                // Do NOT copy notes field - it should remain separate for each eye
                // Notes field is shared and should not be duplicated
            }
        }
    }

    public function save(): void
    {
        // Operation Note is always for both eyes (OU)
        $this->form['operation_eye'] = 'OU';
        $operationEye = 'OU';
        
        $validationRules = [
            'form.appointment_id' => 'required|exists:appointments,id',
            'form.patient_id' => 'required|exists:patients,id',
            'form.doctor_id' => 'required|exists:doctors,id',
            'form.operation_eye' => 'required|in:OU',
            'form.operation_type_od' => 'required|in:PRK,Femto-LASIK,SMILE,PTK',
            'form.operation_type_os' => 'required|in:PRK,Femto-LASIK,SMILE,PTK',
        ];

        $this->validate($validationRules, [
            'form.appointment_id.required' => 'Appointment is required.',
            'form.operation_type.required' => 'Operation type is required.',
            'form.operation_type.in' => 'Invalid operation type selected.',
            'form.operation_type_od.required' => 'Operation type for OD is required.',
            'form.operation_type_od.in' => 'Invalid operation type selected for OD.',
            'form.operation_type_os.required' => 'Operation type for OS is required.',
            'form.operation_type_os.in' => 'Invalid operation type selected for OS.',
        ]);

        try {
            $data = $this->form;
            
            // Convert same_operation_type_both_eyes to boolean
            $data['same_operation_type_both_eyes'] = in_array($data['same_operation_type_both_eyes'], ['1', 1, true, 'yes', 'on'], true);
            
            // Operation Note is always for both eyes (OU)
            $data['operation_eye'] = 'OU';
            $operationEye = 'OU';
            
            if ($operationEye === 'OU') {
                // For both eyes (OU):
                // - If same_operation_type_both_eyes was checked and both are the same, use that value
                // - Otherwise, keep operation_type_od and operation_type_os separate
                // - Set operation_type to the value that matches both (if same) or leave it as fallback
                if ($this->form['same_operation_type_both_eyes'] && 
                    !empty($data['operation_type_od']) && 
                    !empty($data['operation_type_os']) &&
                    $data['operation_type_od'] === $data['operation_type_os']) {
                    $data['operation_type'] = $data['operation_type_od'];
                } elseif (!empty($data['operation_type_od']) && !empty($data['operation_type_os']) &&
                          $data['operation_type_od'] === $data['operation_type_os']) {
                    // If both are the same (even without checkbox), sync to operation_type
                    $data['operation_type'] = $data['operation_type_od'];
                } else {
                    // Different types for each eye - keep operation_type as fallback (use OD or first available)
                    $data['operation_type'] = $data['operation_type_od'] ?? $data['operation_type_os'] ?? '';
                }
            } elseif ($operationEye === 'OD') {
                // For single eye (OD): use operation_type_od, clear operation_type_os
                if (!empty($data['operation_type_od'])) {
                    $data['operation_type'] = $data['operation_type_od'];
                }
                $data['operation_type_os'] = null; // Clear OS since it's not used
            } elseif ($operationEye === 'OS') {
                // For single eye (OS): use operation_type_os, clear operation_type_od
                if (!empty($data['operation_type_os'])) {
                    $data['operation_type'] = $data['operation_type_os'];
                }
                $data['operation_type_od'] = null; // Clear OD since it's not used
            }
            
            // Convert boolean-like values (old shared fields)
            $booleanFields = [
                'eye_drops_vigamox', 'eye_drops_pred_forte', 
                'eye_drops_other', 'prk_mmc_0_02_percent', 'prk_bandage_contact_lens',
                'femto_bandage_contact_lens', 'ptk_mmc_0_02_percent', 'ptk_bandage_contact_lens',
            ];
            
            foreach ($booleanFields as $field) {
                if (isset($data[$field])) {
                    $data[$field] = in_array($data[$field], ['1', 1, true, 'yes', 'on'], true);
                }
            }
            
            // Convert boolean-like values (new separate Eye Drops fields)
            $eyeDropsBooleanFields = [
                'eye_drops_vigamox_od', 'eye_drops_vigamox_os',
                'eye_drops_pred_forte_od', 'eye_drops_pred_forte_os',
                'eye_drops_other_od', 'eye_drops_other_os',
            ];
            
            foreach ($eyeDropsBooleanFields as $field) {
                if (isset($data[$field])) {
                    $data[$field] = in_array($data[$field], ['1', 1, true, 'yes', 'on'], true);
                }
            }
            
            // Convert boolean-like values (new separate OD fields)
            $booleanFieldsOd = [
                'prk_mmc_0_02_percent_od', 'prk_bandage_contact_lens_od',
                'femto_bandage_contact_lens_od', 'ptk_mmc_0_02_percent_od', 'ptk_bandage_contact_lens_od',
                'mmc_0_02_percent_od',
            ];
            
            foreach ($booleanFieldsOd as $field) {
                if (isset($data[$field])) {
                    $data[$field] = in_array($data[$field], ['1', 1, true, 'yes', 'on'], true);
                }
            }
            
            // Convert boolean-like values (new separate OS fields)
            $booleanFieldsOs = [
                'prk_mmc_0_02_percent_os', 'prk_bandage_contact_lens_os',
                'femto_bandage_contact_lens_os', 'ptk_mmc_0_02_percent_os', 'ptk_bandage_contact_lens_os',
                'mmc_0_02_percent_os',
            ];
            
            foreach ($booleanFieldsOs as $field) {
                if (isset($data[$field])) {
                    $data[$field] = in_array($data[$field], ['1', 1, true, 'yes', 'on'], true);
                }
            }
            
            // Handle nullable boolean fields (old shared fields)
            $nullableBooleanFields = ['femto_flap_done', 'smile_complete_lenticule_separation', 'smile_complete_lenticule_extraction'];
            foreach ($nullableBooleanFields as $field) {
                if ($data[$field] === '' || $data[$field] === null) {
                    $data[$field] = null;
                } else {
                    $data[$field] = in_array($data[$field], ['1', 1, true, 'yes', 'on'], true);
                }
            }
            
            // Handle nullable boolean fields (new separate OD fields)
            $nullableBooleanFieldsOd = [
                'femto_flap_done_od', 
                'smile_complete_lenticule_separation_od', 
                'smile_complete_lenticule_extraction_od'
            ];
            foreach ($nullableBooleanFieldsOd as $field) {
                if (isset($data[$field])) {
                    if ($data[$field] === '' || $data[$field] === null) {
                        $data[$field] = null;
                    } else {
                        $data[$field] = in_array($data[$field], ['1', 1, true, 'yes', 'on'], true);
                    }
                }
            }
            
            // Handle nullable boolean fields (new separate OS fields)
            $nullableBooleanFieldsOs = [
                'femto_flap_done_os', 
                'smile_complete_lenticule_separation_os', 
                'smile_complete_lenticule_extraction_os'
            ];
            foreach ($nullableBooleanFieldsOs as $field) {
                if (isset($data[$field])) {
                    if ($data[$field] === '' || $data[$field] === null) {
                        $data[$field] = null;
                    } else {
                        $data[$field] = in_array($data[$field], ['1', 1, true, 'yes', 'on'], true);
                    }
                }
            }
            
            // Handle monovision_eye - convert empty to null
            if (empty($data['monovision_eye']) || $data['monovision_eye'] === '') {
                $data['monovision_eye'] = null;
            }
            
            // Convert mmc_duration_sec fields to integer or null
            if (isset($data['mmc_duration_sec_od'])) {
                if ($data['mmc_duration_sec_od'] === '' || $data['mmc_duration_sec_od'] === null) {
                    $data['mmc_duration_sec_od'] = null;
                } else {
                    $data['mmc_duration_sec_od'] = (int) $data['mmc_duration_sec_od'];
                }
            }
            if (isset($data['mmc_duration_sec_os'])) {
                if ($data['mmc_duration_sec_os'] === '' || $data['mmc_duration_sec_os'] === null) {
                    $data['mmc_duration_sec_os'] = null;
                } else {
                    $data['mmc_duration_sec_os'] = (int) $data['mmc_duration_sec_os'];
                }
            }
            
            // Clear empty strings for nullable fields (old shared fields)
            $nullableFields = [
                'eye_drops_other_details', 'prk_epithelial_removal', 'prk_excimer_profile',
                'femto_excimer_profile', 'ptk_epithelial_removal', 'ptk_excimer_profile', 'notes',
                'operation_type_od', 'operation_type_os'
            ];
            
            foreach ($nullableFields as $field) {
                if (isset($data[$field]) && $data[$field] === '') {
                    $data[$field] = null;
                }
            }
            
            // Clear empty strings for nullable fields (new separate Eye Drops fields)
            $eyeDropsNullableFields = [
                'eye_drops_other_details_od', 'eye_drops_other_details_os',
            ];
            
            foreach ($eyeDropsNullableFields as $field) {
                if (isset($data[$field]) && $data[$field] === '') {
                    $data[$field] = null;
                }
            }
            
            // Clear empty strings for nullable fields (new separate OD/OS fields)
            $nullableFieldsSeparate = [
                'prk_epithelial_removal_od', 'prk_excimer_profile_od',
                'prk_epithelial_removal_os', 'prk_excimer_profile_os',
                'femto_excimer_profile_od', 'femto_excimer_profile_os',
                'ptk_epithelial_removal_od', 'ptk_excimer_profile_od',
                'ptk_epithelial_removal_os', 'ptk_excimer_profile_os',
                // Target fields
                'prk_target_od', 'prk_target_os',
                'femto_target_od', 'femto_target_os',
                'smile_target_od', 'smile_target_os',
                'ptk_target_od', 'ptk_target_os',
                // General MMC duration fields
                'mmc_duration_sec_od', 'mmc_duration_sec_os',
            ];
            foreach ($nullableFieldsSeparate as $field) {
                if (isset($data[$field]) && $data[$field] === '') {
                    $data[$field] = null;
                }
            }
            
            // Handle operation_type based on operation_eye and same_operation_type_both_eyes
            if ($operationEye === 'OU' && $this->form['same_operation_type_both_eyes']) {
                // If same type for both eyes, copy OD fields to OS fields and to old shared fields
                // Only copy if OS fields are empty to prevent overwriting user-entered data
                if (!empty($data['operation_type_od'])) {
                    $operationType = $data['operation_type_od'];
                    $data['operation_type_os'] = $operationType; // Copy operation type
                    
                    // Copy general MMC fields (applies to all operation types)
                    // Only copy if OS fields are empty
                    if (empty($data['mmc_0_02_percent_os'])) {
                        $data['mmc_0_02_percent_os'] = $data['mmc_0_02_percent_od'] ?? false;
                    }
                    if (empty($data['mmc_duration_sec_os'])) {
                        $data['mmc_duration_sec_os'] = $data['mmc_duration_sec_od'] ?? null;
                    }
                    
                    // Copy Eye Drops from OD to OS (only if OS fields are empty)
                    if (empty($data['eye_drops_vigamox_os'])) {
                        $data['eye_drops_vigamox_os'] = $data['eye_drops_vigamox_od'] ?? false;
                    }
                    if (empty($data['eye_drops_pred_forte_os'])) {
                        $data['eye_drops_pred_forte_os'] = $data['eye_drops_pred_forte_od'] ?? false;
                    }
                    if (empty($data['eye_drops_other_os'])) {
                        $data['eye_drops_other_os'] = $data['eye_drops_other_od'] ?? false;
                    }
                    if (empty($data['eye_drops_other_details_os'])) {
                        $data['eye_drops_other_details_os'] = $data['eye_drops_other_details_od'] ?? null;
                    }
                    // Also copy to old shared fields for backward compatibility
                    $data['eye_drops_vigamox'] = $data['eye_drops_vigamox_od'] ?? false;
                    $data['eye_drops_pred_forte'] = $data['eye_drops_pred_forte_od'] ?? false;
                    $data['eye_drops_other'] = $data['eye_drops_other_od'] ?? false;
                    $data['eye_drops_other_details'] = $data['eye_drops_other_details_od'] ?? null;
                    
                    // Copy OD fields to OS fields - only if OS fields are empty
                    if ($operationType === 'PRK') {
                        if (empty($data['prk_epithelial_removal_os'])) {
                            $data['prk_epithelial_removal_os'] = $data['prk_epithelial_removal_od'] ?? null;
                        }
                        if (empty($data['prk_excimer_profile_os'])) {
                            $data['prk_excimer_profile_os'] = $data['prk_excimer_profile_od'] ?? null;
                        }
                        if (empty($data['prk_mmc_0_02_percent_os'])) {
                            $data['prk_mmc_0_02_percent_os'] = $data['prk_mmc_0_02_percent_od'] ?? false;
                        }
                        if (empty($data['prk_bandage_contact_lens_os'])) {
                            $data['prk_bandage_contact_lens_os'] = $data['prk_bandage_contact_lens_od'] ?? false;
                        }
                        if (empty($data['prk_target_os'])) {
                            $data['prk_target_os'] = $data['prk_target_od'] ?? null;
                        }
                        // Also copy to old shared fields
                        $data['prk_epithelial_removal'] = $data['prk_epithelial_removal_od'] ?? null;
                        $data['prk_excimer_profile'] = $data['prk_excimer_profile_od'] ?? null;
                        $data['prk_mmc_0_02_percent'] = $data['prk_mmc_0_02_percent_od'] ?? false;
                        $data['prk_bandage_contact_lens'] = $data['prk_bandage_contact_lens_od'] ?? false;
                    } elseif ($operationType === 'Femto-LASIK') {
                        if (empty($data['femto_flap_done_os'])) {
                            $data['femto_flap_done_os'] = $data['femto_flap_done_od'] ?? null;
                        }
                        if (empty($data['femto_excimer_profile_os'])) {
                            $data['femto_excimer_profile_os'] = $data['femto_excimer_profile_od'] ?? null;
                        }
                        if (empty($data['femto_bandage_contact_lens_os'])) {
                            $data['femto_bandage_contact_lens_os'] = $data['femto_bandage_contact_lens_od'] ?? false;
                        }
                        if (empty($data['femto_target_os'])) {
                            $data['femto_target_os'] = $data['femto_target_od'] ?? null;
                        }
                        // Also copy to old shared fields
                        $data['femto_flap_done'] = $data['femto_flap_done_od'] ?? null;
                        $data['femto_excimer_profile'] = $data['femto_excimer_profile_od'] ?? null;
                        $data['femto_bandage_contact_lens'] = $data['femto_bandage_contact_lens_od'] ?? false;
                    } elseif ($operationType === 'SMILE') {
                        if (empty($data['smile_complete_lenticule_separation_os'])) {
                            $data['smile_complete_lenticule_separation_os'] = $data['smile_complete_lenticule_separation_od'] ?? null;
                        }
                        if (empty($data['smile_complete_lenticule_extraction_os'])) {
                            $data['smile_complete_lenticule_extraction_os'] = $data['smile_complete_lenticule_extraction_od'] ?? null;
                        }
                        if (empty($data['smile_target_os'])) {
                            $data['smile_target_os'] = $data['smile_target_od'] ?? null;
                        }
                        // Also copy to old shared fields
                        $data['smile_complete_lenticule_separation'] = $data['smile_complete_lenticule_separation_od'] ?? null;
                        $data['smile_complete_lenticule_extraction'] = $data['smile_complete_lenticule_extraction_od'] ?? null;
                    } elseif ($operationType === 'PTK') {
                        if (empty($data['ptk_epithelial_removal_os'])) {
                            $data['ptk_epithelial_removal_os'] = $data['ptk_epithelial_removal_od'] ?? null;
                        }
                        if (empty($data['ptk_excimer_profile_os'])) {
                            $data['ptk_excimer_profile_os'] = $data['ptk_excimer_profile_od'] ?? null;
                        }
                        if (empty($data['ptk_mmc_0_02_percent_os'])) {
                            $data['ptk_mmc_0_02_percent_os'] = $data['ptk_mmc_0_02_percent_od'] ?? false;
                        }
                        if (empty($data['ptk_bandage_contact_lens_os'])) {
                            $data['ptk_bandage_contact_lens_os'] = $data['ptk_bandage_contact_lens_od'] ?? false;
                        }
                        if (empty($data['ptk_target_os'])) {
                            $data['ptk_target_os'] = $data['ptk_target_od'] ?? null;
                        }
                        // Also copy to old shared fields
                        $data['ptk_epithelial_removal'] = $data['ptk_epithelial_removal_od'] ?? null;
                        $data['ptk_excimer_profile'] = $data['ptk_excimer_profile_od'] ?? null;
                        $data['ptk_mmc_0_02_percent'] = $data['ptk_mmc_0_02_percent_od'] ?? false;
                        $data['ptk_bandage_contact_lens'] = $data['ptk_bandage_contact_lens_od'] ?? false;
                    }
                    
                    // Do NOT copy notes field - it should remain as a single shared field
                    // Notes field is not duplicated between OD and OS
                }
            } elseif ($operationEye === 'OD') {
                // For single eye (OD): copy OD fields to old shared fields
                if (!empty($data['operation_type_od'])) {
                    $operationType = $data['operation_type_od'];
                    
                    if ($operationType === 'PRK') {
                        $data['prk_epithelial_removal'] = $data['prk_epithelial_removal_od'] ?? null;
                        $data['prk_excimer_profile'] = $data['prk_excimer_profile_od'] ?? null;
                        $data['prk_mmc_0_02_percent'] = $data['prk_mmc_0_02_percent_od'] ?? false;
                        $data['prk_bandage_contact_lens'] = $data['prk_bandage_contact_lens_od'] ?? false;
                    } elseif ($operationType === 'Femto-LASIK') {
                        $data['femto_flap_done'] = $data['femto_flap_done_od'] ?? null;
                        $data['femto_excimer_profile'] = $data['femto_excimer_profile_od'] ?? null;
                        $data['femto_bandage_contact_lens'] = $data['femto_bandage_contact_lens_od'] ?? false;
                    } elseif ($operationType === 'SMILE') {
                        $data['smile_complete_lenticule_separation'] = $data['smile_complete_lenticule_separation_od'] ?? null;
                        $data['smile_complete_lenticule_extraction'] = $data['smile_complete_lenticule_extraction_od'] ?? null;
                    } elseif ($operationType === 'PTK') {
                        $data['ptk_epithelial_removal'] = $data['ptk_epithelial_removal_od'] ?? null;
                        $data['ptk_excimer_profile'] = $data['ptk_excimer_profile_od'] ?? null;
                        $data['ptk_mmc_0_02_percent'] = $data['ptk_mmc_0_02_percent_od'] ?? false;
                        $data['ptk_bandage_contact_lens'] = $data['ptk_bandage_contact_lens_od'] ?? false;
                    }
                }
            } elseif ($operationEye === 'OS') {
                // For single eye (OS): copy OS fields to old shared fields
                if (!empty($data['operation_type_os'])) {
                    $operationType = $data['operation_type_os'];
                    
                    if ($operationType === 'PRK') {
                        $data['prk_epithelial_removal'] = $data['prk_epithelial_removal_os'] ?? null;
                        $data['prk_excimer_profile'] = $data['prk_excimer_profile_os'] ?? null;
                        $data['prk_mmc_0_02_percent'] = $data['prk_mmc_0_02_percent_os'] ?? false;
                        $data['prk_bandage_contact_lens'] = $data['prk_bandage_contact_lens_os'] ?? false;
                    } elseif ($operationType === 'Femto-LASIK') {
                        $data['femto_flap_done'] = $data['femto_flap_done_os'] ?? null;
                        $data['femto_excimer_profile'] = $data['femto_excimer_profile_os'] ?? null;
                        $data['femto_bandage_contact_lens'] = $data['femto_bandage_contact_lens_os'] ?? false;
                    } elseif ($operationType === 'SMILE') {
                        $data['smile_complete_lenticule_separation'] = $data['smile_complete_lenticule_separation_os'] ?? null;
                        $data['smile_complete_lenticule_extraction'] = $data['smile_complete_lenticule_extraction_os'] ?? null;
                    } elseif ($operationType === 'PTK') {
                        $data['ptk_epithelial_removal'] = $data['ptk_epithelial_removal_os'] ?? null;
                        $data['ptk_excimer_profile'] = $data['ptk_excimer_profile_os'] ?? null;
                        $data['ptk_mmc_0_02_percent'] = $data['ptk_mmc_0_02_percent_os'] ?? false;
                        $data['ptk_bandage_contact_lens'] = $data['ptk_bandage_contact_lens_os'] ?? false;
                    }
                }
            }

            if ($this->editingId) {
                // Updating existing operation note
                $operationNote = OperationNote::findOrFail($this->editingId);
                $operationNote->update($data);
                session()->flash('message', 'Operation note updated successfully.');
                
                // Reload the data to keep editing mode
                $this->edit($this->editingId);
            } else {
                // Creating new operation note
                $newOperationNote = OperationNote::create($data);
                session()->flash('message', 'Operation note created successfully.');
                
                // Set editingId and reload the data to switch to edit mode
                $this->editingId = $newOperationNote->id;
                $this->edit($this->editingId);
            }

            // Update visit_stage to 'completed' when operation note is saved
            if ($data['appointment_id'] ?? null) {
                try {
                    $appointment = \App\Models\Appointment::find($data['appointment_id']);
                    if ($appointment && !in_array($appointment->visit_stage, ['cancelled'])) {
                        $appointment->update(['visit_stage' => 'completed']);
                    }
                } catch (\Exception $e) {
                    // Log error but don't prevent save
                    \Log::error('OperationNoteManager save - visit_stage update error: ' . $e->getMessage());
                }
            }

            $this->dispatch('operation-note-saved');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save operation note: ' . $e->getMessage());
        }
    }

    public function edit($id): void
    {
        $operationNote = OperationNote::findOrFail($id);
        $this->editingId = $id;
        
        $this->form = [
            'appointment_id' => $operationNote->appointment_id,
            'patient_id' => $operationNote->patient_id,
            'doctor_id' => $operationNote->doctor_id,
            'operation_type' => $operationNote->operation_type ?? '',
            'operation_type_od' => $operationNote->operation_type_od ?? '',
            'operation_type_os' => $operationNote->operation_type_os ?? '',
            'operation_eye' => $operationNote->operation_eye ?? 'OU',
            'monovision_eye' => $operationNote->monovision_eye ?? '',
            'same_operation_type_both_eyes' => $operationNote->same_operation_type_both_eyes ?? false,
            
            // Load old shared fields if new separate fields are empty (for backward compatibility)
            'prk_epithelial_removal' => $operationNote->prk_epithelial_removal_od ?? $operationNote->prk_epithelial_removal ?? '',
            'prk_excimer_profile' => $operationNote->prk_excimer_profile_od ?? $operationNote->prk_excimer_profile ?? '',
            'prk_mmc_0_02_percent' => $operationNote->prk_mmc_0_02_percent_od ?? $operationNote->prk_mmc_0_02_percent ?? false,
            'prk_bandage_contact_lens' => $operationNote->prk_bandage_contact_lens_od ?? $operationNote->prk_bandage_contact_lens ?? false,
            'femto_flap_done' => $operationNote->femto_flap_done_od ?? $operationNote->femto_flap_done,
            'femto_excimer_profile' => $operationNote->femto_excimer_profile_od ?? $operationNote->femto_excimer_profile ?? '',
            'femto_bandage_contact_lens' => $operationNote->femto_bandage_contact_lens_od ?? $operationNote->femto_bandage_contact_lens ?? false,
            'smile_complete_lenticule_separation' => $operationNote->smile_complete_lenticule_separation_od ?? $operationNote->smile_complete_lenticule_separation,
            'smile_complete_lenticule_extraction' => $operationNote->smile_complete_lenticule_extraction_od ?? $operationNote->smile_complete_lenticule_extraction,
            'ptk_epithelial_removal' => $operationNote->ptk_epithelial_removal_od ?? $operationNote->ptk_epithelial_removal ?? '',
            'ptk_excimer_profile' => $operationNote->ptk_excimer_profile_od ?? $operationNote->ptk_excimer_profile ?? '',
            'ptk_mmc_0_02_percent' => $operationNote->ptk_mmc_0_02_percent_od ?? $operationNote->ptk_mmc_0_02_percent ?? false,
            'ptk_bandage_contact_lens' => $operationNote->ptk_bandage_contact_lens_od ?? $operationNote->ptk_bandage_contact_lens ?? false,
            'eye_drops_vigamox' => $operationNote->eye_drops_vigamox ?? false,
            'eye_drops_pred_forte' => $operationNote->eye_drops_pred_forte ?? false,
            'eye_drops_other' => $operationNote->eye_drops_other ?? false,
            'eye_drops_other_details' => $operationNote->eye_drops_other_details ?? '',
            // New separate Eye Drops fields for OD and OS
            'eye_drops_vigamox_od' => $operationNote->eye_drops_vigamox_od ?? $operationNote->eye_drops_vigamox ?? false,
            'eye_drops_vigamox_os' => $operationNote->eye_drops_vigamox_os ?? $operationNote->eye_drops_vigamox ?? false,
            'eye_drops_pred_forte_od' => $operationNote->eye_drops_pred_forte_od ?? $operationNote->eye_drops_pred_forte ?? false,
            'eye_drops_pred_forte_os' => $operationNote->eye_drops_pred_forte_os ?? $operationNote->eye_drops_pred_forte ?? false,
            'eye_drops_other_od' => $operationNote->eye_drops_other_od ?? $operationNote->eye_drops_other ?? false,
            'eye_drops_other_os' => $operationNote->eye_drops_other_os ?? $operationNote->eye_drops_other ?? false,
            'eye_drops_other_details_od' => $operationNote->eye_drops_other_details_od ?? $operationNote->eye_drops_other_details ?? '',
            'eye_drops_other_details_os' => $operationNote->eye_drops_other_details_os ?? $operationNote->eye_drops_other_details ?? '',
            // New separate fields for OD
            'prk_epithelial_removal_od' => $operationNote->prk_epithelial_removal_od ?? '',
            'prk_excimer_profile_od' => $operationNote->prk_excimer_profile_od ?? '',
            'prk_mmc_0_02_percent_od' => $operationNote->prk_mmc_0_02_percent_od ?? false,
            'prk_bandage_contact_lens_od' => $operationNote->prk_bandage_contact_lens_od ?? false,
            'femto_flap_done_od' => $operationNote->femto_flap_done_od,
            'femto_excimer_profile_od' => $operationNote->femto_excimer_profile_od ?? '',
            'femto_bandage_contact_lens_od' => $operationNote->femto_bandage_contact_lens_od ?? false,
            'smile_complete_lenticule_separation_od' => $operationNote->smile_complete_lenticule_separation_od,
            'smile_complete_lenticule_extraction_od' => $operationNote->smile_complete_lenticule_extraction_od,
            'ptk_epithelial_removal_od' => $operationNote->ptk_epithelial_removal_od ?? '',
            'ptk_excimer_profile_od' => $operationNote->ptk_excimer_profile_od ?? '',
            'ptk_mmc_0_02_percent_od' => $operationNote->ptk_mmc_0_02_percent_od ?? false,
            'ptk_bandage_contact_lens_od' => $operationNote->ptk_bandage_contact_lens_od ?? false,
            // New separate fields for OS
            'prk_epithelial_removal_os' => $operationNote->prk_epithelial_removal_os ?? '',
            'prk_excimer_profile_os' => $operationNote->prk_excimer_profile_os ?? '',
            'prk_mmc_0_02_percent_os' => $operationNote->prk_mmc_0_02_percent_os ?? false,
            'prk_bandage_contact_lens_os' => $operationNote->prk_bandage_contact_lens_os ?? false,
            'femto_flap_done_os' => $operationNote->femto_flap_done_os,
            'femto_excimer_profile_os' => $operationNote->femto_excimer_profile_os ?? '',
            'femto_bandage_contact_lens_os' => $operationNote->femto_bandage_contact_lens_os ?? false,
            'smile_complete_lenticule_separation_os' => $operationNote->smile_complete_lenticule_separation_os,
            'smile_complete_lenticule_extraction_os' => $operationNote->smile_complete_lenticule_extraction_os,
            'ptk_epithelial_removal_os' => $operationNote->ptk_epithelial_removal_os ?? '',
            'ptk_excimer_profile_os' => $operationNote->ptk_excimer_profile_os ?? '',
            'ptk_mmc_0_02_percent_os' => $operationNote->ptk_mmc_0_02_percent_os ?? false,
            'ptk_bandage_contact_lens_os' => $operationNote->ptk_bandage_contact_lens_os ?? false,
            // Target fields for each operation type
            'prk_target_od' => $operationNote->prk_target_od ?? '',
            'prk_target_os' => $operationNote->prk_target_os ?? '',
            'femto_target_od' => $operationNote->femto_target_od ?? '',
            'femto_target_os' => $operationNote->femto_target_os ?? '',
            'smile_target_od' => $operationNote->smile_target_od ?? '',
            'smile_target_os' => $operationNote->smile_target_os ?? '',
            'ptk_target_od' => $operationNote->ptk_target_od ?? '',
            'ptk_target_os' => $operationNote->ptk_target_os ?? '',
            // General MMC fields for all operation types
            'mmc_0_02_percent_od' => $operationNote->mmc_0_02_percent_od ?? false,
            'mmc_duration_sec_od' => $operationNote->mmc_duration_sec_od ?? '',
            'mmc_0_02_percent_os' => $operationNote->mmc_0_02_percent_os ?? false,
            'mmc_duration_sec_os' => $operationNote->mmc_duration_sec_os ?? '',
            'notes' => $operationNote->notes ?? '',
        ];
        
        $this->showModal = true;
    }

    public function delete($id): void
    {
        try {
            OperationNote::findOrFail($id)->delete();
            session()->flash('message', 'Operation note deleted successfully.');
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete operation note: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $appointment = null;
        $operationNote = null;
        
        if ($this->appointmentId) {
            $appointment = Appointment::with(['patient', 'doctor'])->find($this->appointmentId);
            $operationNote = OperationNote::where('appointment_id', $this->appointmentId)->first();
        }

        return view('livewire.operation-note-manager', [
            'appointment' => $appointment,
            'operationNote' => $operationNote,
        ]);
    }
}
