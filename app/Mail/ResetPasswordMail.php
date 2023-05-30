<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
	use Queueable, SerializesModels;

	public $verificationUrl;

	public $user;

	/**
	 * Create a new message instance.
	 */
	public function __construct($verificationUrl, $user)
	{
		$this->verificationUrl = $verificationUrl;

		$this->user = $user;
	}

	/**
	 * Get the message envelope.
	 */
	public function envelope(): Envelope
	{
		return new Envelope(
			subject: 'Reset Password Email',
		);
	}

	/**
	 * Get the message content definition.
	 */
	public function content(): Content
	{
		return new Content(
			view: 'components.emails.reset-password',
		);
	}

	 /**
	  * Get the attachments for the message.
	  *
	  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
	  */
	 public function build()
	 {
	 	return $this->from('noreply@moviequotes.ge')
	 				 ->to($this->user->email)
	 				 ->subject('Please verify your email address')
	 				 ->with([
	 				 	'name'             => $this->user->username,
	 				 	'verificationLink' => $this->verificationUrl,
	 				 ]);
	 }
}
