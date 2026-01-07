<?php

namespace App\Services;

use App\Repositories\Contracts\LeadRepositoryInterface;
use App\DTO\Lead\CreateLeadDTO;
use App\DTO\Lead\LeadFilterDTO;

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
}