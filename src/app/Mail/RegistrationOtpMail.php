<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private string $otp) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '【掲示板】メールアドレスの確認コード');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-otp',
            with: ['otp' => $this->otp],
        );
    }
}
