<?php

namespace Modules\Auth\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;

class ForgotPasswordEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.forgotpassword',
            with: [
                'code' => $this->data['code'],
                'name' => $this->data['name'],
                'expiry' => config('app.password_reset_expiry')
            ],
        );
    }
}
