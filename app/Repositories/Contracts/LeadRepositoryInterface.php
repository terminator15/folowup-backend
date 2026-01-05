<?php

namespace App\Repositories\Contracts;

use App\DTO\Lead\CreateLeadDTO;
use Illuminate\Support\Collection;

interface LeadRepositoryInterface
{
    public function create(CreateLeadDTO $dto): int;

    public function getAll(array $filters = []): Collection;
}
