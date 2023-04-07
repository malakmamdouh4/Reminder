<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemoryNotification extends Notification
{
    use Queueable;
    private $memory;

    public function __construct($memory)
    {
        $this->memory = $memory;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'memory_id' => $this->memory['id']
        ];
    }
}
