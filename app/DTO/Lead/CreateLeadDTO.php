<?php

namespace App\DTO\Lead;

class CreateLeadDTO
{
    public function __construct(
        public readonly ?int $workspaceId,
        public readonly int $ownerId,
        public readonly string $name,
        public readonly string $phone,
        public readonly ?string $leadType,
        public readonly ?float $dealValue,
        public readonly array $meta
    ) {}

    public static function fromRequest(array $data, int $ownerId): self
    {
        return new self(
            workspaceId: $data['workspace_id'] ?? null,
            ownerId: $ownerId,
            name: $data['name'],
            phone: $data['phone'],
            leadType: $data['lead_type'] ?? null,
            dealValue: $data['deal_value'] ?? null,
            meta: $data['meta'] ?? []
        );
    }
}
