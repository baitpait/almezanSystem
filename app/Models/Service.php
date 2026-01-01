<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'base_price',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // علاقة مع تفاصيل الفواتير
    public function invoiceServices(): HasMany
    {
        return $this->hasMany(InvoiceService::class);
    }

    // Scope للخدمات النشطة
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

}
