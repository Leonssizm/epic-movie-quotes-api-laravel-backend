<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyUserEmail extends Mailable
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
			subject: 'Please verify your email address',
		);
	}

	/**
	 * Get the message content definition.
	 */
	public function content(): Content
	{
		return new Content(
			view: 'components.emails.verification-email',
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
	 				 	'username'         => $this->user->username,
	 				 	'verificationLink' => $this->verificationUrl,
	 				 ]);
	 }
}
