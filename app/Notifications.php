<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MedicineRequestNotification extends Notification
{
    use Queueable;

    private $medicineRequests;

    public function __construct($medicineRequests)
    {
        $this->medicineRequests = $medicineRequests;
    }

    public function via($notifiable)
    {
        return ['mail']; // You can customize this to use other channels like 'database', 'slack', etc.
    }

    public function toMail($notifiable)
    {
        // Build the mail representation of the notification
        return (new MailMessage)
            ->line('New medicine request(s) have been made:')
            ->line(json_encode($this->medicineRequests));
    }
}
