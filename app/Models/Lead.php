<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'workspace_id',
        'owner_id',
        'name',
        'phone',
        'lead_type_id',
        'deal_value',
        'email',
        'status',
    ];

    public function meta()
    {
        return $this->hasMany(LeadMeta::class, 'lead_id');
    }

    public function leadType()
    {
        return $this->belongsTo(LeadType::class, 'lead_type_id');
    }
}
