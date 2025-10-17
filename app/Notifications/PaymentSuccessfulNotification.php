<?php
// app/Notifications/PaymentSuccessfulNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Hekmatinasser\Verta\Verta;

class PaymentSuccessfulNotification extends Notification
{
    use Queueable;

    public $paymentDetails;

    public function __construct($paymentDetails)
    {
        $this->paymentDetails = $paymentDetails;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('پرداخت موفق - فایل استور')
            ->view('emails.payment_success', [
                'user' => $notifiable,
                'payment' => $this->paymentDetails,
                'paymentTime' => Verta::now()->format('H:i، j F Y') 
            ]);
    }
}