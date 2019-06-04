<?php

namespace App\Mail;

use App\Models\ContactUS;

use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Mail\Mailable;

use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The contact instance.
     *
     * @var \App\Models\ContactUS
     */
    protected $contact;


    /**
     * Create a new contact mail (message) instance.
     *
     * @param \App\Models\ContactUS $contact [the contact instance]
     *
     * @return void
     */
    public function __construct(ContactUS $contact)
    {
        $this->contact = $contact;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $contact = $this->contact;

        return $this->from($contact->email)
            ->view('public.contact.emails.email', compact('contact'));
    }
}
