<?php

namespace App\DTO\Lead;

use App\Models\Lead;

class LeadResponseDTO
{
    public static function fromModel(Lead $lead): array
    {
        return [
            'id' => $lead->id,
            'workspace_id' => $lead->workspace_id,
            'owner_id' => $lead->owner_id,
            'name' => $lead->name,
            'phone' => $lead->phone,
            'lead_type_id' => $lead->lead_type_id,
            'deal_value' => $lead->deal_value,
            'meta' => $lead->meta->pluck('value', 'key'),
            'lead_type' => $lead->leadType ? [
                'name' => $lead->leadType->name,
                'is_active' => $lead->leadType->is_active,
                
            ] : null,
            'created_at' => $lead->created_at,
        ];
    }
}
