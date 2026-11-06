<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * List uploaded media
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 24);
        $media = MediaUpload::latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $media->items(),
            'meta' => [
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
                'per_page' => $media->perPage(),
                'total' => $media->total(),
            ],
        ]);
    }

    /**
     * Upload an image or file
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,webp,svg,ico,pdf|max:10240',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();
        $size = $file->getSize();

        // Generate safe unique filename
        $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
        
        // Store in storage/app/public/uploads
        $uploadDir = storage_path('app/public/uploads');
        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        $file->move($uploadDir, $fileName);
        $filePath = 'uploads/' . $fileName;
        
        // Build absolute public URL
        $baseUrl = rtrim(config('app.url'), '/');
        $fileUrl = $baseUrl . '/storage/uploads/' . $fileName;

        $media = MediaUpload::create([
            'original_name' => $originalName,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'mime_type' => $mimeType,
            'file_size' => $size,
            'alt_text' => $request->input('alt_text'),
            'uploaded_by' => $request->user()?->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => [
                'id' => $media->id,
                'name' => $media->file_name,
                'original_name' => $media->original_name,
                'url' => $media->file_url,
                'mime_type' => $media->mime_type,
                'size' => $media->file_size,
                'alt_text' => $media->alt_text,
            ],
        ], 201);
    }

    /**
     * Delete media item
     */
    public function destroy(int $id): JsonResponse
    {
        $media = MediaUpload::findOrFail($id);

        $fullPath = storage_path('app/public/' . $media->file_path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully',
        ]);
    }
}

