<?php

namespace App\Notifications;

use App\Mail\BareMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;
    public $mail;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token, BareMail $mail)
    {
        $this->token = $token;
        $this->mail = $mail;
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
    public function toMail(object $notifiable)
    {
        return $this->mail
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->to($notifiable->email)
            ->with([
                'url' => route('password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->email,
                ]),
                'count' => config(
                    'auth.passwords.' .
                        config('auth.defaults.passwords') .
                        '.expire'
                ),
            ]);
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
