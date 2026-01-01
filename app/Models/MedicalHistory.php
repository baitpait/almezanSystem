<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'operation_id',
        'diabetes',
        'chronic_disease',
        'chronic_disease_details',
        'herpes_keratitis',
        'glaucoma',
        'family_history_keratoconus',
        'eye_rubber',
        'pregnancy',
        'ocular_surgery',
        'ocular_surgery_details',
        'family_history_ocular_disease_yes',
        'family_history_ocular_disease',
        'current_medications_yes',
        'current_medications',
        'allergies',
        'glare_halos_squint',
        'refraction_stable_1year',
        'contact_lens_use',
        'past_medical_history',
        'past_ophthalmic_history',
        'chief_complaint',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'diabetes' => 'boolean',
        'chronic_disease' => 'boolean',
        'herpes_keratitis' => 'boolean',
        'glaucoma' => 'boolean',
        'family_history_keratoconus' => 'boolean',
        'eye_rubber' => 'boolean',
        'pregnancy' => 'boolean',
        'ocular_surgery' => 'boolean',
        'family_history_ocular_disease_yes' => 'boolean',
        'current_medications_yes' => 'boolean',
        'glare_halos_squint' => 'boolean',
        'refraction_stable_1year' => 'boolean',
        'contact_lens_use' => 'boolean',
    ];

    /**
     * Get the operation that owns the medical history.
     */
    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }
}
