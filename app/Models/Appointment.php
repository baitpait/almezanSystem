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
     * Calculate visit_stage based on appointment_date.
     * 
     * Business Purpose: Determine the appropriate visit_stage automatically
     * based on whether the appointment is in the past, today, or future.
     * 
     * @param mixed $appointmentDate The appointment date (Carbon, string, or date)
     * @return string The calculated visit_stage
     */
    public static function calculateVisitStage($appointmentDate): string
    {
        if (!$appointmentDate) {
            return 'scheduled';
        }

        $date = \Carbon\Carbon::parse($appointmentDate);
        $today = \Carbon\Carbon::today();

        // Use isBefore with startOfDay to avoid time comparison issues
        if ($date->isBefore($today->startOfDay())) {
            // Past appointments (before today)
            return 'completed';
        } elseif ($date->isToday()) {
            // Today's appointments
            return 'waiting';
        } else {
            // Future appointments
            return 'scheduled';
        }
    }

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
        if (!$this->appointment_time) {
            return '';
        }
        
        try {
            // Try H:i:s format first (07:24:00)
            return \Carbon\Carbon::createFromFormat('H:i:s', $this->appointment_time)->format('h:i A');
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            // Fallback to H:i format (07:24)
            try {
                return \Carbon\Carbon::createFromFormat('H:i', $this->appointment_time)->format('h:i A');
            } catch (\Carbon\Exceptions\InvalidFormatException $e2) {
                // If both fail, try parsing as time string
                return \Carbon\Carbon::parse($this->appointment_time)->format('h:i A');
            }
        }
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
     * Scope appointments visible to staff of a branch: same branch or legacy rows with no branch set.
     * Prevents doctors/secretaries from missing visits created before branch_id was always filled.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeForBranchAccess($query, int $branchId)
    {
        return $query->where(function ($q) use ($branchId) {
            $q->where('branch_id', $branchId)
                ->orWhereNull('branch_id');
        });
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
