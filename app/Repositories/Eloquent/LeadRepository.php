<?php

namespace App\Repositories\Eloquent;

use App\Models\Lead;
use App\Repositories\Contracts\LeadRepositoryInterface;
use App\DTO\Lead\CreateLeadDTO;
use Illuminate\Support\Collection;
use App\DTO\Lead\LeadFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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


    public function getAll(LeadFilterDTO $filters): LengthAwarePaginator
    {
        $query = Lead::with('meta');

        if ($filters->leadType) {
            $query->where('lead_type', $filters->leadType);
        }

        if ($filters->minAmount !== null) {
            $query->where('deal_value', '>=', $filters->minAmount);
        }

        if ($filters->maxAmount !== null) {
            $query->where('deal_value', '<=', $filters->maxAmount);
        }

        // Sorting (safe allowlist)
        $allowedSorts = ['created_at', 'deal_value', 'name'];
        $sortBy = in_array($filters->sortBy, $allowedSorts)
            ? $filters->sortBy
            : 'created_at';

        $query->orderBy($sortBy, $filters->sortOrder);

        return $query->paginate(
            perPage: $filters->perPage,
            page: $filters->page
        );
    }

}
