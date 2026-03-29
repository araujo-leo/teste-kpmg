<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('creating a ticket', function () {
    Storage::fake('local');
    Queue::fake();

    $user = User::factory()->create();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post('/tickets', [
        'project_id' => $project->id,
        'title' => 'Valid Ticket Title',
        'description' => 'Valid Description',
        'status' => 'pending',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('tickets', ['title' => 'Valid Ticket Title']);
});


it('creating with missing data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/tickets', []);

    $response->assertSessionHasErrors(['project_id', 'title']);
});

it('creating with inexistent project', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/tickets', [
        'project_id' => 999,
        'title' => 'Ticket for ghost project',
        'status' => 'pending',
    ]);

    $response->assertSessionHasErrors(['project_id']);
});

it('Creting with title exceeding 255 characters', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post('/tickets', [
        'project_id' => $project->id,
        'title' => str_repeat('a', 256),
        'status' => 'pending',
    ]);

    $response->assertSessionHasErrors(['title']);
});



