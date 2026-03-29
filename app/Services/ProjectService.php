<?php

namespace App\Services;

use App\DTOs\ProjectDTO;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectService
{
    public function getAllprojects(): LengthAwarePaginator
    {
        return Project::with('company:id,name', 'manager:id,name')->orderBy('name')->paginate(10);
    }

    public function getProjectById(int $id): Project
    {
        return Project::with(['company:id,name', 'manager:id,name', 'tickets:id,project_id,title,status,created_at'])->findOrFail($id);
    }

    public function createProject(ProjectDTO $dto): Project
    {
        return $project = Project::create([
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

    public function updateProject(Project $project, ProjectDTO $dto): bool
    {
        return $project->update([
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

    public function deleteProject(Project $project): bool
    {
        return $project->delete();
    }
}
