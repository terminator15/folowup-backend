<?php

namespace App\Repositories\Contracts;

use App\DTO\Lead\CreateLeadDTO;
use Illuminate\Support\Collection;
use App\DTO\Lead\LeadFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeadRepositoryInterface
{
    public function create(CreateLeadDTO $dto): int;

    public function getAll(LeadFilterDTO $dto): LengthAwarePaginator;
}
