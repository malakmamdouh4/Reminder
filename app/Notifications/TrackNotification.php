<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrackNotification extends Notification
{
    use Queueable;
    private $track;

    public function __construct($track)
    {
        $this->track = $track;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'track_id' => $this->track['id']
        ];
    }
}
