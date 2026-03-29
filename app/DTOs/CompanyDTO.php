<?php

namespace App\DTOs;

class CompanyDTO
{
    public function __construct(
        public readonly string $cnpj,
        public readonly string $name,
        public readonly string $email,
        public readonly string $corporate_name,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            $request->input('cnpj'),
            $request->input('name'),
            $request->input('email'),
            $request->input('corporate_name'),
        );
    }
}
