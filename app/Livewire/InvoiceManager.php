<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Branch;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class InvoiceManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public string $statusFilter = '';
    public int $perPage = 10;
    public $editingId = null;
    public $showModal = false;
    public $patientSearch = '';
    public $selectedPatientId = null;
    public $selectedPatientData = null;
    public array $form = [
        'patient_id' => null,
        'appointment_id' => null,
        'doctor_id' => null,
        'branch_id' => null,
        'invoice_date' => '',
        'due_date' => '',
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
            'form.appointment_id' => 'nullable|exists:appointments,id',
            'form.doctor_id' => 'nullable|exists:doctors,id',
            'form.branch_id' => 'nullable|exists:branches,id',
            'form.invoice_date' => 'required|date',
            'form.due_date' => 'nullable|date|after_or_equal:form.invoice_date',
            'form.subtotal' => 'required|numeric|min:0',
            'form.discount' => 'required|numeric|min:0',
            'form.tax' => 'required|numeric|min:0',
            'form.total_amount' => 'required|numeric|min:0',
            'form.paid_amount' => 'required|numeric|min:0',
            'form.remaining_amount' => 'required|numeric|min:0',
            'form.status' => 'required|in:draft,pending,partial,paid,cancelled',
            'form.payment_method' => 'nullable|in:cash,card,bank_transfer,cheque,other',
            'form.notes' => 'nullable|string|max:1000',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function updatingPatientSearch(): void
    {
        $this->selectedPatientId = null;
        $this->selectedPatientData = null;
    }

    public function selectPatient($patientId): void
    {
        $patient = Patient::findOrFail($patientId);
        $this->selectedPatientId = $patientId;
        $this->selectedPatientData = [
            'id' => $patient->id,
            'full_name' => $patient->full_name,
            'id_number' => $patient->id_number,
            'phone' => $patient->phone,
        ];
        $this->form['patient_id'] = $patientId;
        $this->patientSearch = $patient->full_name;
    }

    public function calculateTotal(): void
    {
        $subtotal = (float) ($this->form['subtotal'] ?? 0);
        $discount = (float) ($this->form['discount'] ?? 0);
        $tax = (float) ($this->form['tax'] ?? 0);

        $total = $subtotal - $discount + $tax;
        $this->form['total_amount'] = number_format($total, 2, '.', '');

        $paid = (float) ($this->form['paid_amount'] ?? 0);
        $remaining = max(0, $total - $paid);
        $this->form['remaining_amount'] = number_format($remaining, 2, '.', '');

        // Auto-update status based on payment
        if ($remaining <= 0 && $paid > 0) {
            $this->form['status'] = 'paid';
        } elseif ($paid > 0 && $remaining > 0) {
            $this->form['status'] = 'partial';
        } elseif ($this->form['status'] === 'paid' && $remaining > 0) {
            $this->form['status'] = 'partial';
        }
    }

    public function updatedFormSubtotal(): void
    {
        $this->calculateTotal();
    }

    public function updatedFormDiscount(): void
    {
        $this->calculateTotal();
    }

    public function updatedFormTax(): void
    {
        $this->calculateTotal();
    }

    public function updatedFormPaidAmount(): void
    {
        $this->calculateTotal();
    }

    public function resetForm(): void
    {
        $this->form = [
            'patient_id' => null,
            'appointment_id' => null,
            'doctor_id' => null,
            'branch_id' => auth()->user()?->branch_id,
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => '',
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
        $this->selectedPatientId = null;
        $this->selectedPatientData = null;
        $this->patientSearch = '';
        $this->editingId = null;
        $this->showModal = false;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id): void
    {
        $invoice = Invoice::with(['patient', 'appointment', 'doctor'])->findOrFail($id);
        $this->editingId = $invoice->id;
        $this->form = [
            'patient_id' => $invoice->patient_id,
            'appointment_id' => $invoice->appointment_id,
            'doctor_id' => $invoice->doctor_id,
            'branch_id' => $invoice->branch_id,
            'invoice_date' => $invoice->invoice_date->format('Y-m-d'),
            'due_date' => $invoice->due_date?->format('Y-m-d'),
            'subtotal' => number_format($invoice->subtotal, 2, '.', ''),
            'discount' => number_format($invoice->discount, 2, '.', ''),
            'tax' => number_format($invoice->tax, 2, '.', ''),
            'total_amount' => number_format($invoice->total_amount, 2, '.', ''),
            'paid_amount' => number_format($invoice->paid_amount, 2, '.', ''),
            'remaining_amount' => number_format($invoice->remaining_amount, 2, '.', ''),
            'status' => $invoice->status,
            'payment_method' => $invoice->payment_method,
            'notes' => $invoice->notes,
        ];

        if ($invoice->patient) {
            $this->selectedPatientId = $invoice->patient_id;
            $this->selectedPatientData = [
                'id' => $invoice->patient->id,
                'full_name' => $invoice->patient->full_name,
                'id_number' => $invoice->patient->id_number,
                'phone' => $invoice->patient->phone,
            ];
            $this->patientSearch = $invoice->patient->full_name;
        }

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = $this->form;
        $data['created_by'] = auth()->id() ?? null;

        // Generate invoice number if creating new invoice
        if (!$this->editingId) {
            $data['invoice_number'] = $this->generateInvoiceNumber();
        }

        // Convert string amounts to decimal
        $data['subtotal'] = (float) $data['subtotal'];
        $data['discount'] = (float) $data['discount'];
        $data['tax'] = (float) $data['tax'];
        $data['total_amount'] = (float) $data['total_amount'];
        $data['paid_amount'] = (float) $data['paid_amount'];
        $data['remaining_amount'] = (float) $data['remaining_amount'];

        if ($this->editingId) {
            $invoice = Invoice::findOrFail($this->editingId);
            $invoice->update($data);
            $message = 'Invoice updated successfully.';
        } else {
            Invoice::create($data);
            $message = 'Invoice created successfully.';
        }

        session()->flash('message', $message);
        $this->resetForm();
        $this->dispatch('invoice-saved');
    }

    public function delete($id): void
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        session()->flash('message', 'Invoice deleted successfully.');
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
        $user = auth()->user();
        $branchId = $user?->branch_id;

        $query = Invoice::with(['patient', 'doctor', 'appointment', 'branch'])
            ->when($branchId, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('invoice_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('patient', function ($q) {
                            $q->where('full_name', 'like', '%' . $this->search . '%')
                                ->orWhere('id_number', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->orderBy('invoice_date', 'desc')
            ->orderBy('created_at', 'desc');

        $perPageValue = $this->perPage === -1 ? 10000 : $this->perPage;
        $invoices = $query->paginate($perPageValue);

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

        $appointments = Appointment::when($this->selectedPatientId, function ($q) {
            $q->where('patient_id', $this->selectedPatientId);
        })
            ->when($branchId, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderBy('appointment_date', 'desc')
            ->limit(20)
            ->get();

        $branches = Branch::where('is_active', true)->get();

        return view('livewire.invoice-manager', [
            'invoices' => $invoices,
            'patients' => $patients,
            'doctors' => $doctors,
            'appointments' => $appointments,
            'branches' => $branches,
        ])->layout('components.layouts.app');
    }
}
