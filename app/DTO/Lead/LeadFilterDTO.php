<?php

namespace App\DTO\Lead;

class LeadFilterDTO
{

    // public readonly int $userId;

    public function __construct(
        public readonly ?int $workspaceId,
        public readonly int $userId,
        public readonly ?int $lead_type_id,
        public readonly ?float $minAmount,
        public readonly ?float $maxAmount,
        public readonly int $page,
        public readonly int $perPage,
        public readonly string $sortBy,
        public readonly string $sortOrder,
    ) {}

    public static function fromRequest(array $data, int $userId): self
    {
        return new self(
            workspaceId: $data['workspace_id'] ?? null,
            userId: $userId,
            lead_type_id: $data['lead_type_id'] ?? null,
            minAmount: isset($data['min_amount']) ? (float) $data['min_amount'] : null,
            maxAmount: isset($data['max_amount']) ? (float) $data['max_amount'] : null,
            page: isset($data['page']) ? max(1, (int) $data['page']) : 1,
            perPage: isset($data['per_page']) ? min(100, (int) $data['per_page']) : 20,
            sortBy: $data['sort_by'] ?? 'created_at',
            sortOrder: strtolower($data['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc',
        );
    }
}
