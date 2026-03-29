<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Update a company', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create(['name' => 'Old Company Name']);

    $response = $this->actingAs($user)->put("/companies/{$company->id}", [
        'name' => 'Updated Company Name',
        'cnpj' => $company->cnpj,
        'email' => $company->email,
        'corporate_name' => $company->corporate_name,
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('companies', ['name' => 'Updated Company Name']);
});

it('Update a company with missing data', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();

    $response = $this->actingAs($user)->put("/companies/{$company->id}", [
        'name' => '',
    ]);

    $response->assertSessionHasErrors(['name']);
});
