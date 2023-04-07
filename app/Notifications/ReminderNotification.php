<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderNotification extends Notification
{
    use Queueable;
    private $reminder;

    public function __construct($reminder)
    {
        $this->reminder = $reminder;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'reminder_id' => $this->reminder['id']
        ];
    }
}
