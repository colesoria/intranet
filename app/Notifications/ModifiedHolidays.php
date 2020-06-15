<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;

class ModifiedHolidays extends Notification
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
        $url = url('/holidays/review/' . $this->holidays->id);

        return (new MailMessage)
            ->subject("Modificación de vacaciones en Clicknaranja.")
            ->greeting('Hola!')
            ->line('Se ha modificado una petición de vacaciones')
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
        $user = User::where('id', $this->holidays->user_id)->first();
        return [
            'title' => "Modificación de petición de Vacaciones",
            'message' => $user->name . " ha hecho las modificaciones requeridas en su petición de vacaciones.",
            'link' => '/holidays/review/' . $this->holidays->id
        ];
    }
}
