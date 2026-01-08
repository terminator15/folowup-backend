<?php

namespace App\Services;

use App\Repositories\Contracts\LeadRepositoryInterface;
use App\DTO\Lead\CreateLeadDTO;
use App\DTO\Lead\LeadFilterDTO;
use App\DTO\Lead\UpdateLeadDTO;
use App\Models\Lead;

class LeadService
{
    public function __construct(
        private LeadRepositoryInterface $repository
    ) {}

    public function create(CreateLeadDTO $dto): int
    {
        return $this->repository->create($dto);
    }

    public function list(LeadFilterDTO $dto)
    {
        return $this->repository->getAll($dto);
    }

    public function update(Lead $lead, UpdateLeadDTO $dto): Lead
    {
        $lead->update(array_filter([
            'name' => $dto->name,
            'phone' => $dto->phone,
            'lead_type' => $dto->leadType,
            'deal_value' => $dto->dealValue,
        ]));

        if (!empty($dto->meta)) {
            foreach ($dto->meta as $key => $value) {
                $lead->meta()->updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return $lead->fresh('meta');
    }
}