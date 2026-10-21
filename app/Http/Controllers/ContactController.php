<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Mail\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Contact');
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $inquiry = $request->safe()->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'message',
        ]);

        Mail::to((string) config('mail.from.address'))->send(new ContactInquiry(
            firstName: $inquiry['first_name'],
            lastName: $inquiry['last_name'],
            email: $inquiry['email'],
            phone: $inquiry['phone'],
            message: $inquiry['message'],
        ));

        return Inertia::flash(
            'success',
            'Votre message a bien été envoyé. Un conseiller PAH Immo vous répondra rapidement.',
        )->back();
    }
}
