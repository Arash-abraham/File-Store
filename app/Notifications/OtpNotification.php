<?php
// new update -> Arash-abraham

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
        $this->expiryTime = Verta::now()->addMinutes(5)->format('H:i، j F Y'); // create expire time [iran/tehran]
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('کد تأیید ورود - فایل استور') //title
            ->view('emails.otp', [ // opt msg 
                'otp' => $this->otp, // code
                'expiryTime' => $this->expiryTime //expire time
            ]);
    }
}