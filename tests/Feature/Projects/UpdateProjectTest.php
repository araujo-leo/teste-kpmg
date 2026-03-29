<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Update a project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['name' => 'Old Project Name']);

    $response = $this->actingAs($user)->put("/projects/{$project->id}", [
        'company_id' => $project->company_id,
        'name' => 'New Project Name',
        'status' => 'completed',
        'code' => $project->code,
        'priority' => $project->priority,
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => 'New Project Name',
    ]);
});

it('Update with empty name ', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->put("/projects/{$project->id}", [
        'name' => '',
    ]);

    $response->assertSessionHasErrors(['name']);
});
