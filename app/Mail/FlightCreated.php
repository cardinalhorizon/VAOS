<?php

namespace App\Mail;

use App\Models\Flight;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FlightCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $flight;

    public function __construct(Flight $flight)
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
}
