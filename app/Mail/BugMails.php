<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BugMails extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        //
        $this->data = $data;
        //dd($this->data);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $mail = $this->view('emails.bugs_emails')->with($this->data) ->subject('New Bug By'. ' '.$this->data['createdByName']);
        if ($this->data['bug_attachments'] !== '')
        {
            $mail->attach(public_path().'/'.$this->data['bug_attachments']);
        }
    }
}
