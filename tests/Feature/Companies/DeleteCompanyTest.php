<?php

use App\Models\Company;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Delete a company and its children', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();

    $project = Project::factory()->create(['company_id' => $company->id]);

    $response = $this->actingAs($user)->delete("/companies/{$company->id}");

    $response->assertRedirect('/dashboard');

    $this->assertSoftDeleted('companies', ['id' => $company->id]);

    $this->assertSoftDeleted('projects', ['id' => $project->id]);
});
