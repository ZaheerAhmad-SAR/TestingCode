<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QueriesEmail extends Mailable
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
         //dd($data);
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

        $subject = '';

        if ($this->data['parent_query_id'] == 0)
        {
            $subject = $this->data['studyShortName']. '||'.'New Query By'.' '.$this->data['createdByName'];
        }
        else
        {

            $subject = $this->data['studyShortName'] . '||' . 'Reply Query By' . ' ' . $this->data['createdByName'];
        }

        $mail = $this->view('emails.queries_emails')->with($this->data) ->subject($subject);
            if ($this->data['attachment'] !== '')
            {
                $mail->attach(public_path().'/'.$this->data['attachment']);
            }

    }
}
