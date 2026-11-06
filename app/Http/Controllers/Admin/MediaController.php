<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        $media = MediaUpload::latest()->paginate(24);

        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,webp,svg,ico,pdf|max:10240',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;

        $uploadDir = storage_path('app/public/uploads');
        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        $file->move($uploadDir, $fileName);
        $filePath = 'uploads/' . $fileName;
        $baseUrl = rtrim(config('app.url'), '/');
        $fileUrl = $baseUrl . '/storage/uploads/' . $fileName;

        MediaUpload::create([
            'original_name' => $originalName,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'alt_text' => $request->input('alt_text'),
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('admin.media.index')
            ->with('success', 'File "' . $originalName . '" uploaded successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $media = MediaUpload::findOrFail($id);
        $fullPath = storage_path('app/public/' . $media->file_path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
        $media->delete();

        return redirect()->route('admin.media.index')
            ->with('success', 'Media file deleted.');
    }
}

