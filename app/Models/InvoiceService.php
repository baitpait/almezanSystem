<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceService extends Model
{
    protected $fillable = [
        'invoice_id',
        'service_id',
        'doctor_id',
        'quantity',
        'unit_price',
        'total_price',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // علاقة مع الفاتورة
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // علاقة مع الخدمة
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // علاقة مع الطبيب
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
