<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'parent_id',
        'code',
        'name',
        'flow_type',
        'default_gl_account_id',
        'requires_attachment',
        'requires_partner',
        'status',
    ];

    protected $casts = [
        'requires_attachment' => 'boolean',
        'requires_partner' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
