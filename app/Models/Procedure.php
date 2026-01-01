<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procedure extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'default_duration',
    ];

    /**
     * Get the appointments for the procedure.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
