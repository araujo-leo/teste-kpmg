<?php

use App\Jobs\ProcessTicketAttachment;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\User;
use App\Notifications\TicketProcessedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('Process a attachment and update Ticket detail', function () {
    Storage::fake('local');
    Notification::fake();

    $user = User::factory()->create();
    $ticket = Ticket::factory()->create(['user_id' => $user->id]);
    $detail = TicketDetail::factory()->create(['ticket_id' => $ticket->id]);

    $filePath = 'attachments/test.json';
    $jsonContent = json_encode(['env' => 'production', 'module' => 'finance']);
    Storage::disk('local')->put($filePath, $jsonContent);

    $ticket->update(['attachment_path' => $filePath]);

    (new ProcessTicketAttachment($ticket))->handle();

    $detail->refresh();
    expect($detail->enriched_data)->toBeArray()
        ->and($detail->enriched_data['content']['env'])->toBe('production');

    Notification::assertSentTo($user, TicketProcessedNotification::class);
});
