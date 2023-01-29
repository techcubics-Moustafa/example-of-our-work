<?php

namespace App\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(protected array $data)
    {
        //
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            cc: ['waleedsaid@gmail.com', 'ahmed@gmail.com']/*$this->data['cc']*/,
            bcc: ['kh@gmail.com', 'ws@gmail.com']/*$this->data['bcc']*/,
            //replyTo: $this->data['to'],
            subject: $this->data['subject'],
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'mails.contact-us',
            with: [
                'content' => $this->data['content']
            ],
        );
    }


    public function attachments(): array
    {
        $paths = [];
        foreach ($this->data['files'] as $file) {
            $paths[] = Attachment::fromStorage($file->full_file);
        }
        return $paths;
    }
}
