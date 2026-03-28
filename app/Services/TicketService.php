<?php

namespace App\Services;

use App\DTOs\TicketDTO;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;

class TicketService
{
    public function createTicket(TicketDTO $dto, int $userId): Ticket
    {
        $attachmentPath = null;
        if ($dto->attachment) {
            $attachmentPath = $dto->attachment->store('attachments', 'local');
        }

        $ticket = Ticket::create([
            'project_id' => $dto->project_id,
            'user_id' => $userId,
            'title' => $dto->title,
            'description' => $dto->description,
            'attachment_path' => $attachmentPath,
        ]);

        $ticket->detail()->create([
            'environment' => $dto->environment,
            'module' => $dto->module,
        ]);

        return $ticket;
    }

    public function updateTicket(Ticket $ticket, TicketDTO $dto): Ticket
    {
        $attachmentPath = $ticket->attachment_path;
        if ($dto->attachment) {
            if ($attachmentPath && Storage::disk('local')->exists($attachmentPath)) {
                Storage::disk('local')->delete($attachmentPath);
            }
            $attachmentPath = $dto->attachment->store('attachments', 'local');
        }

        $ticket->update([
            'project_id' => $dto->project_id,
            'title' => $dto->title,
            'description' => $dto->description,
            'status' => $dto->status ?? $ticket->status,
            'attachment_path' => $attachmentPath,
        ]);

        $ticket->detail()->updateOrCreate(
            ['ticket_id' => $ticket->id],
            [
                'environment' => $dto->environment,
                'module' => $dto->module,
            ]
        );

        return $ticket;
    }

    public function deleteTicket(Ticket $ticket): void
    {
        $ticket->delete();
    }
}
