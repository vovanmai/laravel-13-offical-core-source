<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $password,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tài khoản của bạn đã được tạo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.user-credentials',
            with: [
                'name'     => $this->user->name,
                'email'    => $this->user->email,
                'password' => $this->password,
                'loginUrl' => rtrim(config('app.frontend_url'), '/') . '/login',
            ],
        );
    }
}
