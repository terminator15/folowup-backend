<?php

namespace App\DTO\Lead;

class UpdateLeadDTO
{
    public function __construct(
        public ?string $name,
        public ?string $phone,
        public ?string $leadType,
        public ?int $dealValue,
        public array $meta = []
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            phone: $data['phone'] ?? null,
            leadType: $data['lead_type'] ?? null,
            dealValue: $data['deal_value'] ?? null,
            meta: $data['meta'] ?? []
        );
    }
}

