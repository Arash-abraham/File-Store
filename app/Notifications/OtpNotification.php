<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Hekmatinasser\Verta\Verta;

class OtpNotification extends Notification
{
    use Queueable;

    public $otp;
    public $expiryTime;

    public function __construct($otp)
    {
        $this->otp = $otp;
        $this->expiryTime = Verta::now()->addMinutes(5)->format('H:i، j F Y');
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('کد تأیید ورود - فایل استور')
            ->view('emails.otp', [
                'otp' => $this->otp,
                'expiryTime' => $this->expiryTime
            ]);
    }
}