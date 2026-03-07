<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentRequestAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_request_item_id',
        'cost_center_id',
        'project_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(PaymentRequestItem::class, 'payment_request_item_id');
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
