<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefractiveProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'operation_id',
        'patient_name',
        'patient_age',
        'optometrist',
        'eyeglasses_age',
        'time_with_current_rx',
        'current_eyeglasses_od_sphere',
        'current_eyeglasses_od_cylinder',
        'current_eyeglasses_od_axis',
        'current_eyeglasses_od_vision',
        'current_eyeglasses_os_sphere',
        'current_eyeglasses_os_cylinder',
        'current_eyeglasses_os_axis',
        'current_eyeglasses_os_vision',
        'dry_auto_ref_od_sphere',
        'dry_auto_ref_od_cylinder',
        'dry_auto_ref_od_axis',
        'dry_auto_ref_os_sphere',
        'dry_auto_ref_os_cylinder',
        'dry_auto_ref_os_axis',
        'contact_lenses',
        'time_without_lenses',
        'manifest_refraction_od_udva',
        'manifest_refraction_od_sphere',
        'manifest_refraction_od_cylinder',
        'manifest_refraction_od_axis',
        'manifest_refraction_od_bscva',
        'manifest_refraction_od_dcnva_40cm',
        'manifest_refraction_od_add_j1',
        'manifest_refraction_os_udva',
        'manifest_refraction_os_sphere',
        'manifest_refraction_os_cylinder',
        'manifest_refraction_os_axis',
        'manifest_refraction_os_bscva',
        'manifest_refraction_os_dcnva_40cm',
        'manifest_refraction_os_add_j1',
        'refraction_after_dilation_od_sphere',
        'refraction_after_dilation_od_cylinder',
        'refraction_after_dilation_od_axis',
        'refraction_after_dilation_od_vision',
        'refraction_after_dilation_os_sphere',
        'refraction_after_dilation_os_cylinder',
        'refraction_after_dilation_os_axis',
        'refraction_after_dilation_os_vision',
        'refraction_after_dilation_type',
        'pupil_diameter_od_mesopic',
        'pupil_diameter_od_scotopic',
        'pupil_diameter_os_mesopic',
        'pupil_diameter_os_scotopic',
        'dominant_eye',
        'simulation_for_monovision',
        'manifest_refraction_od_rg',
        'manifest_refraction_os_rg',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'patient_age' => 'integer',
    ];

    /**
     * Get the operation that owns the refractive profile.
     */
    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }
}
