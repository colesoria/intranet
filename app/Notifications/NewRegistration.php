<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRegistration extends Notification
{
    use Queueable;

    private $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        $url = url('/');

        return (new MailMessage)
            ->subject("Registro en intranet de Clicknaranja")
            ->greeting('Hola! ' . $this->user->name)
            ->line('Ya estás registrado en la intranet de Clicknaranja.')
            ->line('Puedes acceder a la plataforma con tu correo electrónico y la contraseña cN1256cn.')
            ->line('Por tu seguridad, cambiala por otra que sólo sepas tú lo antes posible.')
            ->action('Entrar en la intranet', $url);
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
            "title" => "Bienvenida a la intranet de Clicknaranja",
            "message" => "Bienvenido a la intranet de Clicknaranja. Ve pidiendote vacaciones para empezar."
        ];
    }
}
