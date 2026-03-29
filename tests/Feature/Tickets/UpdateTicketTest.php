<?php

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Update a ticket', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create(['title' => 'Old Title']);

    $response = $this->actingAs($user)->put("/tickets/{$ticket->id}", [
        'project_id' => $ticket->project_id,
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'status' => 'in_progress',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('tickets', ['title' => 'Updated Title']);
});

it('Update a ticket with no data', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create();

    $response = $this->actingAs($user)->put("/tickets/{$ticket->id}", [
        'title' => '',
    ]);

    $response->assertSessionHasErrors(['title']);
});
