<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SaditaResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $token
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset password SaditaSystem')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Kami menerima permintaan reset password untuk akun SaditaSystem Anda.')
            ->action('Reset password', $url)
            ->line('Link ini akan kedaluwarsa sesuai pengaturan keamanan sistem.')
            ->line('Jika Anda tidak merasa meminta reset password, abaikan email ini.');
    }
}
