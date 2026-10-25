<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Locale;
use App\Http\Requests\StoreMessageRequest;
use App\Mail\ContactInquiry;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class MessageController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Contact');
    }

    public function store(StoreMessageRequest $request): RedirectResponse
    {
        $message = Message::query()->create([
            ...$request->safe()->only([
                'first_name',
                'last_name',
                'email',
                'phone',
                'message',
            ]),
            'locale' => Locale::tryFrom(app()->getLocale()) ?? Locale::default(),
        ]);

        Mail::to((string) config('mail.from.address'))->send(new ContactInquiry(
            firstName: $message->first_name,
            lastName: $message->last_name,
            email: $message->email,
            phone: $message->phone,
            body: $message->message,
        ));

        return Inertia::flash(
            'success',
            'Votre message a bien été envoyé. Un conseiller PAH Immo vous répondra rapidement.',
        )->back();
    }
}
