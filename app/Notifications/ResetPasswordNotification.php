<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    // Terima token dari user model
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Generate URL Reset
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Panggil View Custom
        return (new MailMessage)
            ->subject('Permintaan Reset Password - SiAlumni')
            ->view('emails.reset_password', [
                'url' => $url,
                'notifiable' => $notifiable
            ]);
    }
}