<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Auth\PasswordBroker;

class UserActivationSms extends Notification
{
    use Queueable;

    private $resetToken;

    public function __construct(string $resetToken)
    {
        $this->resetToken = $resetToken;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSMS($notifiable)
    {
        $target = $notifiable->mobile;

        if (substr($target, 0, 1)) {
            $target = preg_replace('/^0?/', config('app.countrycode'), $target);
        }

        $message = "Hello,\n\nTo activate your account, Please Click Here: "
            . config('app.url') . 'users/account-activation/' . $this->resetToken . '/' . $notifiable->id;

        $originator = 'TCP';

        $data = (object)array('to' => $target, 'message' => $message, 'originator' => $originator);

        return $data;
    }
}
