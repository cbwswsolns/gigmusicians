<?php

namespace App\Http\Controllers\PublicArea\Contact;

use App\Http\Controllers\Controller;

use App\Http\Requests\ContactStoreFormRequest;

use App\Mail\ContactMail;

use App\Models\ContactUS;

use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('public.contact.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ContactStoreFormRequest $request   [the current request instance]
     * @param \App\Models\ContactUS                      $contactUs [the contactUS model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ContactStoreFormRequest $request, ContactUS $contactUs)
    {
        $contact = $contactUs->create($request->validated());

        Mail::to(env('MAIL_USERNAME'))->send(new ContactMail($contact));

        return back()->with('success', 'Thanks for contacting us! We will reply to you very soon!');
    }
}
