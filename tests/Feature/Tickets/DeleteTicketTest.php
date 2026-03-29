<?php

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Soft Delete a ticket', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create();

    $response = $this->actingAs($user)->delete("/tickets/{$ticket->id}");

    $response->assertRedirect('/tickets');

    $this->assertSoftDeleted('tickets', ['id' => $ticket->id]);
});
