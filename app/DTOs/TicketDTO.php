<?php

namespace App\DTOs;

use Illuminate\Http\UploadedFile;

class TicketDTO
{
    public function __construct(
        public readonly int $project_id,
        public readonly string $title,
        public readonly string $description,
        public readonly ?string $environment = null,
        public readonly ?string $module = null,
        public readonly ?string $status = null,
        public readonly ?UploadedFile $attachment = null,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            project_id: (int) $request->input('project_id'),
            title: $request->input('title'),
            description: $request->input('description'),
            environment: $request->input('environment'),
            module: $request->input('module'),
            status: $request->input('status'),
            attachment: $request->file('attachment'),
        );
    }
}
