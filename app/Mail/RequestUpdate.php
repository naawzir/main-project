<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestUpdate extends Mailable
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
            ->subject('Progress Update Request for ' . $this->request->address)
            ->view('mail.request-update', ['title' => 'Progress Update Request for ' .
            $this->request->address,'user' =>
            $this->request->user,'caseref' =>
            $this->request->caseref,'address' =>
            $this->request->address,'messagebody' =>
            $this->request->message]);
    }
}
