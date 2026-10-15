<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = URL::temporarySignedRoute(
            'api.v1.auth.email.verify',
            now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ],
        );

        return (new MailMessage)
            ->subject('تأیید نشانی ایمیل | فایل‌استور')
            ->greeting('سلام '.$notifiable->name.' عزیز')
            ->line('برای فعال‌سازی حساب فایل‌استور، نشانی ایمیل خود را تأیید کنید.')
            ->action('تأیید ایمیل', $url)
            ->line('این پیوند تا ۶۰ دقیقه معتبر است.');
    }
}
