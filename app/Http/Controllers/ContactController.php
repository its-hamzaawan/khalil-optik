<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Contact');
    }

    public function store(Request $request): RedirectResponse
    {
        // Simple honeypot: real visitors never fill the hidden "website" field.
        if (filled($request->input('website'))) {
            return back()->with('success', 'Thank you! We will get back to you soon.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:80'],
            'phone' => ['nullable', 'required_without:email', 'string', 'max:20'],
            'email' => ['nullable', 'required_without:phone', 'email', 'max:120'],
            'subject' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'phone.required_without' => 'Please give us a phone number or an email so we can reply.',
            'email.required_without' => 'Please give us an email or a phone number so we can reply.',
        ]);

        ContactMessage::create($data);

        return back()->with('success', 'Thank you! Your message has been sent. We will contact you soon.');
    }
}
