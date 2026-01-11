<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operation extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'branch_id',
        'appointment_id',
        'created_by',
        'start_date',
        'end_date',
        'status',
        'pre_op_assessment_date',
        'post_op_notes',
        'recommendation_notes',
        'decision',
        'decision_od',
        'decision_os',
        // Old shared fields (kept for backward compatibility)
        'prk_epithelial_removal',
        'prk_excimer_profile',
        'prk_monovision_eye',
        'prk_target',
        'femto_excimer_profile',
        'femto_monovision_eye',
        'femto_target',
        'smile_monovision_eye',
        'smile_target',
        'ptk_epithelial_removal',
        'ptk_excimer_profile',
        'ptk_monovision_eye',
        'ptk_target',
        'incompatible_notes',
        // New separate fields for OD
        'prk_epithelial_removal_od',
        'prk_excimer_profile_od',
        'prk_monovision_eye_od',
        'prk_target_od',
        'femto_excimer_profile_od',
        'femto_monovision_eye_od',
        'femto_target_od',
        'smile_monovision_eye_od',
        'smile_target_od',
        'ptk_epithelial_removal_od',
        'ptk_excimer_profile_od',
        'ptk_monovision_eye_od',
        'ptk_target_od',
        'incompatible_notes_od',
        // New separate fields for OS
        'prk_epithelial_removal_os',
        'prk_excimer_profile_os',
        'prk_monovision_eye_os',
        'prk_target_os',
        'femto_excimer_profile_os',
        'femto_monovision_eye_os',
        'femto_target_os',
        'smile_monovision_eye_os',
        'smile_target_os',
        'ptk_epithelial_removal_os',
        'ptk_excimer_profile_os',
        'ptk_monovision_eye_os',
        'ptk_target_os',
        'incompatible_notes_os',
        'diagnosis',
        'plan',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cost' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'pre_op_assessment_date' => 'date',
        'appointment_id' => 'integer',
    ];

    /**
     * Get the patient that owns the operation.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor that owns the operation.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the branch that the operation belongs to.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who created the operation.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the refractive profile for the operation.
     */
    public function refractiveProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(RefractiveProfile::class);
    }

    /**
     * Get the medical history for the operation.
     */
    public function medicalHistory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MedicalHistory::class);
    }

    /**
     * Get the eye examinations for the operation.
     */
    public function eyeExaminations(): HasMany
    {
        return $this->hasMany(EyeExamination::class);
    }

    /**
     * Get the ectasia risk assessment for the operation.
     */
    public function ectasiaRiskAssessment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(EctasiaRiskAssessment::class);
    }

    /**
     * Get the operation details.
     */
    public function operationDetails(): HasMany
    {
        return $this->hasMany(OperationDetail::class);
    }

    /**
     * Get the operation approvals.
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(OperationApproval::class);
    }

    /**
     * Get the appointments for the operation.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the invoices for the operation.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the files for the operation.
     */
    public function files(): HasMany
    {
        return $this->hasMany(OperationFile::class);
    }

    /**
     * Get the appointment that the operation belongs to.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Check if operation has any data stored (not empty).
     * Used to prevent accidental deletion of operations with data.
     *
     * @return bool
     */
    public function hasData(): bool
    {
        // Check if operation has any related data
        $hasRefractiveProfile = $this->refractiveProfile()->exists();
        $hasMedicalHistory = $this->medicalHistory()->exists();
        $hasEyeExaminations = $this->eyeExaminations()->exists();
        $hasEctasiaRisk = $this->ectasiaRiskAssessment()->exists();
        $hasOperationDetails = $this->operationDetails()->exists();
        $hasApprovals = $this->approvals()->exists();
        $hasInvoices = $this->invoices()->exists();
        $hasFiles = $this->files()->exists();
        
        // Check if operation has any notes or important fields filled
        $hasNotes = !empty($this->post_op_notes) || 
                   !empty($this->recommendation_notes) || 
                   !empty($this->notes) ||
                   !empty($this->diagnosis) ||
                   !empty($this->plan);
        
        // Check if operation has decision made
        $hasDecision = !empty($this->decision) || 
                      !empty($this->decision_od) || 
                      !empty($this->decision_os);
        
        // Check if operation has any operation type set (not just default)
        $hasOperationType = !empty($this->operation_type) && 
                           $this->operation_type !== 'Femto-LASIK'; // Default value
        
        // Check if cost is set (not zero)
        $hasCost = $this->cost > 0;
        
        // Check if dates are set
        $hasDates = !empty($this->start_date) || !empty($this->end_date) || !empty($this->pre_op_assessment_date);
        
        return $hasRefractiveProfile || 
               $hasMedicalHistory || 
               $hasEyeExaminations || 
               $hasEctasiaRisk || 
               $hasOperationDetails || 
               $hasApprovals || 
               $hasInvoices || 
               $hasFiles || 
               $hasNotes || 
               $hasDecision || 
               $hasOperationType || 
               $hasCost || 
               $hasDates;
    }

    /**
     * Check if operation is empty (no data stored).
     * Opposite of hasData().
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return !$this->hasData();
    }
}
