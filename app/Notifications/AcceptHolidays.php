<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptHolidays extends Notification
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
            ->subject("Vacaciones aceptadas.")
            ->greeting('Hola!')
            ->line('Las vacaciones que pediste  entre '. $this->holidays->fecha_inicio . ' y ' . $this->holidays->fecha_fin . ' se han aprobado. Felicidades!!')
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
            'title' => 'Vacaciones aceptadas',
            'message' => 'Las vacaciones que pediste  entre '. $this->holidays->fecha_inicio . ' y ' . $this->holidays->fecha_fin . ' se han aprobado. Felicidades!!',
            'link' => '/holidays'
        ];
    }
}
