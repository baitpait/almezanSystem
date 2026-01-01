<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EctasiaRiskAssessment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'operation_id',
        'pta_percentage_od',
        'pta_percentage_os',
        'rsb_od',
        'rsb_os',
        'tomography_normal_pattern',
        'tomography_status',
        'tomography_other',
        'pachymetry_thinnest_od',
        'pachymetry_thinnest_os',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tomography_normal_pattern' => 'boolean',
    ];

    /**
     * Get the operation that owns the ectasia risk assessment.
     */
    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }
}
