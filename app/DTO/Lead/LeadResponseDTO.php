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
            'lead_type' => $lead->lead_type,
            'deal_value' => $lead->deal_value,
            'meta' => $lead->meta->pluck('value', 'key'),
            'created_at' => $lead->created_at,
        ];
    }
}
