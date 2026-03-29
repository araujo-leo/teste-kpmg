<?php

namespace App\Http\Controllers;

use App\DTOs\CompanyDTO;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {}

    public function index(): Response
    {
        $companies = Company::with('projects')->paginate(10);

        return Inertia::render('companies/Index', [
            'companies' => $companies,
        ]);
    }

    public function show(int $id)
    {
        $company = Company::with('projects')->findOrFail($id);

        return Inertia::render('companies/Show', [
            'company' => $company,
        ]);
    }

    public function create(): Response
    {
        $companies = Company::with('projects')->get();

        return Inertia::render('companies/Create', [
            'companies' => $companies,
        ]);
    }

    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $dto = CompanyDTO::fromRequest($request);
        $this->companyService->createCompany($dto, auth()->id());

        return redirect()->route('dashboard')->with('status', 'Company created successfully!');
    }

    public function edit(int $id): Response
    {
        $company = Company::with('projects')->findOrFail($id);

        return Inertia::render('companies/Edit', [
            'company' => $company,
        ]);
    }

    public function update(UpdateCompanyRequest $request, int $id): RedirectResponse
    {
        $company = Company::findOrFail($id);
        $dto = CompanyDTO::fromRequest($request);
        $this->companyService->updateCompany($company, $dto);

        return redirect()->route('dashboard')->with('status', 'Company updated successfully!');
    }
}
