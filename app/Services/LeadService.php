<?php

namespace App\Services;

use App\Repositories\Contracts\LeadRepositoryInterface;
use App\DTO\Lead\CreateLeadDTO;

class LeadService
{
    public function __construct(
        private LeadRepositoryInterface $repository
    ) {}

    public function create(CreateLeadDTO $dto): int
    {
        return $this->repository->create($dto);
    }

    public function list(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }
}