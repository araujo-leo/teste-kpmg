<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Create a project', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();

    $response = $this->actingAs($user)->post('/projects', [
        'company_id' => $company->id,
        'name' => 'Service Hub',
        'code' => '001A',
        'status' => 'not_started',
        'priority' => 'high',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('projects', [
        'name' => 'Service Hub',
        'company_id' => $company->id,
    ]);
});

it('Create with missing data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/projects', []);

    $response->assertSessionHasErrors(['company_id', 'name']);
});

it('Creating with inexistent company', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/projects', [
        'company_id' => 999,
        'name' => 'Project for ghost company',
    ]);

    $response->assertSessionHasErrors(['company_id']);
});
