<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Receipt - {{ $invoice->invoice_number ?? 'Invoice' }}</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; color: #111; }
        body { margin: 24px; background: #f7f9fb; }
        .receipt { max-width: 800px; margin: 0 auto; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 24px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .title { font-size: 22px; font-weight: 700; }
        .meta { font-size: 14px; color: #555; line-height: 1.4; }
        .section { margin-bottom: 20px; }
        .section h3 { margin: 0 0 10px; font-size: 16px; text-transform: uppercase; letter-spacing: 0.5px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; }
        .box { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; background: #f9fafb; }
        .label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .value { font-size: 15px; font-weight: 600; color: #111827; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { padding: 10px; border: 1px solid #e5e7eb; text-align: left; }
        th { background: #f3f4f6; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        tfoot td { font-weight: 700; }
        .signatures { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 24px; }
        .sig-box { border: 1px dashed #9ca3af; border-radius: 8px; padding: 14px; height: 120px; display: flex; flex-direction: column; justify-content: space-between; }
        .sig-title { font-size: 13px; font-weight: 700; color: #111827; }
        .hint { font-size: 11px; color: #6b7280; }
        .stamp { border: 1px dashed #9ca3af; border-radius: 8px; height: 120px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #374151; background: #f9fafb; }
        .actions { display: flex; gap: 8px; margin: 0 0 16px; }
        .btn-screen { padding: 8px 14px; background:#111827; color:#fff; border:none; border-radius:6px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
        .btn-secondary { background:#e5e7eb; color:#111827; }
        @media print {
            body { margin: 0; }
            .receipt { border: none; border-radius: 0; box-shadow: none; }
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <div class="actions">
        <button class="btn-screen" onclick="window.print()">
            Print
        </button>
        <a href="{{ route('invoices.index') }}" class="btn-screen btn-secondary">
            Back to Invoices
        </a>
    </div>
    <div class="receipt">
        <div class="header" style="align-items: flex-start;">
            <div style="display:flex; gap:12px; align-items:center;">
                @php
                    $logoPng = public_path('images/logo.png');
                    $logoSvg = public_path('images/logo.svg');
                    $logoJpg = public_path('images/logo.jpg');
                    $logo = null;
                    if (file_exists($logoPng)) {
                        $logo = asset('images/logo.png');
                    } elseif (file_exists($logoSvg)) {
                        $logo = asset('images/logo.svg');
                    } elseif (file_exists($logoJpg)) {
                        $logo = asset('images/logo.jpg');
                    }
                @endphp
                @if($logo)
                    <div style="width:64px; height:64px; border:1px solid #e5e7eb; border-radius:12px; padding:8px; display:flex; align-items:center; justify-content:center; background:#fff;">
                        <img src="{{ $logo }}" alt="Clinic Logo" style="max-width:100%; max-height:100%; object-fit:contain;">
                    </div>
                @endif
                <div>
                    <div class="title">{{ $invoice->branch->name ?? config('app.name', 'Clinic') }}</div>
                    <div class="meta">
                        {{ $invoice->branch->address ?? '' }}<br>
                        {{ $invoice->branch->phone ?? '' }}
                    </div>
                </div>
            </div>
            <div class="meta" style="text-align:right;">
                <div class="title" style="font-size:18px;">Invoice Receipt</div>
                <div>
                    Invoice #: {{ $invoice->invoice_number ?? '—' }}<br>
                    Date: {{ optional($invoice->invoice_date)->format('Y-m-d') ?? now()->format('Y-m-d') }}
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Patient</h3>
            <div class="grid">
                <div class="box">
                    <div class="label">Name</div>
                    <div class="value">{{ $invoice->patient->full_name ?? '—' }}</div>
                </div>
                <div class="box">
                    <div class="label">ID / Phone</div>
                    <div class="value">
                        {{ $invoice->patient->id_number ?? '—' }}
                        @if(!empty($invoice->patient->phone))
                            &nbsp;·&nbsp; {{ $invoice->patient->phone }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Details</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width:50%;">Service</th>
                        <th style="width:25%;">Amount</th>
                        <th style="width:25%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $invoice->service->name ?? 'Not specified' }}</td>
                        <td>{{ number_format((float) $invoice->total_amount, 2) }} ₪</td>
                        <td>{{ ucfirst($invoice->status ?? 'pending') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Paid</td>
                        <td colspan="2">{{ number_format((float) $invoice->paid_amount, 2) }} ₪</td>
                    </tr>
                    <tr>
                        <td>Remaining</td>
                        <td colspan="2">{{ number_format((float) $invoice->remaining_amount, 2) }} ₪</td>
                    </tr>
                    <tr>
                        <td>Payment Method</td>
                        <td colspan="2">{{ ucfirst($invoice->payment_method ?? 'cash') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="section">
            <h3>Signatures & Stamp</h3>
            <div class="signatures">
                <div class="sig-box">
                    <div class="sig-title">Cashier / Admin Signature</div>
                    <div class="hint">Sign here</div>
                </div>
                <div class="stamp">
                    Clinic Stamp
                </div>
            </div>
        </div>
    </div>
</body>
</html>

