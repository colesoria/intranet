<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;

class AbsceneNotification extends Notification
{
    use Queueable;
    private $absence;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($absence)
    {
        $this->absence = $absence 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/absences/review/' . $this->absence->id);
        $user = User::where('id', $this->absence->user_id)->first();
        return (new MailMessage)
            ->subject("Nueva notificación de ausencia")
            ->greeting('Hola!')
            ->line($user->name . " ha realizado una nueva notificación de ausencia.")
            ->action('Ver la notificación', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $user = User::where('id', $this->absence->user_id)->first();
        return [
            'title' => "Nueva notificación de ausencia",
            'message' => $user->name . " ha realizado una nueva notificación de ausencia.",
            'link' => '/absences/review/' . $this->absence->id
        ];
    }
}
