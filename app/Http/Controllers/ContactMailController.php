<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactMailController extends Controller
{
    public function sendContactMail(Request $request)
    {
        // Validácia údajov
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            // Odoslanie emailu
            Mail::to('marek@rucki.sk')->send(
                new ContactFormMail(
                    senderName: $validated['name'],
                    senderEmail: $validated['email'],
                    emailSubject: $validated['subject'],
                    messageContent: $validated['message'],
                )
            );

            Log::info('Contact form email sent', [
                'from' => $validated['email'],
                'name' => $validated['name'],
            ]);

            return back();
        } catch (\Exception $e) {
            Log::error('Failed to send contact form email', [
                'error' => $e->getMessage(),
                'from' => $validated['email'],
            ]);

            return back()->withErrors([
                'email' => 'Nepodarilo sa odoslať správu. Skúste to prosím neskôr.'
            ]);
        }
    }
}
