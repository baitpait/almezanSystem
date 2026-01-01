<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'branch_id',
        'name',
        'photo',
        'email',
        'phone',
        'specialization',
        'notify_via_sms',
        'notify_via_email',
        'follow_up',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'notify_via_sms' => 'boolean',
        'notify_via_email' => 'boolean',
        'follow_up' => 'boolean',
    ];

    /**
     * Get the user account for the doctor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the branch that the doctor belongs to.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the appointments for the doctor.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the invoices for the doctor.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
