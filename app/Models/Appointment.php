<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'created_by',
        'branch_id',
        'patient_id',
        'doctor_id',
        'procedure_id',
        'appointment_date',
        'appointment_time',
        'duration',
        'notes',
        'notify_patient_sms',
        'notify_doctor_sms',
        'notify_doctor_email',
        'follow_up',
        'status',
        'visit_stage',
        'visit_type',
        'operation_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_date' => 'date',
        'notify_patient_sms' => 'boolean',
        'notify_doctor_sms' => 'boolean',
        'notify_doctor_email' => 'boolean',
        'follow_up' => 'boolean',
    ];

    /**
     * Get the appointment time formatted.
     */
    public function getFormattedTimeAttribute(): string
    {
        return \Carbon\Carbon::createFromFormat('H:i:s', $this->appointment_time)->format('h:i A');
    }

    /**
     * Get the patient that owns the appointment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor that owns the appointment.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }


    /**
     * Get the procedure that owns the appointment.
     */
    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    /**
     * Get the user who created the appointment.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the branch that the appointment belongs to.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the invoices for the appointment.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the operation that the appointment belongs to.
     */
    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }

    /**
     * Get the operation notes for the appointment.
     */
    public function operationNotes(): HasMany
    {
        return $this->hasMany(OperationNote::class);
    }
}
