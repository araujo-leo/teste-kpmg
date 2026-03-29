<?php

namespace App\Http\Controllers;

use App\DTOs\CompanyDTO;
use App\Http\Requests\Company\StoreCompanyRequest;
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
}
