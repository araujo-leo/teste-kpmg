<?php

namespace App\Services;

use App\DTOs\CompanyDTO;
use App\Models\Company;

class CompanyService
{
    public function createCompany(CompanyDTO $dto, int $userId): Company
    {
        $Company = Company::create([
            'cnpj' => $dto->cnpj,
            'name' => $dto->name,
            'email' => $dto->email,
            'corporate_name' => $dto->corporate_name,
            'user_id' => $userId,
        ]);

        return $Company;
    }

    public function updateCompany(Company $company, CompanyDTO $dto): Company
    {
        $company->update([
            'cnpj' => $dto->cnpj,
            'name' => $dto->name,
            'email' => $dto->email,
            'corporate_name' => $dto->corporate_name,
        ]);

        return $company;
    }

    public function deleteCompany(Company $company): void
    {
        $company->delete();
    }
}
