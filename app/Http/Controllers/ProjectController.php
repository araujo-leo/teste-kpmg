<?php

namespace App\Http\Controllers;

use App\DTOs\ProjectDTO;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Models\Company;
use App\Models\User;
use App\Services\ProjectService;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {}

    public function index(): Response
    {
        $projects = $this->projectService->getAllprojects();

        return Inertia::render('projects/Index', [
            'projects' => $projects,
        ]);
    }

    public function show(int $id): Response
    {
        $project = $this->projectService->getProjectById($id);

        return Inertia::render('projects/Show', [
            'project' => $project,
        ]);
    }

    public function create(): Response
    {
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('projects/Create', [
            'companies' => $companies,
            'users' => $users,
        ]);
    }

    public function store(StoreProjectRequest $request)
    {
        $dto = ProjectDTO::fromRequest($request);

        $this->projectService->createProject($dto, auth()->id());

        return redirect()->route('dashboard')->with('status', 'Ticket created successfully!');

    }
}
