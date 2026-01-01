<div class="container mx-auto p-4">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
                <h1>Invoice Management</h1>
                <p>Manage patient invoices and billing</p>
        </div>
            <button class="btn-add btn-action flex items-center gap-2" wire:click="create">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New Invoice
        </button>
        </div>
    </div>
    
    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="alert alert-success mb-6 shadow-lg animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm">{{ session('message') }}</span>
        </div>
    @endif
    
    {{-- Search Container --}}
    <div class="search-container">
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
            <div class="search-input-wrapper flex-1 w-full md:w-auto order-1 md:order-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search by invoice number, patient name...">
                </div>
            <div class="flex items-center gap-2 flex-shrink-0 order-2 md:order-2">
                <select class="form-select" wire:model.live="statusFilter" style="min-width: 150px;">
                        <option value="">All Statuses</option>
                        <option value="draft">Draft</option>
                        <option value="pending">Pending</option>
                        <option value="partial">Partial</option>
                        <option value="paid">Paid</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                <select class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" wire:model.live="perPage" style="width: 90px;" title="Results per page">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="-1">All</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Invoices Table --}}
    <div class="data-table-container overflow-x-auto">
        <table class="data-table min-w-full">
                    <thead>
                <tr>
                    <th class="sticky left-0 z-10 bg-gray-50">Invoice #</th>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Paid</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th class="text-right sticky right-0 z-10 bg-gray-50">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                <tr>
                    <td class="sticky left-0 z-10 bg-white font-mono text-xs text-gray-800 font-semibold">{{ $invoice->invoice_number }}</td>
                            <td>
                        <div class="font-medium text-gray-900">{{ $invoice->patient->full_name }}</div>
                                    @if($invoice->patient->id_number)
                            <div class="text-xs text-gray-600 mt-0.5">{{ $invoice->patient->id_number }}</div>
                                    @endif
                            </td>
                    <td class="font-medium text-gray-800">{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                    <td class="font-semibold text-gray-900">{{ number_format($invoice->total_amount, 2) }}</td>
                    <td class="font-semibold text-green-600">{{ number_format($invoice->paid_amount, 2) }}</td>
                    <td class="font-semibold text-orange-600">{{ number_format($invoice->remaining_amount, 2) }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'partial' => 'bg-blue-100 text-blue-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                            $color = $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                        <span class="badge-status {{ $color }}">{{ ucfirst($invoice->status) }}</span>
                            </td>
                    <td class="sticky right-0 z-10 bg-white">
                        <div class="simple-dropdown-menu">
                            <button class="dropdown-trigger" type="button" onclick="toggleDropdown(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                            <div class="dropdown-menu">
                                <button class="btn-edit btn-action" wire:click="edit({{ $invoice->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                <button class="btn-delete btn-action" onclick="if(confirm('Are you sure you want to delete this invoice?')) { @this.delete({{ $invoice->id }}) }">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                            </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" class="empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                            <h3>No invoices found</h3>
                            <p>Get started by creating a new invoice</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

    {{-- Pagination --}}
    @if($invoices->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-buttons">
                {{ $invoices->links() }}
        </div>
    </div>
    @endif

    {{-- Modal for Create/Edit Invoice --}}
    @if($showModal)
    <div class="modal-overlay" wire:click="resetForm">
        <div class="modal-container max-w-4xl max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="modal-header">
                <h2>{{ $editingId ? 'Edit Invoice' : 'Create New Invoice' }}</h2>
                <button type="button" class="modal-close" wire:click="resetForm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
            
            <form wire:submit.prevent="save" autocomplete="off">
                {{-- Patient Selection --}}
                <div class="form-group mb-6">
                    <label class="form-label">Patient <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" 
                               class="form-input" 
                               placeholder="Search patient by name, ID, or phone..."
                               wire:model.live.debounce.300ms="patientSearch"
                               autocomplete="off">
                        @if($patientSearch && !$selectedPatientId)
                            <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                @forelse($patients as $patient)
                                    <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0" 
                                         wire:click="selectPatient({{ $patient->id }})"
                                         wire:key="patient-{{ $patient->id }}">
                                        <div class="font-semibold text-gray-900">{{ $patient->full_name }}</div>
                                        @if($patient->id_number)
                                            <div class="text-xs text-gray-600">ID: {{ $patient->id_number }}</div>
                                        @endif
                                        @if($patient->phone)
                                            <div class="text-xs text-gray-600">Phone: {{ $patient->phone }}</div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="p-3 text-gray-500 text-center">No patients found</div>
                                @endforelse
                            </div>
                        @endif
                    </div>
                    @error('form.patient_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    
                    @if($selectedPatientData)
                        <div class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $selectedPatientData['full_name'] }}</div>
                                    @if($selectedPatientData['id_number'])
                                        <div class="text-xs text-gray-600">ID: {{ $selectedPatientData['id_number'] }}</div>
                                    @endif
                                    @if($selectedPatientData['phone'])
                                        <div class="text-xs text-gray-600">Phone: {{ $selectedPatientData['phone'] }}</div>
                                    @endif
                                </div>
                                <button type="button" class="text-xs text-gray-600 hover:text-gray-900" wire:click="$set('selectedPatientId', null); $set('patientSearch', ''); $set('form.patient_id', null);">
                                    Clear
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    {{-- Appointment Selection --}}
                    <div class="form-group">
                        <label class="form-label">Appointment</label>
                        <select class="form-select" wire:model.defer="form.appointment_id">
                            <option value="">Select Appointment (Optional)</option>
                            @foreach($appointments as $appointment)
                                <option value="{{ $appointment->id }}">
                                    {{ $appointment->appointment_date->format('d-m-Y') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->appointment_time)->format('h:i A') }} 
                                    ({{ $appointment->doctor->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('form.appointment_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Doctor Selection --}}
                    <div class="form-group">
                        <label class="form-label">Doctor</label>
                        <select class="form-select" wire:model.defer="form.doctor_id">
                            <option value="">Select Doctor (Optional)</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                        @error('form.doctor_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    {{-- Invoice Date --}}
                    <div class="form-group">
                        <label class="form-label">Invoice Date <span class="text-red-500">*</span></label>
                        <input type="date" class="form-input" wire:model.defer="form.invoice_date" required>
                        @error('form.invoice_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Due Date --}}
                    <div class="form-group">
                        <label class="form-label">Due Date</label>
                        <input type="date" class="form-input" wire:model.defer="form.due_date">
                        @error('form.due_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Amounts Section --}}
                <div class="card-modern mb-6">
                    <div class="card-header">
                        <h3>Amounts</h3>
                    </div>
                    <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label class="form-label">Subtotal</label>
                                <input type="number" step="0.01" class="form-input" 
                                   wire:model.live="form.subtotal" placeholder="0.00">
                                @error('form.subtotal') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                            <div class="form-group">
                                <label class="form-label">Discount</label>
                                <input type="number" step="0.01" class="form-input" 
                                   wire:model.live="form.discount" placeholder="0.00">
                                @error('form.discount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                            <div class="form-group">
                                <label class="form-label">Tax</label>
                                <input type="number" step="0.01" class="form-input" 
                                   wire:model.live="form.tax" placeholder="0.00">
                                @error('form.tax') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="form-group">
                                <label class="form-label">Total Amount</label>
                                <input type="text" class="form-input font-bold text-lg bg-gray-50" 
                                   value="{{ number_format($form['total_amount'], 2) }}" readonly>
                        </div>
                            <div class="form-group">
                                <label class="form-label">Paid Amount</label>
                                <input type="number" step="0.01" class="form-input" 
                                   wire:model.live="form.paid_amount" placeholder="0.00">
                                @error('form.paid_amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                            <div class="form-group">
                                <label class="form-label">Remaining Amount</label>
                                <input type="text" class="form-input font-bold text-lg bg-orange-50 text-orange-600" 
                                   value="{{ number_format($form['remaining_amount'], 2) }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    {{-- Status --}}
                    <div class="form-group">
                        <label class="form-label">Status <span class="text-red-500">*</span></label>
                        <select class="form-select" wire:model.defer="form.status">
                            <option value="draft">Draft</option>
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('form.status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Payment Method --}}
                    <div class="form-group">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" wire:model.defer="form.payment_method">
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                        @error('form.payment_method') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Notes --}}
                <div class="form-group mb-4">
                    <label class="form-label">Notes</label>
                    <textarea class="form-input" 
                              wire:model.defer="form.notes" 
                              rows="3" 
                              placeholder="Additional notes..."></textarea>
                    @error('form.notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Modal Actions --}}
                <div class="modal-footer">
                    <button type="button" class="btn-cancel btn-action" wire:click="resetForm">Cancel</button>
                    <button type="submit" class="btn-add btn-action">Save Invoice</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    @endif
</div>
