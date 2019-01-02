<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PasswordResetSms extends Notification
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
     * @return array
     */
    public function via(): array
    {
        return [SmsChannel::class];
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @param  mixed $notifiable
     * @return object
     */
    public function toSMS($notifiable)
    {

        $target = $notifiable->mobile;

        if ($target[0] ?? null) {
            $target = preg_replace('/^0?/', config('app.countrycode'), $target);
        }

        $message = "Hello,\n\nA password reset has been requested on your account. If this was you, Please Click Here: "
            . config('app.url') . 'password/reset/' . $this->resetToken . '/' . $notifiable->id;

        $originator = 'TCP';

        $data = (object) ['to' => $target, 'message' => $message, 'originator' => $originator];

        return $data;
    }
}
