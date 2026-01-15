<?php

namespace App\DTO\Lead;

class UpdateLeadDTO
{
    public function __construct(
        public ?string $name,
        public ?string $phone,
        public ?int $leadTypeId,
        public ?float $dealValue,
        public array $meta = []
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            phone: $data['phone'] ?? null,
            leadTypeId: $data['lead_type_id'] ?? null,
            dealValue: $data['deal_value'] ?? null,
            meta: $data['meta'] ?? []
        );
    }
}

