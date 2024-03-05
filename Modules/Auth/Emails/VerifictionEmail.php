<?php

namespace Modules\Auth\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;

class VerifictionEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function content(): Content
    {
        $link = config('app.frontend_url') . '/register/complete?t=' . $this->data['token'];

        return new Content(
            view: 'emails.verification',
            with: [
                'name' => $this->data['name'],
                'link' => $link,
                'code' => $this->data['code']
            ],
        );
    }
}
