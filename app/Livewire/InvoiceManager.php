<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Invoice;
use App\Models\Patient;
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
    public $filterPatientId = null;
    public $selectedPatientData = null;
    public array $form = [
        'patient_id' => null,
        'service_id' => null,
        'branch_id' => null,
        'invoice_date' => '',
        'subtotal' => '0.00',
        'discount' => '0.00',
        'total_amount' => '0.00',
        'paid_amount' => '',
        'remaining_amount' => '0.00',
        'status' => 'paid',
        'payment_method' => 'cash',
        'notes' => '',
    ];

    public function mount(?int $create = null, ?int $patient = null): void
    {
        // Allow query string fallback (e.g. /invoices?create=1&patient=ID or /invoices?patient=ID)
        $createFlag = $create ?? (int) request()->query('create', 0);
        $patientId = $patient ?? (int) request()->query('patient', 0);

        // If opened from an external action with create flag, open modal and preselect patient if provided
        if ($createFlag) {
            $this->resetForm();
            $this->showModal = true;
            $this->form['invoice_date'] = now()->format('Y-m-d');

            if ($patientId) {
                $patientModel = Patient::find($patientId);
                if ($patientModel) {
                    $this->selectPatient($patientModel->id);
                }
            }
        } elseif ($patientId) {
            // If patient is provided without create flag, filter invoices by patient
            $this->filterPatientId = $patientId;
        }
    }

    protected function rules(): array
    {
        return [
            'form.patient_id' => 'required|exists:patients,id',
            'form.service_id' => 'nullable|exists:services,id',
            'form.branch_id' => 'nullable|exists:branches,id',
            'form.invoice_date' => 'required|date',
            'form.subtotal' => 'required|numeric|min:0',
            'form.discount' => 'required|numeric|min:0',
            'form.total_amount' => 'required|numeric|min:0',
            'form.paid_amount' => 'required|numeric|min:0',
            'form.remaining_amount' => 'required|numeric|min:0',
            'form.status' => 'required|in:draft,pending,partial,paid,cancelled',
            'form.payment_method' => 'required|in:cash,card,bank_transfer,cheque,other',
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

        // No tax - simplified calculation
        $total = $subtotal - $discount;
        $this->form['total_amount'] = number_format($total, 2, '.', '');

        $paid = (float) ($this->form['paid_amount'] ?? 0);
        $remaining = max(0, $total - $paid);
        $this->form['remaining_amount'] = number_format($remaining, 2, '.', '');

        // Since all payments are cash on same day, status is usually 'paid'
        if ($remaining <= 0 && $paid > 0) {
            $this->form['status'] = 'paid';
        } elseif ($paid > 0 && $remaining > 0) {
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

    public function updatedFormServiceId(): void
    {
        if ($this->form['service_id']) {
            $service = \App\Models\Service::find($this->form['service_id']);
            if ($service) {
                $this->form['subtotal'] = number_format((float) $service->base_price, 2, '.', '');
                $this->calculateTotal();
            }
        }
    }

    public function resetForm(): void
    {
        $this->form = [
            'patient_id' => null,
            'service_id' => null,
            'branch_id' => auth()->user()?->branch_id,
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => '',
            'subtotal' => '0.00',
            'discount' => '0.00',
            'total_amount' => '0.00',
            'paid_amount' => '',
            'remaining_amount' => '0.00',
            'status' => 'paid',
            'payment_method' => 'cash',
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
        if (!auth()->user()->can('create.invoices')) {
            session()->flash('error', 'You do not have permission to create invoices.');
            return;
        }
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id): void
    {
        if (!auth()->user()->can('update.invoices')) {
            session()->flash('error', 'You do not have permission to update invoices.');
            return;
        }
        $invoice = Invoice::with(['patient'])->findOrFail($id);
        $this->editingId = $invoice->id;
        // Prefer invoice service_id; fallback to first invoice service (if exists)
        $invoiceServiceId = $invoice->service_id ?: optional($invoice->invoiceServices->first())->service_id;

        $this->form = [
            'patient_id' => $invoice->patient_id,
            'service_id' => $invoiceServiceId,
            'branch_id' => $invoice->branch_id,
            'invoice_date' => $invoice->invoice_date->format('Y-m-d'),
            'due_date' => $invoice->due_date?->format('Y-m-d'),
            'subtotal' => number_format((float) $invoice->subtotal, 2, '.', ''),
            'discount' => number_format((float) $invoice->discount, 2, '.', ''),
            'total_amount' => number_format((float) $invoice->total_amount, 2, '.', ''),
            'paid_amount' => number_format((float) $invoice->paid_amount, 2, '.', ''),
            'remaining_amount' => number_format((float) $invoice->remaining_amount, 2, '.', ''),
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
        if ($this->editingId) {
            if (!auth()->user()->can('update.invoices')) {
                session()->flash('error', 'You do not have permission to update invoices.');
                return;
            }
        } else {
            if (!auth()->user()->can('create.invoices')) {
                session()->flash('error', 'You do not have permission to create invoices.');
                return;
            }
        }
        
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
        $data['total_amount'] = (float) $data['total_amount'];
        $data['paid_amount'] = (float) $data['paid_amount'];
        $data['remaining_amount'] = (float) $data['remaining_amount'];
        $data['service_id'] = $data['service_id'] ? (int) $data['service_id'] : null;

        if ($this->editingId) {
            $invoice = Invoice::findOrFail($this->editingId);
            $invoice->update($data);
            $message = 'Invoice updated successfully.';
        } else {
            $invoice = Invoice::create($data);
            $message = 'Invoice created successfully.';
        }

        session()->flash('message', $message);
        $this->resetForm();
        $this->dispatch('invoice-saved');
    }

    public function delete($id): void
    {
        if (!auth()->user()->can('delete.invoices')) {
            session()->flash('error', 'You do not have permission to delete invoices.');
            return;
        }
        
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
        // Check permission - if not authorized, return empty view
        if (!auth()->user()->can('view.invoices')) {
            return view('livewire.unauthorized')->layout('components.layouts.app');
        }
        
        $user = auth()->user();
        $branchId = $user?->branch_id;

        $query = Invoice::with(['patient', 'branch', 'service', 'invoiceServices.service'])
            ->when($branchId, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($this->filterPatientId, function ($q) {
                $q->where('patient_id', $this->filterPatientId);
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

        $doctors = collect(); // No doctors needed

        $appointments = collect(); // No appointments needed

        $branches = Branch::where('is_active', true)->get();

        $services = \App\Models\Service::active()->orderBy('name')->get();

        return view('livewire.invoice-manager', [
            'invoices' => $invoices,
            'patients' => $patients,
            'services' => $services,
            'branches' => $branches,
        ])->layout('components.layouts.app');
    }
}
