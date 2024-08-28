<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ItemWonNotification extends Notification
{
    use Queueable;

    public $item;
    public $bill;

    /**
     * Create a new notification instance.
     *
     * @param  $item
     * @param  $bill
     */
    public function __construct($item, $bill)
    {
        $this->item = $item;
        $this->bill = $bill;
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
            ->subject('Congratulations! You Have Won the Auction for "' . $this->item->name . '"')
            ->line('Congratulations! You have won the auction for the item "' . $this->item->name . '".')
            ->line('Winning Bid Amount: $' . $this->bill->amount)
            ->line('Please complete the payment by ' . $this->bill->payment_due_date->format('F j, Y') . '.')
            ->line('Thank you for participating in the auction!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'item_id' => $this->item->id,
            'item_name' => $this->item->name,
            'amount' => $this->bill->amount,
            'payment_due_date' => $this->bill->payment_due_date->format('F j, Y'),
            'message' => 'Congratulations! You have won the auction for "' . $this->item->name . '". Please complete the payment by ' . $this->bill->payment_due_date->format('F j, Y') . '.',
        ];
    }
}
