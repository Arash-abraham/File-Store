<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PriceDropNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Product $product,
        public int $savedPrice,
        public int $currentPrice,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $bookUrl = rtrim((string) config('services.frontend.url'), '/').'/book/'.$this->product->slug;

        return (new MailMessage)
            ->subject('خبر خوب! قیمت کتاب موردعلاقه‌ات کاهش یافت')
            ->greeting('سلام '.$notifiable->name.' عزیز')
            ->line('قیمت کتاب «'.$this->product->title.'» که در فهرست علاقه‌مندی‌ها ذخیره کرده‌ای، کاهش پیدا کرده است.')
            ->line('قیمت هنگام ذخیره: '.$this->formatPrice($this->savedPrice))
            ->line('قیمت جدید: '.$this->formatPrice($this->currentPrice))
            ->action('مشاهده‌ی کتاب', $bookUrl)
            ->line('این پیام به‌دلیل فعال بودن کتاب در فهرست علاقه‌مندی‌های شما ارسال شده است.')
            ->line('برای توقف هشدارهای این کتاب، آن را از علاقه‌مندی‌ها حذف کنید.');
    }

    private function formatPrice(int $amount): string
    {
        return strtr(number_format($amount), [
            '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', ',' => '٬',
        ]).' تومان';
    }
}
