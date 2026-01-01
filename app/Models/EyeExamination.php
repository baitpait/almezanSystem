<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EyeExamination extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'operation_id',
        'examination_type',
        'od_iop',
        'od_lids',
        'od_conjunctiva',
        'od_cornea',
        'od_tbut',
        'od_schirmer',
        'od_anterior_chamber',
        'od_iris_pupil',
        'od_lens',
        'od_vitreous',
        'od_optic_disc',
        'od_retina',
        'od_macula',
        'od_vessels',
        'od_fom',
        'od_findings',
        'os_iop',
        'os_lids',
        'os_conjunctiva',
        'os_cornea',
        'os_tbut',
        'os_schirmer',
        'os_anterior_chamber',
        'os_iris_pupil',
        'os_lens',
        'os_vitreous',
        'os_optic_disc',
        'os_retina',
        'os_macula',
        'os_vessels',
        'os_fom',
        'os_findings',
        'unaided_od',
        'unaided_os',
        'manifest_refraction_od',
        'manifest_refraction_os',
        'cyclo_refraction_od',
        'cyclo_refraction_os',
        'notes',
    ];

    /**
     * Get the operation that owns the eye examination.
     */
    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }
}
