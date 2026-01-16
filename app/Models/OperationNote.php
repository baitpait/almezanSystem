<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationNote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'operation_type',
        'operation_type_od',
        'operation_type_os',
        'operation_eye',
        'same_operation_type_both_eyes',
        'monovision_eye',
        'eye_drops_vigamox',
        'eye_drops_pred_forte',
        'eye_drops_other',
        'eye_drops_other_details',
        // New separate Eye Drops fields for OD and OS
        'eye_drops_vigamox_od',
        'eye_drops_vigamox_os',
        'eye_drops_pred_forte_od',
        'eye_drops_pred_forte_os',
        'eye_drops_other_od',
        'eye_drops_other_os',
        'eye_drops_other_details_od',
        'eye_drops_other_details_os',
        // Old shared fields (kept for backward compatibility)
        'prk_epithelial_removal',
        'prk_excimer_profile',
        'prk_mmc_0_02_percent',
        'prk_bandage_contact_lens',
        'femto_flap_done',
        'femto_excimer_profile',
        'femto_bandage_contact_lens',
        'smile_complete_lenticule_separation',
        'smile_complete_lenticule_extraction',
        'ptk_epithelial_removal',
        'ptk_excimer_profile',
        'ptk_mmc_0_02_percent',
        'ptk_bandage_contact_lens',
        // New separate fields for OD
        'prk_epithelial_removal_od',
        'prk_excimer_profile_od',
        'prk_mmc_0_02_percent_od',
        'prk_bandage_contact_lens_od',
        'femto_flap_done_od',
        'femto_excimer_profile_od',
        'femto_bandage_contact_lens_od',
        'smile_complete_lenticule_separation_od',
        'smile_complete_lenticule_extraction_od',
        'ptk_epithelial_removal_od',
        'ptk_excimer_profile_od',
        'ptk_mmc_0_02_percent_od',
        'ptk_bandage_contact_lens_od',
        // New separate fields for OS
        'prk_epithelial_removal_os',
        'prk_excimer_profile_os',
        'prk_mmc_0_02_percent_os',
        'prk_bandage_contact_lens_os',
        'femto_flap_done_os',
        'femto_excimer_profile_os',
        'femto_bandage_contact_lens_os',
        'smile_complete_lenticule_separation_os',
        'smile_complete_lenticule_extraction_os',
        'ptk_epithelial_removal_os',
        'ptk_excimer_profile_os',
        'ptk_mmc_0_02_percent_os',
        'ptk_bandage_contact_lens_os',
        // Target fields for each operation type
        'prk_target_od',
        'prk_target_os',
        'femto_target_od',
        'femto_target_os',
        'smile_target_od',
        'smile_target_os',
        'ptk_target_od',
        'ptk_target_os',
        // General MMC fields for all operation types
        'mmc_0_02_percent_od',
        'mmc_duration_sec_od',
        'mmc_0_02_percent_os',
        'mmc_duration_sec_os',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'eye_drops_vigamox' => 'boolean',
        'eye_drops_pred_forte' => 'boolean',
        'eye_drops_other' => 'boolean',
        // New separate Eye Drops fields
        'eye_drops_vigamox_od' => 'boolean',
        'eye_drops_vigamox_os' => 'boolean',
        'eye_drops_pred_forte_od' => 'boolean',
        'eye_drops_pred_forte_os' => 'boolean',
        'eye_drops_other_od' => 'boolean',
        'eye_drops_other_os' => 'boolean',
        // Old shared fields
        'prk_mmc_0_02_percent' => 'boolean',
        'prk_bandage_contact_lens' => 'boolean',
        // Note: femto_flap_done, smile_complete_lenticule_separation, smile_complete_lenticule_extraction are nullable boolean
        // They should NOT be cast as 'boolean' because Laravel converts null to false
        // We handle them manually in the save() and edit() methods
        'femto_bandage_contact_lens' => 'boolean',
        'ptk_mmc_0_02_percent' => 'boolean',
        'ptk_bandage_contact_lens' => 'boolean',
        // New separate OD fields
        'prk_mmc_0_02_percent_od' => 'boolean',
        'prk_bandage_contact_lens_od' => 'boolean',
        // Note: femto_flap_done_od, smile_complete_lenticule_separation_od, smile_complete_lenticule_extraction_od are nullable boolean
        // They should NOT be cast as 'boolean' because Laravel converts null to false
        // We handle them manually in the save() and edit() methods
        'femto_bandage_contact_lens_od' => 'boolean',
        'ptk_mmc_0_02_percent_od' => 'boolean',
        'ptk_bandage_contact_lens_od' => 'boolean',
        // New separate OS fields
        'prk_mmc_0_02_percent_os' => 'boolean',
        'prk_bandage_contact_lens_os' => 'boolean',
        // Note: femto_flap_done_os, smile_complete_lenticule_separation_os, smile_complete_lenticule_extraction_os are nullable boolean
        // They should NOT be cast as 'boolean' because Laravel converts null to false
        // We handle them manually in the save() and edit() methods
        'femto_bandage_contact_lens_os' => 'boolean',
        'ptk_mmc_0_02_percent_os' => 'boolean',
        'ptk_bandage_contact_lens_os' => 'boolean',
        // General MMC fields
        'mmc_0_02_percent_od' => 'boolean',
        'mmc_0_02_percent_os' => 'boolean',
        'same_operation_type_both_eyes' => 'boolean',
    ];

    /**
     * Get the appointment that owns the operation note.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the patient that owns the operation note.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor that owns the operation note.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
