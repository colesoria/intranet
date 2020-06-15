<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptRemote extends Notification
{
    use Queueable;

    private $remote;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
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
        $url = url('/remote');

        return (new MailMessage)
            ->subject("Teletrabajo aceptado.")
            ->greeting('Hola!')
            ->line('El día de teletrabajo que pediste ('.$this->remote->date.') se ha aprobado. Felicidades!!')
            ->action('Ver teletrabajo', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Teletrabajo aceptado',
            'message' => 'El día de teletrabajo que pediste ('.$this->remote->date.') se ha aprobado. Felicidades!!',
            'link' => '/remote'
        ];
    }
}
