<?php

namespace App\DTO\Lead;

class CreateLeadDTO
{
    public function __construct(
        public readonly ?int $workspaceId,
        public readonly int $ownerId,
        public readonly string $name,
        public readonly string $phone,
        public readonly ?int $leadTypeId,
        public readonly ?float $dealValue,
        public readonly array $meta
    ) {}

    public static function fromRequest(array $data, int $ownerId): self
    {
        return new self(
            workspaceId: $data['workspace_id'],
            ownerId: $ownerId,
            name: $data['name'],
            phone: $data['phone'],
            leadTypeId: $data['lead_type_id'] ?? null,
            dealValue: $data['deal_value'] ?? null,
            meta: $data['meta'] ?? []
        );
    }
}
