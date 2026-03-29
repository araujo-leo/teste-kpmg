<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Ticket $ticket)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $ticketUrl = url("/tickets/{$this->ticket->id}");

        return (new MailMessage)
            ->subject("Ticket #{$this->ticket->id} Attachment Processed")
            ->greeting("Hello, {$notifiable->name}!")
            ->line("Good news! The attachment for the ticket \"{$this->ticket->title}\" has been successfully read and processed by our background system.")
            ->line('The environment and module details have been updated.')
            ->action('View Ticket', $ticketUrl)
            ->line('Thank you for using ServiceHub!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'message' => 'The ticket attachment has been processed successfully.',
        ];
    }
}
