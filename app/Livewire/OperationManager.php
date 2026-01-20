<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Operation;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Branch;
use App\Models\Appointment;
use App\Models\RefractiveProfile;
use App\Models\MedicalHistory;
use App\Models\EyeExamination;
use App\Models\EctasiaRiskAssessment;
use App\Models\OperationDetail;
use App\Models\OperationFile;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class OperationManager extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public string $statusFilter = '';
    public string $dateFilter = 'today'; // upcoming, today, past, all
    public int $perPage = 10;
    public $editingId = null;
    public $showModal = false;
    public $patientSearch = '';
    public $selectedPatientId = null;
    public $activeTab = 'basic'; // basic, refractive, medical, exam, ectasia, recommendation, files
    public $isCreatePage = false;
    public $isEditPage = false;

    // Files Management
    public $newFile = null;
    public $newFileDescription = '';
    public $newFileEye = 'OU';
    public $operationFiles = null;
    
    // Planning section visibility flags
    public bool $showPlanningOd = false;
    public bool $showPlanningOs = false;
    public bool $showPlanningBoth = false;

    // Basic Operation Form
    public array $operationForm = [
        'patient_id' => null,
        'doctor_id' => null,
        'branch_id' => null,
        'appointment_id' => null,
        'start_date' => '',
        'status' => 'scheduled',
        'pre_op_assessment_date' => '',
    ];

    // Refractive Profile Form
    public array $refractiveForm = [
        'optometrist' => '',
        'eyeglasses_age' => '',
        'time_with_current_rx' => '',
        'contact_lenses' => 'No',
        'time_without_lenses' => '',
        'dominant_eye' => '',
        'simulation_for_monovision' => '',
        // Current Eyeglasses OD
        'current_eyeglasses_od_sphere' => '',
        'current_eyeglasses_od_cylinder' => '',
        'current_eyeglasses_od_axis' => '',
        'current_eyeglasses_od_vision' => '',
        // Current Eyeglasses OS
        'current_eyeglasses_os_sphere' => '',
        'current_eyeglasses_os_cylinder' => '',
        'current_eyeglasses_os_axis' => '',
        'current_eyeglasses_os_vision' => '',
        // Manifest Refraction OD
        'manifest_refraction_od_udva' => '',
        'manifest_refraction_od_sphere' => '',
        'manifest_refraction_od_cylinder' => '',
        'manifest_refraction_od_axis' => '',
        'manifest_refraction_od_bscva' => '',
        'manifest_refraction_od_rg' => '',
        'manifest_refraction_od_dcnva_40cm' => '',
        'manifest_refraction_od_add_j1' => '',
        // Manifest Refraction OS
        'manifest_refraction_os_udva' => '',
        'manifest_refraction_os_sphere' => '',
        'manifest_refraction_os_cylinder' => '',
        'manifest_refraction_os_axis' => '',
        'manifest_refraction_os_bscva' => '',
        'manifest_refraction_os_rg' => '',
        'manifest_refraction_os_dcnva_40cm' => '',
        'manifest_refraction_os_add_j1' => '',
        // Refraction After Dilatation OD
        'refraction_after_dilation_od_sphere' => '',
        'refraction_after_dilation_od_cylinder' => '',
        'refraction_after_dilation_od_axis' => '',
        'refraction_after_dilation_od_vision' => '',
        // Refraction After Dilatation OS
        'refraction_after_dilation_os_sphere' => '',
        'refraction_after_dilation_os_cylinder' => '',
        'refraction_after_dilation_os_axis' => '',
        'refraction_after_dilation_os_vision' => '',
        // Refraction After Dilatation Type
        'refraction_after_dilation_type' => '',
        // Pupil Diameter OD
        'pupil_diameter_od_mesopic' => '',
        'pupil_diameter_od_scotopic' => '',
        // Pupil Diameter OS
        'pupil_diameter_os_mesopic' => '',
        'pupil_diameter_os_scotopic' => '',
    ];

    // Medical History Form
    public array $medicalForm = [
        'diabetes' => '',
        'chronic_disease' => '',
        'herpes_keratitis' => '',
        'glaucoma' => '',
        'family_history_keratoconus' => '',
        'eye_rubber' => '',
        'pregnancy' => '',
        'ocular_surgery' => '0', // Default to No
        'ocular_surgery_details' => '',
        'family_history_ocular_disease_yes' => '0', // Default to No
        'family_history_ocular_disease' => '',
        'current_medications_yes' => '0', // Default to No
        'current_medications' => '',
        'glare_halos_squint' => '',
        'refraction_stable_1year' => '1', // Default to Yes
        'contact_lens_use' => '',
    ];

    // Eye Examination Form (with default values)
    public array $examForm = [
        'examination_type' => 'pre_op',
        // OD (Right Eye) - Default values
        'od_iop' => '', // No default value
        'od_lids' => 'Normal',
        'od_conjunctiva' => 'Normal',
        'od_cornea' => 'Clear',
        'od_tbut' => '', // No default value
        'od_schirmer' => '', // No default value
        'od_anterior_chamber' => 'Deep and quiet',
        'od_iris_pupil' => 'Normal',
        'od_lens' => 'Clear',
        'od_vitreous' => 'Clear',
        'od_optic_disc' => 'Normal',
        'od_retina' => 'Normal',
        'od_macula' => 'Normal',
        // OS (Left Eye) - Default values
        'os_iop' => '', // No default value
        'os_lids' => 'Normal',
        'os_conjunctiva' => 'Normal',
        'os_cornea' => 'Clear',
        'os_tbut' => '', // No default value
        'os_schirmer' => '', // No default value
        'os_anterior_chamber' => 'Deep and quiet',
        'os_iris_pupil' => 'Normal',
        'os_lens' => 'Clear',
        'os_vitreous' => 'Clear',
        'os_optic_disc' => 'Normal',
        'os_retina' => 'Normal',
        'os_macula' => 'Normal',
    ];

    // Ectasia Risk Assessment Form (with default values)
    public array $ectasiaForm = [
        'pta_percentage_od' => '',
        'pta_percentage_os' => '',
        'rsb_od' => '',
        'rsb_os' => '',
        'tomography_normal_pattern' => true,
        'tomography_status' => 'normal',
        'tomography_other' => '',
        'pachymetry_thinnest_od' => '550', // Normal: 540-560 microns, default 550
        'pachymetry_thinnest_os' => '550', // Normal: 540-560 microns, default 550
    ];

    // Recommendation Form
    public array $recommendationForm = [
        'decision' => '', // General decision (for backward compatibility and single eye operations)
        'decision_od' => '', // Decision for Right Eye (OD)
        'decision_os' => '', // Decision for Left Eye (OS)
        'same_decision_both_eyes' => false, // Checkbox: Same decision for both eyes
        // PRK - Old shared fields (for backward compatibility)
        'prk_epithelial_removal' => '',
        'prk_excimer_profile' => '',
        'prk_monovision_eye' => '',
        'prk_target' => '',
        // PRK - Separate fields for OD
        'prk_epithelial_removal_od' => '',
        'prk_excimer_profile_od' => '',
        'prk_monovision_eye_od' => '',
        'prk_target_od' => '',
        // PRK - Separate fields for OS
        'prk_epithelial_removal_os' => '',
        'prk_excimer_profile_os' => '',
        'prk_monovision_eye_os' => '',
        'prk_target_os' => '',
        // Femto Lasik - Old shared fields
        'femto_excimer_profile' => '',
        'femto_monovision_eye' => '',
        'femto_target' => '',
        // Femto Lasik - Separate fields for OD
        'femto_excimer_profile_od' => '',
        'femto_monovision_eye_od' => '',
        'femto_target_od' => '',
        // Femto Lasik - Separate fields for OS
        'femto_excimer_profile_os' => '',
        'femto_monovision_eye_os' => '',
        'femto_target_os' => '',
        // Smile - Old shared fields
        'smile_monovision_eye' => '',
        'smile_target' => '',
        // Smile - Separate fields for OD
        'smile_monovision_eye_od' => '',
        'smile_target_od' => '',
        // Smile - Separate fields for OS
        'smile_monovision_eye_os' => '',
        'smile_target_os' => '',
        // PTK - Old shared fields
        'ptk_epithelial_removal' => '',
        'ptk_excimer_profile' => '',
        'ptk_monovision_eye' => '',
        'ptk_target' => '',
        // PTK - Separate fields for OD
        'ptk_epithelial_removal_od' => '',
        'ptk_excimer_profile_od' => '',
        'ptk_monovision_eye_od' => '',
        'ptk_target_od' => '',
        // PTK - Separate fields for OS
        'ptk_epithelial_removal_os' => '',
        'ptk_excimer_profile_os' => '',
        'ptk_monovision_eye_os' => '',
        'ptk_target_os' => '',
        // Incompatible - Old shared field
        'incompatible_notes' => '',
        // Incompatible - Separate fields
        'incompatible_notes_od' => '',
        'incompatible_notes_os' => '',
        // Planning fields for each eye
        'planning_sphere_od' => '',
        'planning_cylinder_od' => '',
        'planning_axis_od' => '',
        'planning_sphere_os' => '',
        'planning_cylinder_os' => '',
        'planning_axis_os' => '',
        // Shared / existing
        'recommendation_notes' => '',
    ];


    public function updatingSearch(): void
    {
        try {
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('OperationManager updatingSearch error: ' . $e->getMessage());
        }
    }

    public function updatingPerPage(): void
    {
        try {
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('OperationManager updatingPerPage error: ' . $e->getMessage());
        }
    }

    public function updatingStatusFilter(): void
    {
        try {
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('OperationManager updatingStatusFilter error: ' . $e->getMessage());
        }
    }

    public function updatingDateFilter(): void
    {
        try {
            $this->resetPage();
        } catch (\Exception $e) {
            \Log::error('OperationManager updatingDateFilter error: ' . $e->getMessage());
        }
    }

    public function mount($id = null): void
    {
        // Initialize properties safely
        $this->search = $this->search ?? '';
        $this->statusFilter = $this->statusFilter ?? '';
        $this->dateFilter = $this->dateFilter ?? 'today';
        $this->perPage = $this->perPage ?? 10;
        
        // Check if we're on create or edit page
        $route = request()->route()?->getName();
        if ($route === 'operations.create') {
            $this->isCreatePage = true;
            $this->create();
            
            // If patient_id is passed as query parameter, pre-select the patient
            $patientId = request()->query('patient_id');
            if ($patientId) {
                $this->selectPatient($patientId);
            }

            // If appointment_id is passed, link it and auto-select patient and doctor if present
            $appointmentId = request()->query('appointment_id');
            if ($appointmentId) {
                $appointment = Appointment::find($appointmentId);
                if ($appointment) {
                    $this->operationForm['appointment_id'] = $appointmentId;
                    // Auto-fill doctor_id from appointment
                    if ($appointment->doctor_id) {
                        $this->operationForm['doctor_id'] = $appointment->doctor_id;
                    }
                    // Auto-select patient if not already selected
                    if (!$this->operationForm['patient_id'] && $appointment->patient_id) {
                        $this->selectPatient($appointment->patient_id);
                    }
                }
            }
        } elseif ($route === 'operations.edit' && $id) {
            $this->isEditPage = true;
            // Check for tab in URL query parameter before calling edit()
            $tabFromUrl = request()->query('tab');
            if ($tabFromUrl && in_array($tabFromUrl, ['basic', 'refractive', 'medical', 'exam', 'ectasia', 'recommendation', 'files'])) {
                $this->activeTab = $tabFromUrl;
            }
            $this->edit($id);
            
            // If appointment_id is passed as query parameter, ensure it's linked
            $appointmentId = request()->query('appointment_id');
            if ($appointmentId) {
                $appointment = Appointment::find($appointmentId);
                if ($appointment) {
                    $this->operationForm['appointment_id'] = $appointmentId;
                    // Auto-fill doctor_id from appointment if not set
                    if (!$this->operationForm['doctor_id'] && $appointment->doctor_id) {
                        $this->operationForm['doctor_id'] = $appointment->doctor_id;
                    }
                    
                    // Update visit_stage to 'in_consultation' when doctor opens the file
                    // Only update if not already completed or cancelled
                    if (!in_array($appointment->visit_stage, ['completed', 'cancelled'])) {
                        $appointment->update(['visit_stage' => 'in_consultation']);
                    }
                }
            } else {
                // If no appointment_id in query, try to get it from the operation
                $operation = Operation::with('appointment')->find($id);
                if ($operation && $operation->appointment_id) {
                    $appointment = Appointment::find($operation->appointment_id);
                    if ($appointment && !in_array($appointment->visit_stage, ['completed', 'cancelled'])) {
                        $appointment->update(['visit_stage' => 'in_consultation']);
                    }
                }
            }
        } else {
            // Load files if editing
            if ($this->editingId) {
                $this->loadOperationFiles();
            }
        }
    }

    public function updatingPatientSearch(): void
    {
        $this->selectedPatientId = null;
    }

    public function selectPatient($patientId): void
    {
        $patient = Patient::findOrFail($patientId);
        $this->selectedPatientId = $patientId;
        $this->operationForm['patient_id'] = $patientId;
        $this->patientSearch = $patient->full_name;
    }

    public function setTab($tab): void
    {
        $this->activeTab = $tab;
        // Load files when switching to files tab
        if ($tab === 'files' && $this->editingId) {
            $this->loadOperationFiles();
        }
    }

    public function resetAllForms(): void
    {
        $this->operationForm = [
            'patient_id' => null,
            'doctor_id' => null,
            'branch_id' => auth()->user()?->branch_id,
            'appointment_id' => null,
            'start_date' => now()->format('Y-m-d'),
            'status' => 'scheduled',
            'pre_op_assessment_date' => now()->format('Y-m-d'),
        ];

        $this->refractiveForm = array_fill_keys(array_keys($this->refractiveForm), '');
        $this->refractiveForm['contact_lenses'] = 'No';

        $this->medicalForm = array_fill_keys(array_keys($this->medicalForm), false);
        $this->medicalForm['refraction_stable_1year'] = '1'; // Default to Yes for radio button
        $this->medicalForm['ocular_surgery_details'] = '';
        $this->medicalForm['family_history_ocular_disease'] = '';
        $this->medicalForm['current_medications'] = '';
        // Convert all boolean fields to empty string for radio buttons (No selected by default)
        $booleanFields = [
            'diabetes', 'chronic_disease', 'herpes_keratitis', 'glaucoma',
            'family_history_keratoconus', 'eye_rubber', 'pregnancy',
            'glare_halos_squint', 'contact_lens_use'
        ];
        foreach ($booleanFields as $field) {
            $this->medicalForm[$field] = '';
        }
        // Set default to "0" (No) for these three fields
        $this->medicalForm['ocular_surgery'] = '0';
        $this->medicalForm['family_history_ocular_disease_yes'] = '0';
        $this->medicalForm['current_medications_yes'] = '0';

        // Reset exam form with defaults
        $this->examForm = [
            'examination_type' => 'pre_op',
            'od_iop' => '', // No default value
            'od_lids' => 'Normal',
            'od_conjunctiva' => 'Normal',
            'od_cornea' => 'Clear',
            'od_tbut' => '', // No default value
            'od_schirmer' => '', // No default value
            'od_anterior_chamber' => 'Deep and quiet',
            'od_iris_pupil' => 'Normal',
            'od_lens' => 'Clear',
            'od_vitreous' => 'Clear',
            'od_optic_disc' => 'Normal',
            'od_retina' => 'Normal',
            'od_macula' => 'Normal',
            'os_iop' => '', // No default value
            'os_lids' => 'Normal',
            'os_conjunctiva' => 'Normal',
            'os_cornea' => 'Clear',
            'os_tbut' => '', // No default value
            'os_schirmer' => '', // No default value
            'os_anterior_chamber' => 'Deep and quiet',
            'os_iris_pupil' => 'Normal',
            'os_lens' => 'Clear',
            'os_vitreous' => 'Clear',
            'os_optic_disc' => 'Normal',
            'os_retina' => 'Normal',
            'os_macula' => 'Normal',
        ];

        // Reset ectasia form with defaults
        $this->ectasiaForm = [
            'pta_percentage_od' => '',
            'pta_percentage_os' => '',
            'rsb_od' => '',
            'rsb_os' => '',
            'tomography_normal_pattern' => true,
            'tomography_status' => 'normal',
            'tomography_other' => '',
            'pachymetry_thinnest_od' => '550',
            'pachymetry_thinnest_os' => '550',
        ];

        $this->recommendationForm = array_fill_keys(array_keys($this->recommendationForm), '');
        // Reset decision fields
        $this->recommendationForm['decision'] = '';
        $this->recommendationForm['decision_od'] = '';
        $this->recommendationForm['decision_os'] = '';


        $this->selectedPatientId = null;
        $this->patientSearch = '';
        if (!$this->isCreatePage && !$this->isEditPage) {
            $this->editingId = null;
            $this->showModal = false;
        }
        $this->activeTab = 'basic';
    }

    /**
     * When decision changes, clear non-relevant fields to ensure UI toggles cleanly.
     */
    public function updatedRecommendationFormDecision($value): void
    {
        $this->recommendationForm['decision'] = $value;
    }


    /**
     * When decision_od changes, sync to OS if same_decision_both_eyes is checked.
     */
    public function updatedRecommendationFormDecisionOd($value): void
    {
        $this->recommendationForm['decision_od'] = $value;
        
        // If same_decision_both_eyes is checked, sync OS decision
        if ($this->recommendationForm['same_decision_both_eyes']) {
            $this->recommendationForm['decision_os'] = $value;
        }
    }

    /**
     * When decision_os changes, clear non-relevant fields.
     */
    public function updatedRecommendationFormDecisionOs($value): void
    {
        $this->recommendationForm['decision_os'] = $value;
        // If same_decision_both_eyes is checked, uncheck it when OS decision changes manually
        if ($this->recommendationForm['same_decision_both_eyes'] && $value !== $this->recommendationForm['decision_od']) {
            $this->recommendationForm['same_decision_both_eyes'] = false;
        }
    }

    /**
     * When same_decision_both_eyes checkbox changes.
     */
    public function updatedRecommendationFormSameDecisionBothEyes($value): void
    {
        if ($value) {
            // Copy OD decision to OS
            if (!empty($this->recommendationForm['decision_od'])) {
                $this->recommendationForm['decision_os'] = $this->recommendationForm['decision_od'];
            }
        }
    }

    /**
     * Get Refraction values from RefractiveProfile and populate Planning fields.
     * 
     * Business Purpose: When user clicks "Get Refraction" button, fetch manifest_refraction values
     * from the RefractiveProfile (either from form or database) and populate the Planning fields 
     * (Sphere, Cylinder, Axis) for the specified eye. This allows users to use the manifest 
     * refraction values as a starting point for operation planning.
     * 
     * @param string $eye The eye to get refraction for ('od', 'os', or 'both')
     */
    public function getRefraction(string $eye = 'od'): void
    {
        try {
            $manifestSphereOd = '';
            $manifestCylinderOd = '';
            $manifestAxisOd = '';
            $manifestSphereOs = '';
            $manifestCylinderOs = '';
            $manifestAxisOs = '';

            // First, try to get from refractiveForm (current form data)
            if (!empty($this->refractiveForm['manifest_refraction_od_sphere']) || 
                !empty($this->refractiveForm['manifest_refraction_od_cylinder']) || 
                !empty($this->refractiveForm['manifest_refraction_od_axis'])) {
                $manifestSphereOd = $this->refractiveForm['manifest_refraction_od_sphere'] ?? '';
                $manifestCylinderOd = $this->refractiveForm['manifest_refraction_od_cylinder'] ?? '';
                $manifestAxisOd = $this->refractiveForm['manifest_refraction_od_axis'] ?? '';
            }

            if (!empty($this->refractiveForm['manifest_refraction_os_sphere']) || 
                !empty($this->refractiveForm['manifest_refraction_os_cylinder']) || 
                !empty($this->refractiveForm['manifest_refraction_os_axis'])) {
                $manifestSphereOs = $this->refractiveForm['manifest_refraction_os_sphere'] ?? '';
                $manifestCylinderOs = $this->refractiveForm['manifest_refraction_os_cylinder'] ?? '';
                $manifestAxisOs = $this->refractiveForm['manifest_refraction_os_axis'] ?? '';
            }

            // If form data is empty, try to get from database
            if (empty($manifestSphereOd) && empty($manifestCylinderOd) && empty($manifestAxisOd) && 
                empty($manifestSphereOs) && empty($manifestCylinderOs) && empty($manifestAxisOs)) {
                if ($this->editingId) {
                    $operation = Operation::with('refractiveProfile')->find($this->editingId);
                    if ($operation && $operation->refractiveProfile) {
                        $refractiveProfile = $operation->refractiveProfile;
                        $manifestSphereOd = $refractiveProfile->manifest_refraction_od_sphere ?? '';
                        $manifestCylinderOd = $refractiveProfile->manifest_refraction_od_cylinder ?? '';
                        $manifestAxisOd = $refractiveProfile->manifest_refraction_od_axis ?? '';
                        $manifestSphereOs = $refractiveProfile->manifest_refraction_os_sphere ?? '';
                        $manifestCylinderOs = $refractiveProfile->manifest_refraction_os_cylinder ?? '';
                        $manifestAxisOs = $refractiveProfile->manifest_refraction_os_axis ?? '';
                    }
                }
            }

            // Check if we have any values
            if (empty($manifestSphereOd) && empty($manifestCylinderOd) && empty($manifestAxisOd) && 
                empty($manifestSphereOs) && empty($manifestCylinderOs) && empty($manifestAxisOs)) {
                session()->flash('error', 'No manifest refraction values found. Please complete the Refractive Profile tab first.');
                return;
            }

            // Get values based on eye parameter and show planning section
            if ($eye === 'od' || $eye === 'both') {
                $this->recommendationForm['planning_sphere_od'] = $manifestSphereOd;
                $this->recommendationForm['planning_cylinder_od'] = $manifestCylinderOd;
                $this->recommendationForm['planning_axis_od'] = $manifestAxisOd;
                $this->showPlanningOd = true;
            }

            if ($eye === 'os' || $eye === 'both') {
                $this->recommendationForm['planning_sphere_os'] = $manifestSphereOs;
                $this->recommendationForm['planning_cylinder_os'] = $manifestCylinderOs;
                $this->recommendationForm['planning_axis_os'] = $manifestAxisOs;
                $this->showPlanningOs = true;
            }

            if ($eye === 'both') {
                $this->showPlanningBoth = true;
            }

            session()->flash('message', 'Refraction values loaded successfully.');
        } catch (\Exception $e) {
            \Log::error('OperationManager getRefraction error: ' . $e->getMessage());
            session()->flash('error', 'Failed to load refraction values: ' . $e->getMessage());
        }
    }

    /**
     * Watch for changes in PRK fields and sync to OS if same_decision_both_eyes is checked.
     */
    public function updatedRecommendationFormPrkEpithelialRemoval($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            ($this->recommendationForm['decision_od'] === 'prk' || $this->recommendationForm['decision_od'] === 'ptk')) {
            // Field is already shared, so it's automatically synced
            // This method ensures Livewire reactivity
        }
    }

    public function updatedRecommendationFormPrkExcimerProfile($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            ($this->recommendationForm['decision_od'] === 'prk' || $this->recommendationForm['decision_od'] === 'ptk')) {
            // Field is already shared
        }
    }

    public function updatedRecommendationFormPrkMonovisionEye($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            ($this->recommendationForm['decision_od'] === 'prk' || $this->recommendationForm['decision_od'] === 'ptk')) {
            // Field is already shared
        }
    }

    public function updatedRecommendationFormPrkTarget($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            ($this->recommendationForm['decision_od'] === 'prk' || $this->recommendationForm['decision_od'] === 'ptk')) {
            // Field is already shared
        }
    }

    public function updatedRecommendationFormFemtoExcimerProfile($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            $this->recommendationForm['decision_od'] === 'femto_lasik') {
            // Field is already shared
        }
    }

    public function updatedRecommendationFormFemtoMonovisionEye($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            $this->recommendationForm['decision_od'] === 'femto_lasik') {
            // Field is already shared
        }
    }

    public function updatedRecommendationFormFemtoTarget($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            $this->recommendationForm['decision_od'] === 'femto_lasik') {
            // Field is already shared
        }
    }

    public function updatedRecommendationFormSmileMonovisionEye($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            $this->recommendationForm['decision_od'] === 'smile') {
            // Field is already shared
        }
    }

    public function updatedRecommendationFormSmileTarget($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            $this->recommendationForm['decision_od'] === 'smile') {
            // Field is already shared
        }
    }

    public function updatedRecommendationFormPtkEpithelialRemoval($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            $this->recommendationForm['decision_od'] === 'ptk') {
            // Field is already shared
        }
    }

    public function updatedRecommendationFormPtkExcimerProfile($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            $this->recommendationForm['decision_od'] === 'ptk') {
            // Field is already shared
        }
    }

    public function updatedRecommendationFormPtkMonovisionEye($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            $this->recommendationForm['decision_od'] === 'ptk') {
            // Field is already shared
        }
    }

    public function updatedRecommendationFormPtkTarget($value): void
    {
        if ($this->recommendationForm['same_decision_both_eyes'] && 
            $this->recommendationForm['decision_od'] === $this->recommendationForm['decision_os'] &&
            $this->recommendationForm['decision_od'] === 'ptk') {
            // Field is already shared
        }
    }

    public function updatedRecommendationFormIncompatibleNotes($value): void
    {
        // Do NOT automatically copy incompatible_notes to OD/OS
        // User should enter data separately for each eye
        // Only copy if same_decision_both_eyes is explicitly checked AND user is using shared field
    }

    public function updatedRecommendationFormIncompatibleNotesOd($value): void
    {
        // Do NOT automatically copy to OS or to shared field
        // Each eye should have its own notes
    }

    public function updatedRecommendationFormIncompatibleNotesOs($value): void
    {
        // Do NOT automatically copy to OD or to shared field
        // Each eye should have its own notes
    }

    /**
     * Sync all decision-specific fields from OD to OS when same_decision_both_eyes is checked.
     */
    private function syncFieldsFromOdToOs(): void
    {
        if (!$this->recommendationForm['same_decision_both_eyes']) {
            return;
        }

        $decisionOd = $this->recommendationForm['decision_od'] ?? '';
        
        // Only sync if both eyes have the same decision
        if ($decisionOd === $this->recommendationForm['decision_os']) {
            // Sync PRK fields
            if ($decisionOd === 'prk') {
                $this->recommendationForm['prk_epithelial_removal'] = $this->recommendationForm['prk_epithelial_removal'] ?? '';
                $this->recommendationForm['prk_excimer_profile'] = $this->recommendationForm['prk_excimer_profile'] ?? '';
                $this->recommendationForm['prk_monovision_eye'] = $this->recommendationForm['prk_monovision_eye'] ?? '';
                $this->recommendationForm['prk_target'] = $this->recommendationForm['prk_target'] ?? '';
            }
            // Sync Femto fields
            elseif ($decisionOd === 'femto_lasik') {
                $this->recommendationForm['femto_excimer_profile'] = $this->recommendationForm['femto_excimer_profile'] ?? '';
                $this->recommendationForm['femto_monovision_eye'] = $this->recommendationForm['femto_monovision_eye'] ?? '';
                $this->recommendationForm['femto_target'] = $this->recommendationForm['femto_target'] ?? '';
            }
            // Sync Smile fields
            elseif ($decisionOd === 'smile') {
                $this->recommendationForm['smile_monovision_eye'] = $this->recommendationForm['smile_monovision_eye'] ?? '';
                $this->recommendationForm['smile_target'] = $this->recommendationForm['smile_target'] ?? '';
            }
            // Sync PTK fields
            elseif ($decisionOd === 'ptk') {
                $this->recommendationForm['ptk_epithelial_removal'] = $this->recommendationForm['ptk_epithelial_removal'] ?? '';
                $this->recommendationForm['ptk_excimer_profile'] = $this->recommendationForm['ptk_excimer_profile'] ?? '';
                $this->recommendationForm['ptk_monovision_eye'] = $this->recommendationForm['ptk_monovision_eye'] ?? '';
                $this->recommendationForm['ptk_target'] = $this->recommendationForm['ptk_target'] ?? '';
            }
            // Sync Incompatible fields
            elseif ($decisionOd === 'incompatible') {
                $this->recommendationForm['incompatible_notes'] = $this->recommendationForm['incompatible_notes'] ?? '';
            }
        }
    }

    /**
     * Watch for changes in recommendation form fields.
     * Note: Since we now use separate fields for OD/OS, this method is mainly for validation/logging.
     */
    public function updatedRecommendationForm($propertyName, $value): void
    {
        // Ensure propertyName is a string before processing
        if (!is_string($propertyName)) {
            return;
        }

        // Extract field name from property name (e.g., "recommendationForm.prk_epithelial_removal_od" -> "prk_epithelial_removal_od")
        $fieldName = str_replace('recommendationForm.', '', $propertyName);
        
        // This method can be used for future enhancements like validation or logging
        // Since fields are now separate for OD/OS, no syncing is needed here
    }

    /**
     * Clear all decision-specific fields.
     */
    private function clearDecisionFields(): void
    {
        // Clear all decision-specific fields (old shared fields)
        $this->recommendationForm['prk_epithelial_removal'] = '';
        $this->recommendationForm['prk_excimer_profile'] = '';
        $this->recommendationForm['prk_monovision_eye'] = '';
        $this->recommendationForm['prk_target'] = '';

        $this->recommendationForm['femto_excimer_profile'] = '';
        $this->recommendationForm['femto_monovision_eye'] = '';
        $this->recommendationForm['femto_target'] = '';

        $this->recommendationForm['smile_monovision_eye'] = '';
        $this->recommendationForm['smile_target'] = '';

        $this->recommendationForm['ptk_epithelial_removal'] = '';
        $this->recommendationForm['ptk_excimer_profile'] = '';
        $this->recommendationForm['ptk_monovision_eye'] = '';
        $this->recommendationForm['ptk_target'] = '';

        $this->recommendationForm['incompatible_notes'] = '';

        // Clear separate OD fields
        $this->recommendationForm['prk_epithelial_removal_od'] = '';
        $this->recommendationForm['prk_excimer_profile_od'] = '';
        $this->recommendationForm['prk_monovision_eye_od'] = '';
        $this->recommendationForm['prk_target_od'] = '';
        $this->recommendationForm['femto_excimer_profile_od'] = '';
        $this->recommendationForm['femto_monovision_eye_od'] = '';
        $this->recommendationForm['femto_target_od'] = '';
        $this->recommendationForm['smile_monovision_eye_od'] = '';
        $this->recommendationForm['smile_target_od'] = '';
        $this->recommendationForm['ptk_epithelial_removal_od'] = '';
        $this->recommendationForm['ptk_excimer_profile_od'] = '';
        $this->recommendationForm['ptk_monovision_eye_od'] = '';
        $this->recommendationForm['ptk_target_od'] = '';
        $this->recommendationForm['incompatible_notes_od'] = '';

        // Clear separate OS fields
        $this->recommendationForm['prk_epithelial_removal_os'] = '';
        $this->recommendationForm['prk_excimer_profile_os'] = '';
        $this->recommendationForm['prk_monovision_eye_os'] = '';
        $this->recommendationForm['prk_target_os'] = '';
        $this->recommendationForm['femto_excimer_profile_os'] = '';
        $this->recommendationForm['femto_monovision_eye_os'] = '';
        $this->recommendationForm['femto_target_os'] = '';
        $this->recommendationForm['smile_monovision_eye_os'] = '';
        $this->recommendationForm['smile_target_os'] = '';
        $this->recommendationForm['ptk_epithelial_removal_os'] = '';
        $this->recommendationForm['ptk_excimer_profile_os'] = '';
        $this->recommendationForm['ptk_monovision_eye_os'] = '';
        $this->recommendationForm['ptk_target_os'] = '';
        $this->recommendationForm['incompatible_notes_os'] = '';
    }

    /**
     * Get the effective decision for the current eye context.
     */
    private function getEffectiveDecision(string $eye = null): string
    {
        // Use eye-specific decisions
        if ($eye === 'OD') {
            return $this->recommendationForm['decision_od'] ?? $this->recommendationForm['decision'] ?? '';
        } elseif ($eye === 'OS') {
            return $this->recommendationForm['decision_os'] ?? $this->recommendationForm['decision'] ?? '';
        }
        
        // Default to general decision
        return $this->recommendationForm['decision'] ?? '';
    }

    public function create(): void
    {
        $this->resetAllForms();
        $this->showModal = true;
    }

    public function save(): void
    {
        try {
            // Auto-fill doctor_id from appointment if not set
            if (empty($this->operationForm['doctor_id']) && !empty($this->operationForm['appointment_id'])) {
                $appointment = Appointment::find($this->operationForm['appointment_id']);
                if ($appointment && $appointment->doctor_id) {
                    $this->operationForm['doctor_id'] = $appointment->doctor_id;
                }
            }

            // Validate basic operation
            $validationRules = [
                'operationForm.patient_id' => 'required|exists:patients,id',
                'operationForm.doctor_id' => 'required|exists:doctors,id',
                'operationForm.branch_id' => 'nullable|exists:branches,id',
                'operationForm.appointment_id' => 'nullable|exists:appointments,id',
                'operationForm.start_date' => 'nullable|date',
                'operationForm.status' => 'required',
            ];

            $this->validate($validationRules, [
                'operationForm.patient_id.required' => 'Please select a patient.',
                'operationForm.patient_id.exists' => 'Selected patient does not exist.',
                'operationForm.doctor_id.required' => 'Please select a doctor.',
                'operationForm.doctor_id.exists' => 'Selected doctor does not exist.',
                'operationForm.status.required' => 'Status is required.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Get custom error messages or use field names
            $errorMessages = [];
            foreach ($e->errors() as $field => $messages) {
                $errorMessages[] = $messages[0]; // Use first custom message
            }
            session()->flash('error', implode(' ', $errorMessages));
            return;
        }

        try {
            // Prepare operation data
            $operationData = $this->operationForm;
            $operationData['created_by'] = auth()->id();
            
            // Convert empty strings to null for optional fields
            foreach ($operationData as $key => $value) {
                if ($value === '') {
                    $operationData[$key] = null;
                }
            }

            if ($this->editingId) {
                $operation = Operation::findOrFail($this->editingId);
                $operation->update($operationData);
            } else {
                $operation = Operation::create($operationData);
            }

        // Save Refractive Profile - Always save if operation exists
            $refractiveData = $this->refractiveForm;
            
            // Convert all empty strings to null for database compatibility
            foreach ($refractiveData as $key => $value) {
                if ($value === '') {
                    $refractiveData[$key] = null;
                }
            }
            
            // Handle ENUM fields - convert empty to null
            if (empty($refractiveData['dominant_eye']) || $refractiveData['dominant_eye'] === '') {
                $refractiveData['dominant_eye'] = null;
            }
            if (empty($refractiveData['contact_lenses']) || $refractiveData['contact_lenses'] === '') {
                $refractiveData['contact_lenses'] = 'No'; // Default value
            }
            
            $refractiveData['operation_id'] = $operation->id;
            if ($patient = Patient::find($operation->patient_id)) {
                $refractiveData['patient_name'] = $patient->full_name;
                $refractiveData['patient_age'] = $patient->date_of_birth ? now()->diffInYears($patient->date_of_birth) : null;
            }
            RefractiveProfile::updateOrCreate(
                ['operation_id' => $operation->id],
                $refractiveData
            );


            // Save Medical History
            $medicalData = $this->medicalForm;
            // Convert string "1"/"0" from radio buttons to boolean
            $booleanFields = [
                'diabetes', 'chronic_disease', 'herpes_keratitis', 'glaucoma',
                'family_history_keratoconus', 'eye_rubber', 'pregnancy', 'ocular_surgery',
                'family_history_ocular_disease_yes', 'current_medications_yes',
                'glare_halos_squint', 'refraction_stable_1year', 'contact_lens_use'
            ];
            foreach ($booleanFields as $field) {
                if (isset($medicalData[$field])) {
                    $medicalData[$field] = $medicalData[$field] === '1' || $medicalData[$field] === 1 || $medicalData[$field] === true;
                } else {
                    $medicalData[$field] = false;
                }
            }
            // Ensure detail fields are saved even if empty
            $detailFields = [
                'ocular_surgery_details',
                'family_history_ocular_disease',
                'current_medications'
            ];
            foreach ($detailFields as $field) {
                if (!isset($medicalData[$field])) {
                    $medicalData[$field] = '';
                }
            }
            $medicalData['operation_id'] = $operation->id;
            MedicalHistory::updateOrCreate(
                ['operation_id' => $operation->id],
                $medicalData
            );

            // Save Eye Examination
            $examData = $this->examForm;
            $examData['operation_id'] = $operation->id;
            EyeExamination::updateOrCreate(
                ['operation_id' => $operation->id, 'examination_type' => 'pre_op'],
                $examData
            );

        // Save Ectasia Risk Assessment
        if (!empty(array_filter($this->ectasiaForm, fn($v) => $v !== '' && $v !== false))) {
            $ectasiaData = $this->ectasiaForm;
            
            // Convert empty strings to null for all fields (now all are text fields)
            $textFields = ['pta_percentage_od', 'pta_percentage_os', 'rsb_od', 'rsb_os', 'pachymetry_thinnest_od', 'pachymetry_thinnest_os', 'tomography_other'];
            foreach ($textFields as $field) {
                if (isset($ectasiaData[$field]) && $ectasiaData[$field] === '') {
                    $ectasiaData[$field] = null;
                }
            }
            
            $ectasiaData['operation_id'] = $operation->id;
            EctasiaRiskAssessment::updateOrCreate(
                ['operation_id' => $operation->id],
                $ectasiaData
            );
        }

            // Prepare recommendation data
            $recommendationData = $this->recommendationForm;

            // Convert all empty strings to null for database compatibility
            foreach ($recommendationData as $key => $value) {
                if ($value === '') {
                    $recommendationData[$key] = null;
                }
            }

            // Handle ENUM fields - convert empty to null (old shared fields)
            if (empty($recommendationData['prk_monovision_eye']) || $recommendationData['prk_monovision_eye'] === '') {
                $recommendationData['prk_monovision_eye'] = null;
            }
            if (empty($recommendationData['femto_monovision_eye']) || $recommendationData['femto_monovision_eye'] === '') {
                $recommendationData['femto_monovision_eye'] = null;
            }
            if (empty($recommendationData['smile_monovision_eye']) || $recommendationData['smile_monovision_eye'] === '') {
                $recommendationData['smile_monovision_eye'] = null;
            }
            if (empty($recommendationData['ptk_monovision_eye']) || $recommendationData['ptk_monovision_eye'] === '') {
                $recommendationData['ptk_monovision_eye'] = null;
            }

            // Handle ENUM fields - convert empty to null (separate OD fields)
            if (empty($recommendationData['prk_monovision_eye_od']) || $recommendationData['prk_monovision_eye_od'] === '') {
                $recommendationData['prk_monovision_eye_od'] = null;
            }
            if (empty($recommendationData['femto_monovision_eye_od']) || $recommendationData['femto_monovision_eye_od'] === '') {
                $recommendationData['femto_monovision_eye_od'] = null;
            }
            if (empty($recommendationData['smile_monovision_eye_od']) || $recommendationData['smile_monovision_eye_od'] === '') {
                $recommendationData['smile_monovision_eye_od'] = null;
            }
            if (empty($recommendationData['ptk_monovision_eye_od']) || $recommendationData['ptk_monovision_eye_od'] === '') {
                $recommendationData['ptk_monovision_eye_od'] = null;
            }

            // Handle ENUM fields - convert empty to null (separate OS fields)
            if (empty($recommendationData['prk_monovision_eye_os']) || $recommendationData['prk_monovision_eye_os'] === '') {
                $recommendationData['prk_monovision_eye_os'] = null;
            }
            if (empty($recommendationData['femto_monovision_eye_os']) || $recommendationData['femto_monovision_eye_os'] === '') {
                $recommendationData['femto_monovision_eye_os'] = null;
            }
            if (empty($recommendationData['smile_monovision_eye_os']) || $recommendationData['smile_monovision_eye_os'] === '') {
                $recommendationData['smile_monovision_eye_os'] = null;
            }
            if (empty($recommendationData['ptk_monovision_eye_os']) || $recommendationData['ptk_monovision_eye_os'] === '') {
                $recommendationData['ptk_monovision_eye_os'] = null;
            }

            // Handle decisions - check both eyes separately
            $decisionOd = $recommendationData['decision_od'] ?? $recommendationData['decision'] ?? '';
            $decisionOs = $recommendationData['decision_os'] ?? $recommendationData['decision'] ?? '';
            
            // Check if at least one eye uses each decision type
            $hasPrk = ($decisionOd === 'prk' || $decisionOs === 'prk');
            $hasFemto = ($decisionOd === 'femto_lasik' || $decisionOs === 'femto_lasik');
            $hasSmile = ($decisionOd === 'smile' || $decisionOs === 'smile');
            $hasPtk = ($decisionOd === 'ptk' || $decisionOs === 'ptk');
            $hasIncompatible = ($decisionOd === 'incompatible' || $decisionOs === 'incompatible');
            
            // Clear fields not relevant to any selected decision (old shared fields)
            if (!$hasPrk) {
                $recommendationData['prk_epithelial_removal'] = null;
                $recommendationData['prk_excimer_profile'] = null;
                $recommendationData['prk_monovision_eye'] = null;
                $recommendationData['prk_target'] = null;
                // Clear separate OD/OS fields
                $recommendationData['prk_epithelial_removal_od'] = null;
                $recommendationData['prk_excimer_profile_od'] = null;
                $recommendationData['prk_monovision_eye_od'] = null;
                $recommendationData['prk_target_od'] = null;
                $recommendationData['prk_epithelial_removal_os'] = null;
                $recommendationData['prk_excimer_profile_os'] = null;
                $recommendationData['prk_monovision_eye_os'] = null;
                $recommendationData['prk_target_os'] = null;
            }
            if (!$hasFemto) {
                $recommendationData['femto_excimer_profile'] = null;
                $recommendationData['femto_monovision_eye'] = null;
                $recommendationData['femto_target'] = null;
                // Clear separate OD/OS fields
                $recommendationData['femto_excimer_profile_od'] = null;
                $recommendationData['femto_monovision_eye_od'] = null;
                $recommendationData['femto_target_od'] = null;
                $recommendationData['femto_excimer_profile_os'] = null;
                $recommendationData['femto_monovision_eye_os'] = null;
                $recommendationData['femto_target_os'] = null;
            }
            if (!$hasSmile) {
                $recommendationData['smile_monovision_eye'] = null;
                $recommendationData['smile_target'] = null;
                // Clear separate OD/OS fields
                $recommendationData['smile_monovision_eye_od'] = null;
                $recommendationData['smile_target_od'] = null;
                $recommendationData['smile_monovision_eye_os'] = null;
                $recommendationData['smile_target_os'] = null;
            }
            if (!$hasPtk) {
                $recommendationData['ptk_epithelial_removal'] = null;
                $recommendationData['ptk_excimer_profile'] = null;
                $recommendationData['ptk_monovision_eye'] = null;
                $recommendationData['ptk_target'] = null;
                // Clear separate OD/OS fields
                $recommendationData['ptk_epithelial_removal_od'] = null;
                $recommendationData['ptk_excimer_profile_od'] = null;
                $recommendationData['ptk_monovision_eye_od'] = null;
                $recommendationData['ptk_target_od'] = null;
                $recommendationData['ptk_epithelial_removal_os'] = null;
                $recommendationData['ptk_excimer_profile_os'] = null;
                $recommendationData['ptk_monovision_eye_os'] = null;
                $recommendationData['ptk_target_os'] = null;
            }
            if (!$hasIncompatible) {
                $recommendationData['incompatible_notes'] = null;
                // Clear separate OD/OS fields
                $recommendationData['incompatible_notes_od'] = null;
                $recommendationData['incompatible_notes_os'] = null;
            }
            
            // If decision_od and decision_os are set, sync decision for backward compatibility
            if (!empty($recommendationData['decision_od']) && !empty($recommendationData['decision_os'])) {
                // If both eyes have the same decision, set general decision
                if ($recommendationData['decision_od'] === $recommendationData['decision_os']) {
                    $recommendationData['decision'] = $recommendationData['decision_od'];
                    
                    // If same_decision_both_eyes is checked, copy shared fields to both OD and OS fields
                    if ($this->recommendationForm['same_decision_both_eyes']) {
                        $decision = $recommendationData['decision_od'];
                        
                        // Copy PRK fields
                        if ($decision === 'prk') {
                            $recommendationData['prk_epithelial_removal_od'] = $recommendationData['prk_epithelial_removal'] ?? null;
                            $recommendationData['prk_epithelial_removal_os'] = $recommendationData['prk_epithelial_removal'] ?? null;
                            $recommendationData['prk_excimer_profile_od'] = $recommendationData['prk_excimer_profile'] ?? null;
                            $recommendationData['prk_excimer_profile_os'] = $recommendationData['prk_excimer_profile'] ?? null;
                            $recommendationData['prk_monovision_eye_od'] = $recommendationData['prk_monovision_eye'] ?? null;
                            $recommendationData['prk_monovision_eye_os'] = $recommendationData['prk_monovision_eye'] ?? null;
                            $recommendationData['prk_target_od'] = $recommendationData['prk_target'] ?? null;
                            $recommendationData['prk_target_os'] = $recommendationData['prk_target'] ?? null;
                        }
                        // Copy Femto fields
                        elseif ($decision === 'femto_lasik') {
                            $recommendationData['femto_excimer_profile_od'] = $recommendationData['femto_excimer_profile'] ?? null;
                            $recommendationData['femto_excimer_profile_os'] = $recommendationData['femto_excimer_profile'] ?? null;
                            $recommendationData['femto_monovision_eye_od'] = $recommendationData['femto_monovision_eye'] ?? null;
                            $recommendationData['femto_monovision_eye_os'] = $recommendationData['femto_monovision_eye'] ?? null;
                            $recommendationData['femto_target_od'] = $recommendationData['femto_target'] ?? null;
                            $recommendationData['femto_target_os'] = $recommendationData['femto_target'] ?? null;
                        }
                        // Copy Smile fields
                        elseif ($decision === 'smile') {
                            $recommendationData['smile_monovision_eye_od'] = $recommendationData['smile_monovision_eye'] ?? null;
                            $recommendationData['smile_monovision_eye_os'] = $recommendationData['smile_monovision_eye'] ?? null;
                            $recommendationData['smile_target_od'] = $recommendationData['smile_target'] ?? null;
                            $recommendationData['smile_target_os'] = $recommendationData['smile_target'] ?? null;
                        }
                        // Copy PTK fields
                        elseif ($decision === 'ptk') {
                            $recommendationData['ptk_epithelial_removal_od'] = $recommendationData['ptk_epithelial_removal'] ?? null;
                            $recommendationData['ptk_epithelial_removal_os'] = $recommendationData['ptk_epithelial_removal'] ?? null;
                            $recommendationData['ptk_excimer_profile_od'] = $recommendationData['ptk_excimer_profile'] ?? null;
                            $recommendationData['ptk_excimer_profile_os'] = $recommendationData['ptk_excimer_profile'] ?? null;
                            $recommendationData['ptk_monovision_eye_od'] = $recommendationData['ptk_monovision_eye'] ?? null;
                            $recommendationData['ptk_monovision_eye_os'] = $recommendationData['ptk_monovision_eye'] ?? null;
                            $recommendationData['ptk_target_od'] = $recommendationData['ptk_target'] ?? null;
                            $recommendationData['ptk_target_os'] = $recommendationData['ptk_target'] ?? null;
                        }
                        // Copy Incompatible fields - ONLY if same_decision_both_eyes is checked
                        // Do NOT copy if user entered data separately in incompatible_notes_od or incompatible_notes_os
                        elseif ($decision === 'incompatible') {
                            // Only copy from shared field if it exists and separate fields are empty
                            // This prevents overwriting user-entered data
                            if (!empty($recommendationData['incompatible_notes']) && 
                                empty($recommendationData['incompatible_notes_od']) && 
                                empty($recommendationData['incompatible_notes_os'])) {
                                $recommendationData['incompatible_notes_od'] = $recommendationData['incompatible_notes'];
                                $recommendationData['incompatible_notes_os'] = $recommendationData['incompatible_notes'];
                            }
                            // If user entered data in OD or OS separately, keep them separate
                            // Do NOT copy between OD and OS automatically
                        }
                    }
                }
            }

            // Update operation with recommendation data
            $operation->update($recommendationData);

            // Update appointment visit_stage to 'completed' when saving operation
            // Only for the specific patient
            if ($operation->appointment_id) {
                $appointment = \App\Models\Appointment::find($operation->appointment_id);
                if ($appointment && !in_array($appointment->visit_stage, ['cancelled'])) {
                    $appointment->update(['visit_stage' => 'completed']);
                }
            }

            session()->flash('message', $this->editingId ? 'Operation updated successfully.' : 'Operation created successfully.');
            
            // Set editingId if it was a new operation
            if (!$this->editingId) {
                $this->editingId = $operation->id;
            }
            
            // Redirect after save - stay on edit page to continue editing with same tab
            if ($this->isCreatePage || $this->isEditPage) {
                // Preserve active tab in URL query parameter
                $this->redirect(route('operations.edit', ['id' => $operation->id, 'tab' => $this->activeTab]));
            } else {
                $this->resetAllForms();
            }
        } catch (\Exception $e) {
            \Log::error('Operation save error: ' . $e->getMessage());
            session()->flash('error', 'Failed to save operation: ' . $e->getMessage());
        }
    }

    public function edit($id): void
    {
        $operation = Operation::with([
            'refractiveProfile',
            'medicalHistory',
            'eyeExaminations',
            'ectasiaRiskAssessment',
            'appointment'
        ])->findOrFail($id);

        $this->editingId = $operation->id;
        
        // Restore active tab from URL query parameter if exists (after save redirect)
        $tabFromUrl = request()->query('tab');
        if ($tabFromUrl && in_array($tabFromUrl, ['basic', 'refractive', 'medical', 'exam', 'ectasia', 'recommendation', 'files'])) {
            $this->activeTab = $tabFromUrl;
        }

        $this->operationForm = [
            'patient_id' => $operation->patient_id,
            'doctor_id' => $operation->doctor_id ?? ($operation->appointment?->doctor_id ?? null),
            'branch_id' => $operation->branch_id,
            'appointment_id' => $operation->appointment_id,
            'start_date' => $operation->start_date?->format('Y-m-d'),
            'status' => $operation->status,
            'pre_op_assessment_date' => $operation->pre_op_assessment_date?->format('Y-m-d'),
        ];

        if ($operation->patient) {
            $this->selectedPatientId = $operation->patient_id;
            $this->patientSearch = $operation->patient->full_name;
        }

        // Load related data
        if ($refractive = $operation->refractiveProfile) {
            $this->refractiveForm = $refractive->toArray();
            unset($this->refractiveForm['id'], $this->refractiveForm['operation_id'], $this->refractiveForm['created_at'], $this->refractiveForm['updated_at'], $this->refractiveForm['patient_name'], $this->refractiveForm['patient_age']);
            // Convert null values back to empty strings for form compatibility
            foreach ($this->refractiveForm as $key => $value) {
                if ($value === null) {
                    $this->refractiveForm[$key] = '';
                }
            }
        } else {
            // Initialize with empty values if no refractive profile exists
            $this->refractiveForm = array_fill_keys(array_keys($this->refractiveForm), '');
            $this->refractiveForm['contact_lenses'] = 'No';
        }


        if ($medical = $operation->medicalHistory) {
            $this->medicalForm = $medical->toArray();
            unset($this->medicalForm['id'], $this->medicalForm['operation_id'], $this->medicalForm['created_at'], $this->medicalForm['updated_at']);
            // Convert boolean to string "1"/"" or "1"/"0" for radio buttons
            // Fields that use value="0" in the view: ocular_surgery, family_history_ocular_disease_yes, current_medications_yes
            $booleanFieldsWithZero = ['ocular_surgery', 'family_history_ocular_disease_yes', 'current_medications_yes'];
            foreach ($booleanFieldsWithZero as $field) {
                if (isset($this->medicalForm[$field])) {
                    $this->medicalForm[$field] = $this->medicalForm[$field] ? '1' : '0';
                } else {
                    $this->medicalForm[$field] = '0';
                }
            }
            
            // Fields that use value="" in the view: all other boolean fields
            $booleanFieldsWithEmpty = [
                'diabetes', 'chronic_disease', 'herpes_keratitis', 'glaucoma',
                'family_history_keratoconus', 'eye_rubber', 'pregnancy',
                'glare_halos_squint', 'refraction_stable_1year', 'contact_lens_use'
            ];
            foreach ($booleanFieldsWithEmpty as $field) {
                if (isset($this->medicalForm[$field])) {
                    $this->medicalForm[$field] = $this->medicalForm[$field] ? '1' : '';
                } else {
                    $this->medicalForm[$field] = '';
                }
            }
        } else {
            // Set default values if no medical history exists
            $this->medicalForm = [
                'diabetes' => '',
                'chronic_disease' => '',
                'herpes_keratitis' => '',
                'glaucoma' => '',
                'family_history_keratoconus' => '',
                'eye_rubber' => '',
                'pregnancy' => '',
                'ocular_surgery' => '0',
                'ocular_surgery_details' => '',
                'family_history_ocular_disease_yes' => '0',
                'family_history_ocular_disease' => '',
                'current_medications_yes' => '0',
                'current_medications' => '',
                'glare_halos_squint' => '',
                'refraction_stable_1year' => '1', // Default to Yes
                'contact_lens_use' => '',
            ];
        }

        if ($exam = $operation->eyeExaminations()->where('examination_type', 'pre_op')->first()) {
            $this->examForm = $exam->toArray();
            unset($this->examForm['id'], $this->examForm['operation_id'], $this->examForm['created_at'], $this->examForm['updated_at']);
        }

        if ($ectasia = $operation->ectasiaRiskAssessment) {
            $this->ectasiaForm = $ectasia->toArray();
            unset($this->ectasiaForm['id'], $this->ectasiaForm['operation_id'], $this->ectasiaForm['created_at'], $this->ectasiaForm['updated_at']);
        }

        // Determine if both eyes have same decision
        $sameDecision = false;
        if (!empty($operation->decision_od) && !empty($operation->decision_os)) {
            $sameDecision = ($operation->decision_od === $operation->decision_os);
        }

        // If same decision, load shared fields from OD fields (or use old shared fields if available)
        $prkEpithelialRemoval = $sameDecision ? ($operation->prk_epithelial_removal_od ?? $operation->prk_epithelial_removal ?? '') : ($operation->prk_epithelial_removal ?? '');
        $prkExcimerProfile = $sameDecision ? ($operation->prk_excimer_profile_od ?? $operation->prk_excimer_profile ?? '') : ($operation->prk_excimer_profile ?? '');
        $prkMonovisionEye = $sameDecision ? ($operation->prk_monovision_eye_od ?? $operation->prk_monovision_eye ?? '') : ($operation->prk_monovision_eye ?? '');
        $prkTarget = $sameDecision ? ($operation->prk_target_od ?? $operation->prk_target ?? '') : ($operation->prk_target ?? '');
        
        $femtoExcimerProfile = $sameDecision ? ($operation->femto_excimer_profile_od ?? $operation->femto_excimer_profile ?? '') : ($operation->femto_excimer_profile ?? '');
        $femtoMonovisionEye = $sameDecision ? ($operation->femto_monovision_eye_od ?? $operation->femto_monovision_eye ?? '') : ($operation->femto_monovision_eye ?? '');
        $femtoTarget = $sameDecision ? ($operation->femto_target_od ?? $operation->femto_target ?? '') : ($operation->femto_target ?? '');
        
        $smileMonovisionEye = $sameDecision ? ($operation->smile_monovision_eye_od ?? $operation->smile_monovision_eye ?? '') : ($operation->smile_monovision_eye ?? '');
        $smileTarget = $sameDecision ? ($operation->smile_target_od ?? $operation->smile_target ?? '') : ($operation->smile_target ?? '');
        
        $ptkEpithelialRemoval = $sameDecision ? ($operation->ptk_epithelial_removal_od ?? $operation->ptk_epithelial_removal ?? '') : ($operation->ptk_epithelial_removal ?? '');
        $ptkExcimerProfile = $sameDecision ? ($operation->ptk_excimer_profile_od ?? $operation->ptk_excimer_profile ?? '') : ($operation->ptk_excimer_profile ?? '');
        $ptkMonovisionEye = $sameDecision ? ($operation->ptk_monovision_eye_od ?? $operation->ptk_monovision_eye ?? '') : ($operation->ptk_monovision_eye ?? '');
        $ptkTarget = $sameDecision ? ($operation->ptk_target_od ?? $operation->ptk_target ?? '') : ($operation->ptk_target ?? '');
        
        $incompatibleNotes = $sameDecision ? ($operation->incompatible_notes_od ?? $operation->incompatible_notes ?? '') : ($operation->incompatible_notes ?? '');

        $this->recommendationForm = [
            'decision' => $operation->decision ?? '',
            'decision_od' => $operation->decision_od ?? ($operation->decision ?? ''),
            'decision_os' => $operation->decision_os ?? ($operation->decision ?? ''),
            'same_decision_both_eyes' => $sameDecision,
            // Old shared fields (for backward compatibility) - use loaded values if same decision
            'prk_epithelial_removal' => $prkEpithelialRemoval,
            'prk_excimer_profile' => $prkExcimerProfile,
            'prk_monovision_eye' => $prkMonovisionEye,
            'prk_target' => $prkTarget,
            'femto_excimer_profile' => $femtoExcimerProfile,
            'femto_monovision_eye' => $femtoMonovisionEye,
            'femto_target' => $femtoTarget,
            'smile_monovision_eye' => $smileMonovisionEye,
            'smile_target' => $smileTarget,
            'ptk_epithelial_removal' => $ptkEpithelialRemoval,
            'ptk_excimer_profile' => $ptkExcimerProfile,
            'ptk_monovision_eye' => $ptkMonovisionEye,
            'ptk_target' => $ptkTarget,
            'incompatible_notes' => $incompatibleNotes,
            // Separate OD fields
            'prk_epithelial_removal_od' => $operation->prk_epithelial_removal_od ?? '',
            'prk_excimer_profile_od' => $operation->prk_excimer_profile_od ?? '',
            'prk_monovision_eye_od' => $operation->prk_monovision_eye_od ?? '',
            'prk_target_od' => $operation->prk_target_od ?? '',
            'femto_excimer_profile_od' => $operation->femto_excimer_profile_od ?? '',
            'femto_monovision_eye_od' => $operation->femto_monovision_eye_od ?? '',
            'femto_target_od' => $operation->femto_target_od ?? '',
            'smile_monovision_eye_od' => $operation->smile_monovision_eye_od ?? '',
            'smile_target_od' => $operation->smile_target_od ?? '',
            'ptk_epithelial_removal_od' => $operation->ptk_epithelial_removal_od ?? '',
            'ptk_excimer_profile_od' => $operation->ptk_excimer_profile_od ?? '',
            'ptk_monovision_eye_od' => $operation->ptk_monovision_eye_od ?? '',
            'ptk_target_od' => $operation->ptk_target_od ?? '',
            'incompatible_notes_od' => $operation->incompatible_notes_od ?? '',
            // Separate OS fields
            'prk_epithelial_removal_os' => $operation->prk_epithelial_removal_os ?? '',
            'prk_excimer_profile_os' => $operation->prk_excimer_profile_os ?? '',
            'prk_monovision_eye_os' => $operation->prk_monovision_eye_os ?? '',
            'prk_target_os' => $operation->prk_target_os ?? '',
            'femto_excimer_profile_os' => $operation->femto_excimer_profile_os ?? '',
            'femto_monovision_eye_os' => $operation->femto_monovision_eye_os ?? '',
            'femto_target_os' => $operation->femto_target_os ?? '',
            'smile_monovision_eye_os' => $operation->smile_monovision_eye_os ?? '',
            'smile_target_os' => $operation->smile_target_os ?? '',
            'ptk_epithelial_removal_os' => $operation->ptk_epithelial_removal_os ?? '',
            'ptk_excimer_profile_os' => $operation->ptk_excimer_profile_os ?? '',
            'ptk_monovision_eye_os' => $operation->ptk_monovision_eye_os ?? '',
            'ptk_target_os' => $operation->ptk_target_os ?? '',
            'incompatible_notes_os' => $operation->incompatible_notes_os ?? '',
            // Planning fields for each eye
            'planning_sphere_od' => $operation->planning_sphere_od ?? '',
            'planning_cylinder_od' => $operation->planning_cylinder_od ?? '',
            'planning_axis_od' => $operation->planning_axis_od ?? '',
            'planning_sphere_os' => $operation->planning_sphere_os ?? '',
            'planning_cylinder_os' => $operation->planning_cylinder_os ?? '',
            'planning_axis_os' => $operation->planning_axis_os ?? '',
            'recommendation_notes' => $operation->recommendation_notes,
        ];

        // Show planning sections if values exist
        $this->showPlanningOd = !empty($operation->planning_sphere_od) || !empty($operation->planning_cylinder_od) || !empty($operation->planning_axis_od);
        $this->showPlanningOs = !empty($operation->planning_sphere_os) || !empty($operation->planning_cylinder_os) || !empty($operation->planning_axis_os);
        $this->showPlanningBoth = $this->showPlanningOd && $this->showPlanningOs && $sameDecision;

        // Load operation files
        $this->loadOperationFiles();

        // Update appointment visit_stage to 'in_consultation' when doctor opens the file
        // Only for the specific patient, not all patients
        if ($operation->appointment_id) {
            $appointment = Appointment::find($operation->appointment_id);
            if ($appointment && !in_array($appointment->visit_stage, ['completed', 'cancelled'])) {
                $appointment->update(['visit_stage' => 'in_consultation']);
            }
        }

        $this->showModal = true;
    }

    /**
     * Cancel and close the modal.
     * 
     * Business Purpose: When user clicks Cancel button or closes the modal,
     * update the appointment visit_stage to 'completed' for the specific patient.
     */
    public function cancel(): void
    {
        // Update appointment visit_stage to 'completed' when closing the file
        // Only for the specific patient using operationForm['appointment_id']
        if (!empty($this->operationForm['appointment_id'])) {
            $appointment = Appointment::find($this->operationForm['appointment_id']);
            if ($appointment && !in_array($appointment->visit_stage, ['cancelled'])) {
                $appointment->update(['visit_stage' => 'completed']);
            }
        }
        
        $this->resetAllForms();
        $this->showModal = false;
        
        // Redirect to operations list if on edit page
        if ($this->isEditPage) {
            $this->redirect(route('operations.index'));
        }
    }

    public function delete($id): void
    {
        Operation::findOrFail($id)->delete();
        session()->flash('message', 'Operation deleted successfully.');
    }

    /**
     * View operation - creates it if it doesn't exist, then redirects to edit page
     * 
     * Business Purpose: When user clicks "View" button in Assessment page,
     * this method ensures an Operation exists for the appointment and opens it for editing.
     * 
     * @param int $appointmentId The appointment ID to create/view operation for
     */
    public function viewOperation($appointmentId): void
    {
        try {
            $appointment = Appointment::with('operation')->findOrFail($appointmentId);
            
            // Verify appointment type is valid for operations
            if (!in_array($appointment->visit_type, ['Assessment', 'Operation'])) {
                session()->flash('error', 'This appointment type does not support operations.');
                return;
            }

            // If operation doesn't exist, create it
            if (!$appointment->operation_id || !$appointment->operation) {
                $operation = Operation::create([
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    'branch_id' => $appointment->branch_id ?? auth()->user()->branch_id ?? 1,
                    'appointment_id' => $appointment->id,
                    'created_by' => auth()->id(),
                    'status' => 'scheduled',
                    'start_date' => $appointment->appointment_date,
                ]);
                
                // Link appointment to operation
                $appointment->update(['operation_id' => $operation->id]);
                
                $operationId = $operation->id;
            } else {
                $operationId = $appointment->operation_id;
            }

            // Update visit_stage to 'in_consultation' when doctor opens the file
            // Only for the specific patient, not all patients
            if (!in_array($appointment->visit_stage, ['completed', 'cancelled'])) {
                $appointment->update(['visit_stage' => 'in_consultation']);
            }

            // Redirect to edit page with query parameters
            $url = route('operations.edit', ['id' => $operationId]) . '?appointment_id=' . $appointment->id . '&patient_id=' . $appointment->patient_id;
            $this->redirect($url);
        } catch (\Exception $e) {
            \Log::error('OperationManager viewOperation error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Failed to open operation: ' . $e->getMessage());
        }
    }

    public function loadOperationFiles(): void
    {
        if ($this->editingId) {
            $this->operationFiles = OperationFile::where('operation_id', $this->editingId)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $this->operationFiles = collect([]);
        }
    }

    public function uploadFile(): void
    {
        // Check if operation is saved
        if (!$this->editingId) {
            session()->flash('error', 'Please save the operation first before uploading files.');
            $this->dispatch('alert', type: 'error', message: 'Please save the operation first before uploading files.');
            return;
        }

        // Validate file
        try {
            $this->validate([
                'newFile' => 'required|file|mimes:jpeg,jpg,png,gif,webp,pdf|max:10240', // Max 10MB
                'newFileDescription' => 'nullable|string|max:1000',
                'newFileEye' => 'required|in:OD,OS,OU',
            ], [
                'newFile.required' => 'Please select a file to upload.',
                'newFile.mimes' => 'File must be an image (jpeg, jpg, png, gif, webp) or PDF.',
                'newFile.max' => 'File size must not exceed 10MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', $e->getMessage());
            return;
        }

        $file = $this->newFile;
        
        if (!$file) {
            session()->flash('error', 'No file selected.');
            return;
        }

        try {
            // Sanitize filename
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $filePath = $file->storeAs('operation_files', $fileName, 'public');

            // Determine file type
            $mimeType = $file->getMimeType();
            $fileType = str_starts_with($mimeType, 'image/') ? 'image' : 'pdf';

            OperationFile::create([
                'operation_id' => $this->editingId,
                'file_path' => $filePath,
                'file_name' => $originalName,
                'file_type' => $fileType,
                'mime_type' => $mimeType,
                'file_size' => $file->getSize(),
                'description' => $this->newFileDescription ?? '',
                'eye' => $this->newFileEye ?? 'OU',
            ]);

            // Reset form
            $this->newFile = null;
            $this->newFileDescription = '';
            $this->newFileEye = 'OU';

            // Reload files
            $this->loadOperationFiles();

            session()->flash('message', 'File uploaded successfully.');
        } catch (\Exception $e) {
            \Log::error('File upload error: ' . $e->getMessage());
            session()->flash('error', 'Failed to upload file: ' . $e->getMessage());
        }
    }

    public function deleteFile($fileId): void
    {
        try {
            $file = OperationFile::findOrFail($fileId);
            
            // Delete file from storage
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete record
            $file->delete();

            // Reload files
            $this->loadOperationFiles();

            session()->flash('message', 'File deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete file: ' . $e->getMessage());
        }
    }

    /**
     * Auto-create Operations for Appointments that don't have one yet
     * This ensures all Assessment and Operation type appointments have a corresponding Operation
     */
    private function autoCreateOperationsForAppointments($branchId = null): void
    {
        try {
            // Get all Appointments of type "Assessment" or "Operation" that don't have an operation_id
            $appointmentsQuery = Appointment::whereIn('visit_type', ['Assessment', 'Operation'])
                ->whereNull('operation_id')
                ->with(['patient', 'doctor', 'branch']);

            // Filter by branch if user has a branch
            if ($branchId) {
                $appointmentsQuery->where('branch_id', $branchId);
            }

            $appointments = $appointmentsQuery->get();

            $createdCount = 0;
            $skippedCount = 0;

            // Create Operations for each Appointment
            foreach ($appointments as $appointment) {
                // Skip if patient, doctor, or branch is missing
                if (!$appointment->patient_id || !$appointment->doctor_id || !$appointment->branch_id) {
                    $skippedCount++;
                    continue;
                }

                try {
                    // Create Operation
                    $operation = Operation::create([
                        'patient_id' => $appointment->patient_id,
                        'doctor_id' => $appointment->doctor_id,
                        'branch_id' => $appointment->branch_id,
                        'appointment_id' => $appointment->id,
                        'created_by' => auth()->id() ?? 1,
                        'status' => 'scheduled',
                        'start_date' => $appointment->appointment_date ?? now(),
                    ]);

                    // Link Appointment to Operation
                    $appointment->update(['operation_id' => $operation->id]);
                    $createdCount++;
                } catch (\Exception $e) {
                    // Log individual operation creation errors
                    \Log::error('Failed to create operation for appointment ' . $appointment->id . ': ' . $e->getMessage());
                    $skippedCount++;
                }
            }

            // Log summary if operations were created
            if ($createdCount > 0) {
                \Log::info("Auto-created {$createdCount} operations. Skipped: {$skippedCount}");
            }
        } catch (\Exception $e) {
            // Log error but don't break the page
            \Log::error('Failed to auto-create operations: ' . $e->getMessage());
        }
    }

    public function render()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return view('livewire.unauthorized')->layout('components.layouts.app');
            }

            $branchId = $user->branch_id;

            // Operations will be created when user clicks "View" button
            // No auto-creation here

            // Show Appointments of type "Assessment" (with or without operations)
            $query = Appointment::query()
                ->with(['patient', 'doctor', 'branch', 'operation'])
                ->where('visit_type', 'Assessment');
                
            // Apply branch filter
            if ($branchId && !$user->isAdmin()) {
                $query->where('branch_id', $branchId);
            }
            
            // Apply search filter
            if (!empty($this->search)) {
                $query->whereHas('patient', function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%')
                      ->orWhere('id_number', 'like', '%' . $this->search . '%');
                });
            }
            
            // Apply status filter (filter by visit_stage, not operation.status)
            if (!empty($this->statusFilter)) {
                $query->where('visit_stage', $this->statusFilter);
            }
            
            // Apply date filter
            if ($this->dateFilter === 'today') {
                // Show only today's appointments
                $query->whereDate('appointment_date', \Carbon\Carbon::today());
            } elseif ($this->dateFilter === 'upcoming') {
                // Show upcoming appointments (future dates, excluding completed/cancelled)
                $query->whereDate('appointment_date', '>=', \Carbon\Carbon::today())
                      ->whereNotIn('visit_stage', ['completed', 'cancelled']);
            } elseif ($this->dateFilter === 'past') {
                // Show past appointments (past dates or completed/cancelled)
                $query->where(function ($query) {
                    $query->whereDate('appointment_date', '<', \Carbon\Carbon::today())
                          ->orWhereIn('visit_stage', ['completed', 'cancelled']);
                });
            }
            // If dateFilter === 'all', show all appointments (no date filter applied)
            
            $query->orderBy('appointment_date', 'desc')
                  ->orderBy('appointment_time', 'desc')
                  ->orderBy('created_at', 'desc');

            $appointments = $query->paginate($this->perPage ?? 10);

            $patients = Patient::when($this->patientSearch, function ($q) {
                $q->where('full_name', 'like', '%' . $this->patientSearch . '%')
                    ->orWhere('id_number', 'like', '%' . $this->patientSearch . '%')
                    ->orWhere('phone', 'like', '%' . $this->patientSearch . '%');
            })
                ->limit(10)
                ->get();

            $doctors = Doctor::when($branchId, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })->get();

            $branches = Branch::where('is_active', true)->get();

            if ($this->isCreatePage || $this->isEditPage) {
                return view('livewire.operation-manager', [
                    'operations' => collect([]),
                    'patients' => $patients,
                    'doctors' => $doctors,
                    'branches' => $branches,
                ])->layout('components.layouts.app');
            }

            return view('livewire.operation-manager', [
                'appointments' => $appointments,
                'operations' => collect([]), // Keep for backward compatibility
                'patients' => $patients,
                'doctors' => $doctors,
                'branches' => $branches,
            ])->layout('components.layouts.app');
        } catch (\Exception $e) {
            \Log::error('OperationManager render error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            session()->flash('error', 'An error occurred while loading the page. Please try again.');
            return view('livewire.operation-manager', [
                'appointments' => collect([])->paginate($this->perPage),
                'operations' => collect([]),
                'patients' => collect([]),
                'doctors' => collect([]),
                'branches' => collect([]),
            ])->layout('components.layouts.app');
        }
    }
}
