<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserDeactivationSms extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
     * @return \stdClass
     */
    public function toSMS($notifiable): \stdClass
    {

        $target = $notifiable->mobile;

        if ($target[0] ?? null) {
            $target = preg_replace('/^0?/', config('app.countrycode'), $target);
        }

        $message = "Hello,\n\nYou have received this message as you have deactivated your TCP account.\n\n"
            . 'You will now no longer be able to log in to our system, and after a period of time in keeping with '
            . "our data recording your details will be removed in their entirity.\n\nIf this was done innacurately, "
            . 'or should you wish to re-activate your account in future, Please contact The Conveyancing Department '
            . "on: \n\n" . config('app.tel');

        $originator = 'TCP';

        $data = (object) ['to' => $target, 'message' => $message, 'originator' => $originator];

        return $data;
    }
}
