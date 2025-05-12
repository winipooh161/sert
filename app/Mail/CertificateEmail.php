<?php

namespace App\Mail;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificateEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Экземпляр сертификата.
     *
     * @var Certificate
     */
    public $certificate;

    /**
     * Сопроводительное сообщение.
     *
     * @var string|null
     */
    public $message;

    /**
     * Create a new message instance.
     */
    public function __construct(Certificate $certificate, ?string $message = null)
    {
        $this->certificate = $certificate;
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ваш подарочный сертификат №' . $this->certificate->certificate_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.certificate',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
