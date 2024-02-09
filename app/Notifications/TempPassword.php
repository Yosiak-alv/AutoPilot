<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Str;
class TempPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $randomPassword = Str::random(8);

        $notifiable->update(['password' => $randomPassword]);
        
        return (new MailMessage)
            ->subject('AutoPilot - Contraseña Temporal')
            ->greeting('Hola, '.$notifiable->name )
            ->line('Este correo es para notificarle su Contraseña Temporal.')
            ->action('Iniciar Sesion', url('/'))
            ->line('Su contraseña temporal es: '.$randomPassword);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
