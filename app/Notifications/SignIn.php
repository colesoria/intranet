<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;

class SignIn extends Notification
{
    use Queueable;

    private $sign;
    private $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sign)
    {
        $this->sign = $sign;
        $this->user = User::where('id', $sign->user_id)->first();
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
        $url = url('/admin/signs/today');
        return (new MailMessage)
            ->subject("Fichaje de entrada.")
            ->greeting('Hola!')
            ->line($this->user->name . ' ha fichado para entrar a las ' . $this->sign->in . ' horas.')
            ->action('Ver los fichajes de hoy', $url);
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
            'title' => 'Fichaje de entrada',
            'message' => $this->user->name . ' ha fichado para entrar a las ' . $this->sign->in . ' horas.',
            'link' => '/admin/signs/today'
        ];
    }
}
