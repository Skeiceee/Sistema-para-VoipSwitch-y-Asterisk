<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccessChargeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from('noreply@capa1.cl')
                    ->to($this->data['to'])
                    ->subject($this->data['subject'])
                    ->attachFromStorage('/revenues/'.$this->data['file_date'].'.xlsx')
                    ->view('mails.accesscharge');
    }
}
