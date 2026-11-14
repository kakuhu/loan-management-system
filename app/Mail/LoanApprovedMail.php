<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Applicant $applicant)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your loan application has been approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.loan-approved',
            with: [
                'applicant' => $this->applicant,
            ],
        );
    }
}
