<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;

class HolidaysPetition extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $holidays;

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
        $user = User::where('id', $this->holidays->user_id)->first();
        return (new MailMessage)
            ->subject("Nueva petición de vacaciones")
            ->greeting('Hola!')
            ->line($user->name . " ha realizado una nueva petición de vacaciones.")
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
            'title' => "Nueva petición de Vacaciones",
            'message' => $user->name . " ha realizado una nueva petición de vacaciones.",
            'link' => '/holidays/review/' . $this->holidays->id
        ];
    }
}
