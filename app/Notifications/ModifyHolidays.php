<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ModifyHolidays extends Notification
{
    use Queueable;
    private $holidays;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($holidays)
    {
        $this->holidays = $holidays;
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
        $url = url('/holidays');

        return (new MailMessage)
            ->subject("Petición de modificación de vacaciones.")
            ->greeting('Hola!')
            ->line('Las vacaciones que entre ' . $this->holidays->fecha_inicio . ' y ' . $this->holidays->fecha_fin . ' necesitan ser modificadas')
            ->action('Ver el estado de tus vacaciones', $url);
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
            'title' => 'Modificación necesaria en vacaciones',
            'message' => 'Las vacaciones que entre ' . $this->holidays->fecha_inicio . ' y ' . $this->holidays->fecha_fin . ' necesitan ser modificadas',
            'link' => '/holidays'
        ];
    }
}
