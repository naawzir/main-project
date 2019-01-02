<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestTraining extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from($this->request->from)
            ->subject('Training Request for ' . $this->request->branch)
            ->view(
                'mail.request-training',
                [
                    'title' => 'Training Request for ' . $this->request->branch,
                    'branch' => $this->request->branch,
                    'username' => $this->request->username,
                    'messagebody' => $this->request->message,
                ]
            );
    }
}
