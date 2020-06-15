<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;

class ModifiedAbscene extends Notification
{
    use Queueable;
    private $abscense;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($absence)
    {
        $this->abscense = $absence;
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
        $url = url('/abscenses/review/' . $this->abscense->id);

        return (new MailMessage)
            ->subject("Modificación de ausencia en Clicknaranja.")
            ->greeting('Hola!')
            ->line('Se ha modificado una ausencia.')
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
        $user = User::where('id', $this->abscense->user_id)->first();
        return [
            'title' => "Modificación de notificación de ausencia",
            'message' => $user->name . " ha hecho modificaciones en una notificación de ausencia.",
            'link' => '/abscenses/review/' . $this->abscense->id
        ];
    }
}
