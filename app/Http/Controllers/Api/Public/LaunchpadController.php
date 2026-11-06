<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\LaunchpadLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaunchpadController extends Controller
{
    /**
     * Submit Launchpad application from React frontend (/launchpad)
     */
    public function store(Request $request): JsonResponse
    {
        // Normalize alias field names if frontend sends name, whatsapp, etc.
        $fullName = $request->input('full_name') ?? $request->input('name');
        $email = $request->input('email') ?? $request->input('email_address');
        $whatsapp = $request->input('whatsapp_number') ?? $request->input('whatsapp') ?? $request->input('phone');
        $startupName = $request->input('startup_name') ?? $request->input('startup');
        $industry = $request->input('industry');

        $request->merge([
            'full_name' => $fullName,
            'email' => $email,
            'whatsapp_number' => $whatsapp,
            'startup_name' => $startupName,
            'industry' => $industry,
        ]);

        $validated = $request->validate([
            'full_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'whatsapp_number' => 'required|string|min:6|max:50',
            'startup_name' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
        ], [
            'full_name.required' => 'Please provide your full name.',
            'email.required' => 'A valid email address is required.',
            'whatsapp_number.required' => 'Please provide your WhatsApp phone number.',
        ]);

        $validated['ip_address'] = $request->ip();
        $validated['status'] = 'new';
        $validated['is_read'] = false;

        $lead = LaunchpadLead::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for applying to the Launchpad! Welcome to the first 100 founders. Our team will connect with you on WhatsApp shortly.',
            'data' => [
                'id' => $lead->id,
                'full_name' => $lead->full_name,
                'email' => $lead->email,
                'whatsapp_number' => $lead->whatsapp_number,
                'startup_name' => $lead->startup_name,
                'industry' => $lead->industry,
                'created_at' => $lead->created_at,
            ],
        ], 201);
    }
}

