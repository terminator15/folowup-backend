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
        'lead_type',
        'deal_value',
    ];

    public function meta()
    {
        return $this->hasMany(LeadMeta::class, 'lead_id');
    }
}
