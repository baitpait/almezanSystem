<div class="container mx-auto p-4">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1>Invoice Management</h1>
                <p>Manage patient invoices and billing</p>
            </div>
            @can('create.invoices')
            <button class="btn-add btn-action flex items-center gap-2" wire:click="create">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Invoice
            </button>
            @endcan
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
        <div class="flex flex-col gap-4">
            {{-- Search, Status, and Per Page Row --}}
            <div>
                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="form-label">Search</label>
                        <div class="search-input-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="Search by invoice number or patient name...">
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <label class="form-label">Status</label>
                        <select class="form-select" wire:model.live="statusFilter" style="width: 150px; min-width: 150px;">
                            <option value="">All Statuses</option>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="draft">Draft</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="flex-shrink-0">
                        <label class="form-label">Per Page</label>
                        <select class="form-select" wire:model.live="perPage" style="width: 80px; min-width: 80px;">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="-1">All</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Invoices Table --}}
    <div class="data-table-container overflow-x-auto">
        <table class="data-table min-w-full">
            <thead>
            <tr>
                    <th class="sticky left-0 z-10 bg-gray-50">Patient</th>
                <th>Service</th>
                <th>Date</th>
                    <th>Amounts</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th class="text-right sticky right-0 z-10 bg-gray-50">Actions</th>
            </tr>
            </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                <tr>
                    <td class="sticky left-0 z-10 bg-white">
                        <div class="font-medium text-gray-900">{{ $invoice->patient->full_name }}</div>
                            </td>
                    <td>
                        <div class="text-sm text-gray-700">
                            @if($invoice->service)
                                {{ $invoice->service->name }}
                            @else
                                <span class="text-gray-400">No service</span>
                            @endif
                        </div>
                    </td>
                    <td class="font-medium text-gray-800">{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                    <td>
                        <div class="text-sm text-gray-900 font-semibold">Total: ₪{{ number_format($invoice->total_amount, 2) }}</div>
                        <div class="text-sm text-green-600">Paid: ₪{{ number_format($invoice->paid_amount, 2) }}</div>
                        <div class="text-sm text-orange-600">Remaining: ₪{{ number_format($invoice->remaining_amount, 2) }}</div>
                    </td>
                    <td>
                        @php
                            $paymentMethods = [
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'bank_transfer' => 'Bank Transfer',
                                'cheque' => 'Cheque',
                                'other' => 'Other',
                            ];
                            $paymentLabel = $paymentMethods[$invoice->payment_method] ?? ucfirst($invoice->payment_method);
                        @endphp
                        <span class="badge badge-info">{{ $paymentLabel }}</span>
                    </td>
                    <td>
                                @php
                                    $statusLabels = [
                                'draft' => 'Draft',
                                'pending' => 'Pending',
                                'partial' => 'Partial',
                                'paid' => 'Paid',
                                'cancelled' => 'Cancelled',
                                    ];
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'partial' => 'bg-blue-100 text-blue-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                            $label = $statusLabels[$invoice->status] ?? ucfirst($invoice->status);
                                    $color = $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                        <span class="badge-status {{ $color }}">{{ $label }}</span>
                            </td>
                    <td class="sticky right-0 z-10 bg-white" style="display: flex; justify-content: flex-end; align-items: center;">
                        <div class="relative inline-block" data-dropdown-container="{{ $invoice->id }}">
                            <button type="button"
                                    class="btn btn-sm btn-ghost"
                                    onclick="toggleSimpleDropdown({{ $invoice->id }}, event)"
                                    data-dropdown-trigger="{{ $invoice->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <div class="simple-dropdown-menu"
                                 id="dropdown-menu-{{ $invoice->id }}"
                                 data-dropdown-menu="{{ $invoice->id }}"
                                 data-original-parent="{{ $invoice->id }}"
                                 style="display: none;">
                                <ul class="dropdown-menu-list">
                                    @can('update.invoices')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-edit" wire:click="edit({{ $invoice->id }})" onclick="closeSimpleDropdown({{ $invoice->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                            <span>Edit</span>
                                    </button>
                                    </li>
                                    @endcan
                                    @can('print.invoices')
                                    <li>
                                        <a href="{{ route('invoices.print', $invoice) }}" class="dropdown-menu-item" onclick="closeSimpleDropdown({{ $invoice->id }});">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                                            </svg>
                                            <span>Print Receipt</span>
                                        </a>
                                    </li>
                                    @endcan
                                    @can('delete.invoices')
                                    <li>
                                        <button type="button" class="dropdown-menu-item dropdown-menu-item-delete"
                                                onclick="if(confirm('Are you sure you want to delete this invoice?')) { @this.delete({{ $invoice->id }}) } closeSimpleDropdown({{ $invoice->id }});">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                            <span>Delete</span>
                                    </button>
                                    </li>
                                    @endcan
                                </ul>
                            </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
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
                               placeholder="Search by name, ID, or phone..."
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
                                            <div class="text-xs text-gray-600">رقم الهوية: {{ $patient->id_number }}</div>
                                        @endif
                                        @if($patient->phone)
                                            <div class="text-xs text-gray-600">الهاتف: {{ $patient->phone }}</div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="p-3 text-gray-500 text-center">لا توجد مرضى</div>
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    {{-- Invoice Date --}}
                    <div class="form-group">
                        <label class="form-label">Invoice Date <span class="text-red-500">*</span></label>
                        <input type="date" class="form-input" wire:model.defer="form.invoice_date" required>
                        @error('form.invoice_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Service Selection --}}
                    <div class="form-group">
                        <label class="form-label">Service</label>
                        <select class="form-select" wire:model.live="form.service_id">
                            <option value="">Select a service (Optional)</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">
                                    {{ $service->name }}
                                    @if($service->category)
                                        ({{ $service->category }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('form.service_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Amounts Section --}}
                <div class="card-modern mb-6">
                    <div class="card-header">
                        <h3>Amounts</h3>
                    </div>
                    <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Amount (₪)</label>
                                <input type="number" step="0.01" class="form-input"
                                   wire:model.live="form.subtotal" placeholder="0.00">
                                @error('form.subtotal') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                            <div class="form-group">
                                <label class="form-label">Discount ($)</label>
                                <input type="number" step="0.01" class="form-input"
                                   wire:model.live="form.discount" placeholder="0.00">
                                @error('form.discount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="form-group">
                                <label class="form-label">Total Amount</label>
                                <input type="text" class="form-input font-bold text-lg bg-gray-50"
                                   value="₪{{ number_format($form['total_amount'], 2) }}" readonly>
                        </div>
                            <div class="form-group">
                                <label class="form-label">Paid Amount</label>
                                <input type="number" step="0.01" class="form-input"
                                   wire:model.live="form.paid_amount" placeholder="0.00">
                                @error('form.paid_amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                @php
                                    $overPaid = (float) ($form['paid_amount'] ?? 0) > (float) ($form['total_amount'] ?? 0);
                                @endphp
                                @if($overPaid)
                                    <div class="mt-1 text-xs font-semibold text-red-600">
                                        Paid amount exceeds total due.
                                    </div>
                                @endif
                        </div>
                            <div class="form-group">
                                <label class="form-label">Remaining Amount</label>
                                <input type="text" class="form-input font-bold text-lg bg-orange-50 text-orange-600"
                                   value="₪{{ number_format($form['remaining_amount'], 2) }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    {{-- Payment Method --}}
                    <div class="form-group">
                        <label class="form-label">Payment Method <span class="text-red-500">*</span></label>
                        <select class="form-select" wire:model.defer="form.payment_method">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                        @error('form.payment_method') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="form-group">
                        <label class="form-label">Status <span class="text-red-500">*</span></label>
                        <select class="form-select" wire:model.defer="form.status">
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="draft">Draft</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('form.status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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
                    <button type="submit" class="btn-add btn-action">{{ $editingId ? 'Update Invoice' : 'Save Invoice' }}</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    @endif
</div>
