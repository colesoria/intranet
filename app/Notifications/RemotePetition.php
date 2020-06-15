<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;

class RemotePetition extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $remote;

    public function __construct($remote)
    {
        $this->remote = $remote;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/remote/review/' . $this->remote->id);
        $user = User::where('id', $this->remote->user_id)->first();
        return (new MailMessage)
            ->subject("Nueva petición de teletrabajo.")
            ->greeting('Hola!')
            ->line($user->name . " ha realizado una nueva petición de teletrabajo.")
            ->action('Ver la petición', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $user = User::where('id', $this->remote->user_id)->first();
        return [
            'title' => "Nueva petición de teletrabajo",
            'message' => $user->name . " ha realizado una nueva petición de teletrabajo.",
            'link' => '/remote/review/' . $this->remote->id
        ];
    }
}
