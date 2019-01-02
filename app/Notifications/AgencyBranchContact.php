<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AgencyBranchContact extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    //private $url;
    private $title;

    //public function __construct(string $url, string $title)
    public function __construct(string $title)
    {
        //$this->url = $url;
        $this->title = $title;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->error()
            ->from('postmaster@sandbox1266223f0adb4d7aa3ef028bf6014e61.mailgun.org')
            ->subject($this->title)
            ->view('mail.agency-branch-contact', [
                'user' => $notifiable,
                'title' => $this->title]) // 'url' => $this->url,
            ->success();
    }
}
