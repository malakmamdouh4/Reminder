<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HistoryNotification extends Notification
{
    use Queueable;
    private $history;

    public function __construct($history)
    {
        $this->history = $history;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'history_id' => $this->history['id']
        ];
    }
}
