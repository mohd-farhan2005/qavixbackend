<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    /**
     * Get active industries for React frontend
     */
    public function index(Request $request): JsonResponse
    {
        $query = Industry::active()->ordered();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Include nested active clients if requested (e.g. ?with_clients=1)
        if ($request->boolean('with_clients')) {
            $query->with(['activeClients' => function ($q) {
                $q->ordered();
            }]);
        } else {
            $query->withCount(['clients as active_clients_count' => function ($q) {
                $q->where('is_active', true);
            }]);
        }

        $industries = $query->get();

        return response()->json([
            'success' => true,
            'count' => $industries->count(),
            'data' => $industries,
        ]);
    }

    /**
     * Get single industry by slug or id with its clients
     */
    public function show(string $slugOrId): JsonResponse
    {
        $query = Industry::active()->where(function ($q) use ($slugOrId) {
            if (is_numeric($slugOrId)) {
                $q->where('id', (int) $slugOrId);
            } else {
                $q->where('slug', $slugOrId);
            }
        })->with(['activeClients' => function ($q) {
            $q->ordered();
        }]);

        $industry = $query->first();

        if (!$industry) {
            return response()->json([
                'success' => false,
                'message' => 'Industry not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $industry,
        ]);
    }
}

