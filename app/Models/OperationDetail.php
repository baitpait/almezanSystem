<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'operation_id',
        'femto_lasik',
        'prk_mmc',
        'prk_type',
        'trans_prk',
        'ptk',
        'topography_guided',
        'excimer_profile',
        'mmc_concentration',
        'mmc_duration_seconds',
        'bll',
        'drops_used',
        'target_refraction_od',
        'target_refraction_os',
        'target_nomogram_od',
        'target_pach_od',
        'target_kaapa_od',
        'target_vertex_od',
        'target_wtw_od',
        'target_procedure_od',
        'target_pta_od',
        'target_pupil_size_od',
        'target_nomogram_os',
        'target_pach_os',
        'target_kaapa_os',
        'target_vertex_os',
        'target_wtw_os',
        'target_procedure_os',
        'target_pta_os',
        'target_pupil_size_os',
        'target_add',
        'mv_eye',
        'has_complications',
        'complications_details',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'femto_lasik' => 'boolean',
        'prk_mmc' => 'boolean',
        'trans_prk' => 'boolean',
        'ptk' => 'boolean',
        'topography_guided' => 'boolean',
        'mmc_concentration' => 'decimal:3',
        'mmc_duration_seconds' => 'integer',
        'bll' => 'boolean',
        'has_complications' => 'boolean',
        'target_pach_od' => 'integer',
        'target_vertex_od' => 'decimal:2',
        'target_wtw_od' => 'decimal:2',
        'target_pta_od' => 'decimal:2',
        'target_pupil_size_od' => 'decimal:2',
        'target_pach_os' => 'integer',
        'target_vertex_os' => 'decimal:2',
        'target_wtw_os' => 'decimal:2',
        'target_pta_os' => 'decimal:2',
        'target_pupil_size_os' => 'decimal:2',
    ];

    /**
     * Get the operation that owns the operation details.
     */
    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }
}
