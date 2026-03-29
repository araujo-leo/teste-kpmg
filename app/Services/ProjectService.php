<?php

namespace App\Services;

use App\DTOs\ProjectDTO;
use App\Models\Project;

class ProjectService
{
    public function getAllprojects()
    {
        return Project::with('company:id,name', 'manager:id,name')->orderBy('name')->paginate(10);
    }
    public function createProject(ProjectDTO $dto)
    {
        $project = Project::create([
            'company_id' => $dto->company_id,
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
            'status' => $dto->status,
            'priority' => $dto->priority,
            'starts_at' => $dto->starts_at,
            'ends_at' => $dto->ends_at,
            'manager_id' => $dto->manager_id,
        ]);
    }
}
