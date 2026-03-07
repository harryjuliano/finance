<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'partner_group_id',
        'code',
        'type',
        'name',
        'legal_name',
        'tax_number',
        'address',
        'phone',
        'email',
        'contact_person',
        'status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
