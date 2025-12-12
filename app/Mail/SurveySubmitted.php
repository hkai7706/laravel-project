<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SurveySubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $responses;
    public $submittedAt;

    public function __construct($userName, $responses)
    {
        $this->userName = $userName;
        $this->responses = $responses;
        $this->submittedAt = now()->format('F j, Y g:i A');
    }

    public function build()
    {
        return $this->subject('New Love Survey Submission - ' . $this->userName)
                    ->view('emails.survey-submitted')
                    ->with([
                        'userName' => $this->userName,
                        'responses' => $this->responses,
                        'submittedAt' => $this->submittedAt
                    ]);
    }
}