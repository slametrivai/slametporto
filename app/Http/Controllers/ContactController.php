<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        // Rate limiter key based on IP
        $throttleKey = 'contact-inquiry:' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $message = "Too many messages. Please wait {$seconds} seconds and try again.";

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 429);
            }

            return back()->withErrors(['rate_limit' => $message])->withInput();
        }

        RateLimiter::hit($throttleKey, 60);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        $inquiry = Inquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? 'Website Inquiry',
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        $successMessage = 'Thanks for your message. Slamet Rivai will reply to your email soon.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'id' => $inquiry->id,
            ]);
        }

        return back()->with('success', $successMessage);
    }
}
