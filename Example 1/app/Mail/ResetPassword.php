<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(protected $user, protected $token, public $url)
    {
        //
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: _trans('Reset Password'),
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'mails.reset-password',
            with: [
                'user' => $this->user,
                'token' => $this->token,
                'url' => $this->url
            ],
        );
    }


    public function attachments(): array
    {
        return [];
    }
}
