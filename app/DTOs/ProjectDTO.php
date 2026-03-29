<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class ProjectDTO
{
    public function __construct(
        public readonly int $company_id,
        public readonly string $name,
        public readonly string $code,
        public readonly ?string $description,
        public readonly string $status,
        public readonly string $priority,
        public readonly ?string $starts_at,
        public readonly ?string $ends_at,
        public readonly ?int $manager_id,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            company_id: (int) $request->input('company_id'),
            name: $request->input('name'),
            code: $request->input('code'),
            description: $request->input('description'),
            status: $request->input('status', 'not_started'),
            priority: $request->input('priority', 'low'),
            starts_at: $request->input('starts_at'),
            ends_at: $request->input('ends_at'),
            manager_id: $request->filled('manager_id') ? (int) $request->input('manager_id') : null,
        );
    }
}
