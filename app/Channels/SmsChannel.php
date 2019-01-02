<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Nessworthy\TextMarketer\Message\SendMessage;
use Nessworthy\TextMarketer\TextMarketer;
use Nessworthy\TextMarketer\TextMarketerException;

class SmsChannel
{
    /**
     * @var TextMarketer
     */
    private $textMarketer;

    public function __construct(TextMarketer $textMarketer)
    {
        $this->textMarketer = $textMarketer;
    }

    /**
     * Send an SMS Notification
     *
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return boolean
     */
    public function send($notifiable, Notification $notification): bool
    {
        $data = $notification->toSMS($notifiable);
        $tag = $data->tag ?? null;

        try {
            $message = new SendMessage($data->message, $data->to, $data->originator, $tag);
            $result = $this->textMarketer->sendMessage($message);
        } catch (TextMarketerException $exception) {
            // TODO: Handle me!
            return false;
        }

        // TODO: Handle $result!
        return $result->isSent() || $result->isQueued() || $result->isScheduled();
    }
}
