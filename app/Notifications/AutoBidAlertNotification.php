<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AutoBidAlertNotification extends Notification
{
    use Queueable;

    public $autoBid;

    /**
     * Create a new notification instance.
     */
    public function __construct($autoBid)
    {
        $this->autoBid = $autoBid;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Auto-bid Alert for the item "' . $this->autoBid->item->name . '"')
            ->line('Your auto-bid for the item "' . $this->autoBid->item->name . '" has reached the alert threshold you set');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'item_id' => $this->autoBid->item->id,
            'item_name' => $this->autoBid->item->name,
            'message' => 'Your auto-bid for the item "' . $this->autoBid->item->name . '" has reached the alert threshold you set',
        ];
    }
}
