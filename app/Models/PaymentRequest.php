<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'branch_id',
        'department_id',
        'cost_center_id',
        'project_id',
        'requester_id',
        'request_no',
        'request_date',
        'priority',
        'due_date',
        'currency_id',
        'exchange_rate',
        'total_amount',
        'tax_amount',
        'net_amount',
        'description',
        'status',
        'verification_status',
        'approval_status',
        'payment_status',
        'payment_method',
        'source_account',
        'document_complete_flag',
        'revision_no',
        'control_number',
        'rejected_reason',
        'cancelled_reason',
        'submitted_at',
        'verified_by',
        'verified_at',
        'approved_at',
        'created_by',
        'updated_by',
        'approved_by',
        'posted_by',
    ];

    protected $casts = [
        'request_date' => 'date',
        'due_date' => 'date',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'document_complete_flag' => 'boolean',
        'exchange_rate' => 'decimal:6',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PaymentRequestItem::class);
    }
}
