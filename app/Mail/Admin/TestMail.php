<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(public $request)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: _trans('Test Mail'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.test-email',
            with: [
                'request' => $this->request
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}
