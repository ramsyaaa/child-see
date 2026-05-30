<?php

namespace App\Mail;

use App\Models\User;
use App\Models\OtpCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $otpCode;
    public $expirationMinutes = 10;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, OtpCode $otpCode)
    {
        $this->user = $user;
        $this->otpCode = $otpCode;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verifikasi Email - Kode OTP Anda',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Extract the OTP code string to avoid serialization issues
        $otpCodeString = $this->otpCode instanceof OtpCode
            ? $this->otpCode->otp_code
            : (string) $this->otpCode;

        return new Content(
            view: 'emails.otp-verification',
            with: [
                'user' => $this->user,
                'otpCode' => $otpCodeString,
                'expirationMinutes' => $this->expirationMinutes,
            ]
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
