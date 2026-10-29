<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbandonedCartNotification extends Notification
{
    use Queueable;

    public function __construct(public array $bookTitles, public int $itemCount) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $cartUrl = rtrim((string) config('services.frontend.url'), '/').'/cart';
        $message = (new MailMessage)
            ->subject('سبدت منتظر کتاب‌های توست | فایل‌استور')
            ->greeting('سلام '.$notifiable->name.' عزیز')
            ->line('چند کتاب در سبد خریدت باقی مانده؛ اگر هنوز به آن‌ها علاقه‌مندی، می‌توانی خریدت را ادامه بدهی.');

        foreach ($this->bookTitles as $title) {
            $message->line('• '.$title);
        }

        if ($this->itemCount > count($this->bookTitles)) {
            $message->line('و '.($this->itemCount - count($this->bookTitles)).' کتاب دیگر');
        }

        return $message
            ->action('بازگشت به سبد خرید', $cartUrl)
            ->line('برای توقف این یادآوری‌ها، تنظیم رضایت ایمیلی را در حساب کاربری خاموش کن.');
    }
}
