<?php
namespace App\Jobs;
use App\Models\Ticket;
use App\Notifications\TicketProcessedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
class ProcessTicketAttachment implements ShouldQueue
{
    use Queueable;
    public function __construct(public Ticket $ticket)
    {
    }
    public function handle(): void
    {
        if (!$this->ticket->attachment_path || !Storage::disk('local')->exists($this->ticket->attachment_path)) {
            return;
        }

        $content = Storage::disk('local')->get($this->ticket->attachment_path);
        $metadata = [];
        $decoded = json_decode($content, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $metadata['content'] = $decoded;
            $metadata['type'] = 'JSON';
        } else {
            $metadata['word_count'] = str_word_count(strip_tags($content));
            $metadata['type'] = 'TEXT';
            $metadata['length'] = strlen($content);
        }
        if ($this->ticket->detail) {
            $this->ticket->detail->update([
                'enriched_data' => $metadata
            ]);
        }
        $this->ticket->user->notify(new TicketProcessedNotification($this->ticket));

        event(new TicketProcessedNotification($this->ticket));
    }
}
