<?php

namespace App\Repositories\Eloquent;

use App\Models\Lead;
use App\Repositories\Contracts\LeadRepositoryInterface;
use App\DTO\Lead\CreateLeadDTO;
use Illuminate\Support\Collection;
use App\DTO\Lead\LeadFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User;

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


    public function getAll(LeadFilterDTO $dto): LengthAwarePaginator
    {
        $query = Lead::with('meta');

        if ($dto->workspaceId) {
        // Team workspace
        $query->where('workspace_id', $dto->workspaceId);
        } else {
            // My Personal
            $query->whereNull('workspace_id')
                ->where('owner_id', $dto->userId);
        }

        if ($dto->leadType) {
            $query->where('lead_type', $dto->leadType);
        }

        if ($dto->minAmount !== null) {
            $query->where('deal_value', '>=', $dto->minAmount);
        }

        if ($dto->maxAmount !== null) {
            $query->where('deal_value', '<=', $dto->maxAmount);
        }

        // Sorting (safe allowlist)
        $allowedSorts = ['created_at', 'deal_value', 'name'];
        $sortBy = in_array($dto->sortBy, $allowedSorts)
            ? $dto->sortBy
            : 'created_at';

        $query->orderBy($sortBy, $dto->sortOrder);

        return $query->paginate(
            perPage: $dto->perPage,
            page: $dto->page
        );
    }

}
