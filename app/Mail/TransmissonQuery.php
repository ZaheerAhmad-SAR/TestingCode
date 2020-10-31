<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransmissonQuery extends Mailable
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
        return $this->view('emails.transmisson_query')
            ->with($this->data)
            ->cc($this->data['cc_email'])
            ->attach(public_path().'/'.$this->data['attachment'])
            ->subject($this->data['query_subject']
                .' | '.$this->data['Transmission_Number'].' | '
                .$this->data['visit_name'].' | '.$this->data['StudyI_ID'].' | '.$this->data['Subject_ID']);
    }
}
