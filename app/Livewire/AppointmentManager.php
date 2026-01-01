<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Operation;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    protected $listeners = ['refreshAppointments' => '$refresh'];

    public string $search = '';
    public int $perPage = 10;
    public string $patientSearch = '';
    public string $visitTypeFilter = '';
    public string $visitStageFilter = '';
    public string $doctorFilter = '';
    public string $dateFilter = 'today';
    public string $dateFrom = '';
    public string $dateTo = '';
    public $selectedPatientId = null;
    public $selectedPatientData = null;
    public $showModal = false;
    public $showPatientModal = false;
    public $showInvoiceModal = false;
    public $selectedAppointmentId = null;
    public $editingId = null;
    public $patientPreSelected = false;
    public $showOperationWarning = false;
    public $operationHasData = false;
    public array $patientForm = [
        'full_name' => '',
        'id_number' => '',
        'date_of_birth' => '',
        'gender' => '',
        'phone' => '',
        'phone_secondary' => '',
        'city' => '',
        'occupation' => '',
        'notes' => '',
    ];
    public array $form = [
        'patient_id' => null,
        'doctor_id' => null,
        'appointment_date' => '',
        'appointment_time' => '',
        'duration' => 30,
        'notes' => '',
        'visit_stage' => null,
        'visit_type' => null,
    ];
    public array $invoiceForm = [
        'patient_id' => null,
        'appointment_id' => null,
        'doctor_id' => null,
        'invoice_date' => '',
        'subtotal' => '0.00',
        'discount' => '0.00',
        'tax' => '0.00',
        'total_amount' => '0.00',
        'paid_amount' => '0.00',
        'remaining_amount' => '0.00',
        'status' => 'pending',
        'payment_method' => null,
        'notes' => '',
    ];

    protected function rules(): array
    {
        return [
            'form.patient_id' => 'required|exists:patients,id',
            'form.doctor_id' => 'required|exists:doctors,id',
            'form.appointment_date' => 'required|date',
            'form.appointment_time' => 'required',
            'form.duration' => 'required|integer|min:5|max:480',
            'form.notes' => 'nullable|string|max:1000',
            'form.visit_stage' => 'required|in:waiting,in_consultation,completed,cancelled',
            'form.visit_type' => 'required|in:Assessment,Operation,Follow up,New visit',
        ];
    }

    public function mount(): void
    {
        // Check if patient_id is passed as query parameter (from Patient Manager)
        $patientId = request()->query('patient_id');
        if ($patientId) {
            $this->resetForm();
            $this->selectPatient($patientId);
            $this->patientPreSelected = true;
            $this->showModal = true;
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function updatingVisitTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingVisitStageFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDoctorFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFilter(): void
    {
        $this->resetPage();
        // Reset date range when changing filter
        if ($this->dateFilter !== 'date_range') {
            $this->dateFrom = '';
            $this->dateTo = '';
        }
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function updatingPatientSearch(): void
    {
        $this->selectedPatientId = null;
        $this->selectedPatientData = null;
    }

    public function updatedFormVisitType($value): void
    {
        // Check if changing from Assessment and operation has data
        if ($this->editingId) {
            $appointment = Appointment::with('operation')->find($this->editingId);
            if ($appointment && $appointment->visit_type === 'Assessment' && $value !== 'Assessment') {
                if ($appointment->operation_id && $appointment->operation && $appointment->operation->hasData()) {
                    $this->showOperationWarning = true;
                    $this->operationHasData = true;
                } else {
                    $this->showOperationWarning = false;
                    $this->operationHasData = false;
                }
            } else {
                $this->showOperationWarning = false;
            }
        }
    }

    public function selectPatient($patientId): void
    {
        $patient = Patient::findOrFail($patientId);
        $this->selectedPatientId = $patientId;
        
        $lastAppointment = $patient->appointments()->latest()->first();
        $lastVisitDate = $lastAppointment?->appointment_date;
        $lastVisitFormatted = $lastVisitDate ? $lastVisitDate->format('d-m-Y') : 'No previous visits';
        
        // Calculate days between last visit and current appointment date (or today if no date set)
        $daysBetween = null;
        if ($lastVisitDate) {
            $currentDate = !empty($this->form['appointment_date']) 
                ? \Carbon\Carbon::parse($this->form['appointment_date'])
                : \Carbon\Carbon::today();
            $daysBetween = $lastVisitDate->diffInDays($currentDate);
        }
        
        $this->selectedPatientData = [
            'id' => $patient->id,
            'full_name' => $patient->full_name,
            'id_number' => $patient->id_number,
            'phone' => $patient->phone,
            'last_visit' => $lastVisitFormatted,
            'days_between' => $daysBetween,
        ];
        $this->form['patient_id'] = $patientId;
        $this->patientSearch = $patient->full_name;
        $this->dispatch('patient-selected');
    }

    public function updatedFormAppointmentDate($value): void
    {
        // Recalculate days between when appointment date changes
        if ($this->selectedPatientId && $value) {
            $patient = Patient::find($this->selectedPatientId);
            if ($patient) {
                $lastAppointment = $patient->appointments()->latest()->first();
                $lastVisitDate = $lastAppointment?->appointment_date;
                
                if ($lastVisitDate) {
                    $currentDate = \Carbon\Carbon::parse($value);
                    $daysBetween = $lastVisitDate->diffInDays($currentDate);
                    if (isset($this->selectedPatientData)) {
                        $this->selectedPatientData['days_between'] = $daysBetween;
                    }
                }
            }
        }
    }



    public function resetForm(): void
    {
        $this->form = [
            'patient_id' => null,
            'doctor_id' => null,
            'appointment_date' => now()->format('Y-m-d'),
            'appointment_time' => now()->format('H:i'),
            'duration' => 30,
            'notes' => '',
            'visit_stage' => null,
            'visit_type' => null,
        ];
        $this->selectedPatientId = null;
        $this->selectedPatientData = null;
        $this->patientSearch = '';
        $this->editingId = null;
        $this->patientPreSelected = false;
        $this->showModal = false;
        $this->showOperationWarning = false;
        $this->operationHasData = false;
    }

    public function openPatientModal(): void
    {
        $this->patientForm = [
            'full_name' => '',
            'id_number' => '',
            'date_of_birth' => '',
            'gender' => '',
            'phone' => '',
            'phone_secondary' => '',
            'city' => '',
            'occupation' => '',
            'notes' => '',
        ];
        $this->showPatientModal = true;
    }

    public function closePatientModal(): void
    {
        $this->showPatientModal = false;
        $this->patientForm = [
            'full_name' => '',
            'id_number' => '',
            'date_of_birth' => '',
            'gender' => '',
            'phone' => '',
            'phone_secondary' => '',
            'city' => '',
            'occupation' => '',
            'notes' => '',
        ];
    }

    public function savePatient(): void
    {
        $rules = [
            'patientForm.full_name' => 'required|string|max:255',
            'patientForm.id_number' => 'required|string|max:50|unique:patients,id_number',
            'patientForm.date_of_birth' => 'required|date',
            'patientForm.gender' => 'required|in:male,female',
            'patientForm.phone' => 'required|regex:/^[\d٠١٢٣٤٥٦٧٨٩+\-\s()]+$/u|max:32',
            'patientForm.phone_secondary' => [
                'nullable',
                'regex:/^[\d٠١٢٣٤٥٦٧٨٩+\-\s()]+$/u',
                'max:32',
            ],
            'patientForm.city' => 'required|string|max:255',
            'patientForm.occupation' => 'required|string|max:255',
            'patientForm.notes' => 'nullable|string|max:1000',
        ];

        // Add custom validation for phone_secondary to be different from phone
        if (!empty($this->patientForm['phone_secondary'])) {
            $rules['patientForm.phone_secondary'][] = function ($attribute, $value, $fail) {
                if ($value === $this->patientForm['phone']) {
                    $fail('The secondary phone number must be different from the primary phone number.');
                }
            };
        }

        $this->validate($rules);

        $patient = Patient::create($this->patientForm);
        
        // Auto-select the newly created patient
        $this->selectPatient($patient->id);
        
        $this->closePatientModal();
        session()->flash('message', 'Patient added successfully and selected.');
    }

    public function create(): void
    {
        if (!auth()->user()->can('create.appointments')) {
            session()->flash('error', 'You do not have permission to create appointments.');
            return;
        }
        $this->resetForm();
        // Set default date and time to current
        $this->form['appointment_date'] = now()->format('Y-m-d');
        $this->form['appointment_time'] = now()->format('H:i');
        $this->showModal = true;
    }

    public function goToAssessment($appointmentId): void
    {
        // Get appointment with operation relationship
        $appointment = Appointment::with('operation')->findOrFail($appointmentId);
        
        // Check if visit_type supports assessment/operation
        if (!in_array($appointment->visit_type, ['Assessment', 'Operation'])) {
            session()->flash('error', 'This appointment type does not support assessment.');
            return;
        }
        
        // If operation_id exists, redirect to edit page (update existing operation)
        if ($appointment->operation_id && $appointment->operation) {
            $this->redirect(route('operations.edit', [
                'id' => $appointment->operation_id,
                'appointment_id' => $appointmentId,
                'patient_id' => $appointment->patient_id
            ]));
        } else {
            // If no operation_id, create it automatically before redirecting
            $operation = Operation::create([
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'branch_id' => $appointment->branch_id,
                'appointment_id' => $appointment->id,
                'created_by' => auth()->id(),
                'operation_type' => 'Femto-LASIK', // Default type, can be changed later
                'operation_eye' => 'OU', // Default to both eyes
                'cost' => 0.00,
                'status' => 'scheduled',
                'start_date' => $appointment->appointment_date,
            ]);
            
            // Link appointment to operation
            $appointment->update(['operation_id' => $operation->id]);
            
            // Redirect to edit page (not create page) since operation is now created
            $this->redirect(route('operations.edit', [
                'id' => $operation->id,
                'appointment_id' => $appointmentId,
                'patient_id' => $appointment->patient_id
            ]));
        }
    }

    public function createVisitForPatient($appointmentId): void
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $this->resetForm();
        
        // Pre-fill patient data
        $this->selectedPatientId = $appointment->patient_id;
        $this->patientSearch = $appointment->patient->full_name;
        
        // Get last visit data
        $lastAppointment = $appointment->patient->appointments()
            ->where('id', '!=', $appointmentId)
            ->latest()
            ->first();
        $lastVisitDate = $lastAppointment?->appointment_date;
        $lastVisitFormatted = $lastVisitDate ? $lastVisitDate->format('d-m-Y') : 'No previous visits';
        
        // Calculate days between
        $daysBetween = null;
        if ($lastVisitDate) {
            $currentDate = \Carbon\Carbon::today();
            $daysBetween = $lastVisitDate->diffInDays($currentDate);
        }
        
        $this->selectedPatientData = [
            'id' => $appointment->patient->id,
            'full_name' => $appointment->patient->full_name,
            'id_number' => $appointment->patient->id_number,
            'phone' => $appointment->patient->phone,
            'last_visit' => $lastVisitFormatted,
            'days_between' => $daysBetween,
        ];
        
        $this->form['patient_id'] = $appointment->patient_id;
        $this->form['doctor_id'] = $appointment->doctor_id;
        
        $this->showModal = true;
    }

    public function edit($id): void
    {
        if (!auth()->user()->can('update.appointments')) {
            session()->flash('error', 'You do not have permission to update appointments.');
            return;
        }
        $appointment = Appointment::with(['patient', 'doctor', 'operation'])->findOrFail($id);
        $this->editingId = $appointment->id;
        $this->selectedPatientId = $appointment->patient_id;
        $this->patientSearch = $appointment->patient->full_name;
        $this->form = [
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'appointment_date' => $appointment->appointment_date->format('Y-m-d'),
            'appointment_time' => \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time)->format('H:i'),
            'duration' => $appointment->duration,
            'notes' => $appointment->notes,
            'visit_stage' => $appointment->visit_stage,
            'visit_type' => $appointment->visit_type,
        ];
        
        // Check if operation has data for warning
        if ($appointment->operation_id && $appointment->operation) {
            $this->operationHasData = $appointment->operation->hasData();
        } else {
            $this->operationHasData = false;
        }
        
        $this->showModal = true;
    }

    public function save(): void
    {
        if ($this->editingId) {
            if (!auth()->user()->can('update.appointments')) {
                session()->flash('error', 'You do not have permission to update appointments.');
                return;
            }
        } else {
            if (!auth()->user()->can('create.appointments')) {
                session()->flash('error', 'You do not have permission to create appointments.');
                return;
            }
        }
        
        $this->validate();
        
        $data = $this->form;
        
        // Add created_by and branch_id for new appointments
        if (!$this->editingId) {
            $data['created_by'] = auth()->id();
            $data['branch_id'] = auth()->user()->branch_id;
        }
        
        if ($this->editingId) {
            // Editing existing appointment
            $appointment = Appointment::findOrFail($this->editingId);
            $oldVisitType = $appointment->visit_type;
            $newVisitType = $data['visit_type'];
            
            // Handle visit_type changes
            if ($oldVisitType !== $newVisitType) {
                // If changing FROM "Assessment" or "Operation" to something else
                if (in_array($oldVisitType, ['Assessment', 'Operation']) && 
                    !in_array($newVisitType, ['Assessment', 'Operation'])) {
                    if ($appointment->operation_id) {
                        $operation = Operation::find($appointment->operation_id);
                        
                        if ($operation) {
                            // Check if operation has any data stored
                            if ($operation->hasData()) {
                                // Operation has data - only unlink, don't delete
                                // This prevents accidental data loss
                                $operation->update(['appointment_id' => null]);
                                $data['operation_id'] = null;
                                $message = 'Appointment updated successfully. Operation unlinked (data preserved).';
                            } else {
                                // Operation is empty - safe to delete
                                $operation->delete();
                                $data['operation_id'] = null;
                                $message = 'Appointment updated successfully. Empty operation deleted.';
                            }
                        } else {
                            // Operation not found - just unlink
                            $data['operation_id'] = null;
                            $message = 'Appointment updated successfully.';
                        }
                    } else {
                        $message = 'Appointment updated successfully.';
                    }
                }
                
                // If changing TO "Assessment" or "Operation" and no operation exists
                if (in_array($newVisitType, ['Assessment', 'Operation']) && !$appointment->operation_id) {
                    $operation = Operation::create([
                        'patient_id' => $appointment->patient_id,
                        'doctor_id' => $appointment->doctor_id,
                        'branch_id' => $appointment->branch_id,
                        'appointment_id' => $appointment->id,
                        'created_by' => auth()->id(),
                        'operation_type' => 'Femto-LASIK', // Default type, can be changed later
                        'operation_eye' => 'OU', // Default to both eyes
                        'cost' => 0.00,
                        'status' => 'scheduled',
                        'start_date' => $appointment->appointment_date,
                    ]);
                    
                    $data['operation_id'] = $operation->id;
                    $message = 'Appointment updated successfully. Operation created and linked automatically.';
                } elseif (!isset($message)) {
                    // Only set message if not already set above
                    $message = 'Appointment updated successfully.';
                }
            } else {
                $message = 'Appointment updated successfully.';
            }
            
            $appointment->update($data);
        } else {
            // Creating new appointment
            $appointment = Appointment::create($data);
            $message = 'Appointment added successfully.';
            
            // If visit_type is "Assessment" or "Operation", automatically create an Operation and link it
            if (in_array($data['visit_type'], ['Assessment', 'Operation'])) {
                $operation = Operation::create([
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    'branch_id' => $appointment->branch_id,
                    'appointment_id' => $appointment->id,
                    'created_by' => auth()->id(),
                    'operation_type' => 'Femto-LASIK', // Default type, can be changed later
                    'operation_eye' => 'OU', // Default to both eyes
                    'cost' => 0.00,
                    'status' => 'scheduled',
                    'start_date' => $appointment->appointment_date,
                ]);
                
                // Link operation to appointment
                $appointment->update(['operation_id' => $operation->id]);
                
                $message .= ' Operation created and linked automatically.';
            }
        }
        
        $this->resetForm();
        session()->flash('message', $message);
    }

    public function delete($id): void
    {
        if (!auth()->user()->can('delete.appointments')) {
            session()->flash('error', 'You do not have permission to delete appointments.');
            return;
        }
        
        Appointment::findOrFail($id)->delete();
        session()->flash('message', 'Appointment deleted successfully.');
    }

    public function openInvoiceModal($appointmentId): void
    {
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($appointmentId);
        $this->selectedAppointmentId = $appointmentId;
        $this->invoiceForm = [
            'patient_id' => $appointment->patient_id,
            'appointment_id' => $appointmentId,
            'doctor_id' => $appointment->doctor_id,
            'invoice_date' => now()->format('Y-m-d'),
            'subtotal' => '0.00',
            'discount' => '0.00',
            'tax' => '0.00',
            'total_amount' => '0.00',
            'paid_amount' => '0.00',
            'remaining_amount' => '0.00',
            'status' => 'pending',
            'payment_method' => null,
            'notes' => '',
        ];
        $this->showInvoiceModal = true;
    }

    public function closeInvoiceModal(): void
    {
        $this->showInvoiceModal = false;
        $this->selectedAppointmentId = null;
        $this->invoiceForm = [
            'patient_id' => null,
            'appointment_id' => null,
            'doctor_id' => null,
            'invoice_date' => '',
            'subtotal' => '0.00',
            'discount' => '0.00',
            'tax' => '0.00',
            'total_amount' => '0.00',
            'paid_amount' => '0.00',
            'remaining_amount' => '0.00',
            'status' => 'pending',
            'payment_method' => null,
            'notes' => '',
        ];
    }

    public function calculateInvoiceTotal(): void
    {
        $subtotal = (float) ($this->invoiceForm['subtotal'] ?? 0);
        $discount = (float) ($this->invoiceForm['discount'] ?? 0);
        $tax = (float) ($this->invoiceForm['tax'] ?? 0);
        
        $total = $subtotal - $discount + $tax;
        $this->invoiceForm['total_amount'] = number_format($total, 2, '.', '');
        
        $paid = (float) ($this->invoiceForm['paid_amount'] ?? 0);
        $remaining = max(0, $total - $paid);
        $this->invoiceForm['remaining_amount'] = number_format($remaining, 2, '.', '');
        
        if ($remaining <= 0 && $paid > 0) {
            $this->invoiceForm['status'] = 'paid';
        } elseif ($paid > 0 && $remaining > 0) {
            $this->invoiceForm['status'] = 'partial';
        }
    }

    public function updatedInvoiceFormSubtotal(): void
    {
        $this->calculateInvoiceTotal();
    }

    public function updatedInvoiceFormDiscount(): void
    {
        $this->calculateInvoiceTotal();
    }

    public function updatedInvoiceFormTax(): void
    {
        $this->calculateInvoiceTotal();
    }

    public function updatedInvoiceFormPaidAmount(): void
    {
        $this->calculateInvoiceTotal();
    }

    public function saveInvoice(): void
    {
        $this->validate([
            'invoiceForm.patient_id' => 'required|exists:patients,id',
            'invoiceForm.appointment_id' => 'nullable|exists:appointments,id',
            'invoiceForm.doctor_id' => 'nullable|exists:doctors,id',
            'invoiceForm.invoice_date' => 'required|date',
            'invoiceForm.subtotal' => 'required|numeric|min:0',
            'invoiceForm.discount' => 'required|numeric|min:0',
            'invoiceForm.tax' => 'required|numeric|min:0',
            'invoiceForm.total_amount' => 'required|numeric|min:0',
            'invoiceForm.paid_amount' => 'required|numeric|min:0',
            'invoiceForm.remaining_amount' => 'required|numeric|min:0',
            'invoiceForm.status' => 'required|in:draft,pending,partial,paid,cancelled',
            'invoiceForm.payment_method' => 'nullable|in:cash,card,bank_transfer,cheque,other',
            'invoiceForm.notes' => 'nullable|string|max:1000',
        ]);

        $data = $this->invoiceForm;
        $data['created_by'] = auth()->id();
        $data['branch_id'] = auth()->user()?->branch_id;
        $data['invoice_number'] = $this->generateInvoiceNumber();
        
        // Convert string amounts to decimal
        $data['subtotal'] = (float) $data['subtotal'];
        $data['discount'] = (float) $data['discount'];
        $data['tax'] = (float) $data['tax'];
        $data['total_amount'] = (float) $data['total_amount'];
        $data['paid_amount'] = (float) $data['paid_amount'];
        $data['remaining_amount'] = (float) $data['remaining_amount'];

        Invoice::create($data);
        
        $this->closeInvoiceModal();
        session()->flash('message', 'Invoice created successfully.');
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-';
        $year = now()->format('Y');
        $month = now()->format('m');
        
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . $year . $month . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad((string) $newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        if (!auth()->user()->can('view.appointments')) {
            abort(403, 'You do not have permission to view appointments.');
        }
        
        $patients = [];
        if (!empty(trim($this->patientSearch))) {
            $searchTerm = '%'.trim($this->patientSearch).'%';
            $patients = Patient::where(function ($q) use ($searchTerm) {
                    $q->where('full_name', 'like', $searchTerm)
                      ->orWhere('id_number', 'like', $searchTerm)
                      ->orWhere('phone', 'like', $searchTerm)
                      ->orWhere('phone_secondary', 'like', $searchTerm);
                })
                ->limit(10)
                ->get();
        }

        $query = Appointment::with(['patient', 'doctor', 'procedure', 'creator', 'branch']);
        
        // Filter by branch if user is not admin
        if (!auth()->user()->isAdmin() && auth()->user()->branch_id) {
            $query->where('branch_id', auth()->user()->branch_id);
        }
        
        // Search functionality
        if (!empty(trim($this->search))) {
            $searchTerm = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('patient', function ($patientQuery) use ($searchTerm) {
                    $patientQuery->where('full_name', 'like', $searchTerm)
                                 ->orWhere('id_number', 'like', $searchTerm)
                                 ->orWhere('phone', 'like', $searchTerm);
                })
                ->orWhereHas('doctor', function ($doctorQuery) use ($searchTerm) {
                    $doctorQuery->where('name', 'like', $searchTerm);
                })
                ->orWhere('visit_type', 'like', $searchTerm);
            });
        }

        // Visit Type Filter
        if (!empty($this->visitTypeFilter)) {
            $query->where('visit_type', $this->visitTypeFilter);
        }

        // Visit Stage Filter
        if (!empty($this->visitStageFilter)) {
            $query->where('visit_stage', $this->visitStageFilter);
        }

        // Doctor Filter
        if (!empty($this->doctorFilter)) {
            $query->where('doctor_id', $this->doctorFilter);
        }

        // Date Filter
        if (!empty($this->dateFilter)) {
            $today = \Carbon\Carbon::today();
            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('appointment_date', $today);
                    break;
                case 'upcoming':
                    $query->whereDate('appointment_date', '>=', $today)
                          ->where('visit_stage', '!=', 'completed')
                          ->where('visit_stage', '!=', 'cancelled');
                    break;
                case 'past':
                    $query->whereDate('appointment_date', '<', $today)
                          ->orWhere(function ($q) {
                              $q->whereIn('visit_stage', ['completed', 'cancelled']);
                          });
                    break;
                case 'this_week':
                    $query->whereBetween('appointment_date', [
                        $today->copy()->startOfWeek(),
                        $today->copy()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('appointment_date', $today->month)
                          ->whereYear('appointment_date', $today->year);
                    break;
                case 'date_range':
                    // Filter by date range (from - to)
                    if (!empty($this->dateFrom)) {
                        $query->whereDate('appointment_date', '>=', $this->dateFrom);
                    }
                    if (!empty($this->dateTo)) {
                        $query->whereDate('appointment_date', '<=', $this->dateTo);
                    }
                    break;
            }
        }
        
        $perPageValue = $this->perPage === -1 ? 10000 : $this->perPage;
        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate($perPageValue);

        return view('livewire.appointment-manager', [
            'patients' => $patients,
            'appointments' => $appointments,
            'doctors' => Doctor::orderBy('name')->get(),
        ])->layout('components.layouts.app');
    }
}
