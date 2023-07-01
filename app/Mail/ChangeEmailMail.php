<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChangeEmailMail extends Mailable
{
	use Queueable, SerializesModels;

	public $verificationUrl;

	public $new_email;

	/**
	 * Create a new message instance.
	 */
	public function __construct($verificationUrl, $new_email)
	{
		$this->verificationUrl = $verificationUrl;

		$this->new_email = $new_email;
	}

	/**
	 * Get the message envelope.
	 */
	public function envelope(): Envelope
	{
		return new Envelope(
			subject: 'Change Email',
		);
	}

	/**
	 * Get the message content definition.
	 */
	public function content(): Content
	{
		return new Content(
			view: 'components.emails.change-email',
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
	 				 ->to($this->new_email)
	 				 ->subject('Please verify your email address')
	 				 ->with([
	 				 	'verificationLink' => $this->verificationUrl,
	 				 ]);
	 }
}
