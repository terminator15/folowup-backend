<?php

namespace App\Repositories\Eloquent;

use App\Models\Lead;
use App\Repositories\Contracts\LeadRepositoryInterface;
use App\DTO\Lead\CreateLeadDTO;
use Illuminate\Support\Collection;

class LeadRepository implements LeadRepositoryInterface
{
    public function create(CreateLeadDTO $dto): int
    {
        $lead = Lead::create([
            'workspace_id' => $dto->workspaceId,
            'owner_id' => $dto->ownerId,
            'name' => $dto->name,
            'phone' => $dto->phone,
            'lead_type' => $dto->leadType,
            'deal_value' => $dto->dealValue,
        ]);

        foreach ($dto->meta as $key => $value) {
            $lead->meta()->create([
                'key' => $key,
                'value' => $value,
            ]);
        }

        return $lead->id;
    }

    public function getAll(array $filters = []): Collection
    {
        $query = Lead::with('meta');

        if (!empty($filters['lead_type'])) {
            $query->where('lead_type', $filters['lead_type']);
        }

        if (!empty($filters['min_amount'])) {
            $query->where('deal_value', '>=', $filters['min_amount']);
        }

        if (!empty($filters['max_amount'])) {
            $query->where('deal_value', '<=', $filters['max_amount']);
        }

        return $query->get();
    }

}
