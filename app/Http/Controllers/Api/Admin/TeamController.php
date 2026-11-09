<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    /**
     * List all team members (active and inactive) for admin
     */
    public function index(Request $request): JsonResponse
    {
        $query = TeamMember::query()->orderBy('order', 'asc')->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('position', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        return response()->json([
            'success' => true,
            'data' => $query->get(),
        ]);
    }

    /**
     * Store new team member
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('team', 'public');
        } elseif ($request->filled('photo') && is_string($request->photo)) {
            $photoPath = $request->photo;
        }

        $member = TeamMember::create([
            'name' => $validated['name'],
            'position' => $validated['position'],
            'email' => $validated['email'] ?? null,
            'photo' => $photoPath,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Team member created successfully',
            'data' => $member,
        ], 201);
    }

    /**
     * Show single team member
     */
    public function show(int $id): JsonResponse
    {
        $member = TeamMember::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $member,
        ]);
    }

    /**
     * Update team member
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $member = TeamMember::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo && !str_starts_with($member->photo, 'http') && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $request->file('photo')->store('team', 'public');
        }

        if ($request->has('is_active')) {
            $validated['is_active'] = $request->boolean('is_active');
        }

        $member->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Team member updated successfully',
            'data' => $member->fresh(),
        ]);
    }

    /**
     * Delete team member
     */
    public function destroy(int $id): JsonResponse
    {
        $member = TeamMember::findOrFail($id);

        if ($member->photo && !str_starts_with($member->photo, 'http') && Storage::disk('public')->exists($member->photo)) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Team member deleted successfully',
        ]);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(int $id): JsonResponse
    {
        $member = TeamMember::findOrFail($id);
        $member->is_active = !$member->is_active;
        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated to ' . ($member->is_active ? 'active' : 'inactive'),
            'is_active' => $member->is_active,
        ]);
    }
}
