<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Get active team members / directors for React frontend
     */
    public function index(Request $request): JsonResponse
    {
        $query = TeamMember::active();

        // Optional search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('position', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('limit')) {
            $query->limit((int) $request->limit);
        }

        $members = $query->get();

        return response()->json([
            'success' => true,
            'count' => $members->count(),
            'data' => $members,
        ]);
    }

    /**
     * Get single team member details
     */
    public function show(int $id): JsonResponse
    {
        $member = TeamMember::where('is_active', true)->find($id);

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Team member not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $member,
        ]);
    }
}
