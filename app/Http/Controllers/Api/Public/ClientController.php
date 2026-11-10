<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Get active clients for React frontend
     */
    public function index(Request $request): JsonResponse
    {
        $query = Client::active()->ordered()->with('industry:id,name,slug');

        // Filter by Industry Slug (e.g. ?industry=fintech)
        if ($request->filled('industry')) {
            $slug = $request->industry;
            $query->whereHas('industry', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        // Filter by Industry ID (e.g. ?industry_id=2)
        if ($request->filled('industry_id')) {
            $query->where('industry_id', (int) $request->industry_id);
        }

        // Filter by Featured clients (e.g. ?featured=1)
        if ($request->has('featured')) {
            $query->where('featured', $request->boolean('featured'));
        }

        // Search by Client Name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('limit')) {
            $query->limit((int) $request->limit);
        }

        $clients = $query->get();

        return response()->json([
            'success' => true,
            'count' => $clients->count(),
            'data' => $clients,
        ]);
    }

    /**
     * Get single client by ID or Slug for frontend Client Page
     */
    public function show(string $identifier): JsonResponse
    {
        $client = Client::active()
            ->with('industry:id,name,slug')
            ->where(function ($q) use ($identifier) {
                if (is_numeric($identifier)) {
                    $q->where('id', (int) $identifier)->orWhere('slug', $identifier);
                } else {
                    $q->where('slug', $identifier);
                }
            })
            ->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $client,
        ]);
    }
}

