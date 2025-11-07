<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParticipantRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $sessionLabel;

    public function __construct($participant, $sessionLabel)
    {
        $this->participant = $participant;
        $this->sessionLabel = $sessionLabel;
    }

    public function build()
    {
        return $this->subject('Tiket Donor Darah Indo Teknik')
            ->view('emails.participant_registered');
    }
}
