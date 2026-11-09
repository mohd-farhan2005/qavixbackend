@extends('layouts.admin')

@section('title', 'Media Library | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <div style="font-size: 12px; color: var(--color-secondary-bright); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
            Asset Manager
        </div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff;">
            Media & Image Library
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Upload article thumbnails, OpenGraph sharing cards, and copy URLs with 1 click.
        </p>
    </div>
</div>

<!-- Upload Box -->
<div style="background: var(--color-dark-card); border: 1px dashed var(--color-secondary-border); border-radius: var(--radius-lg); padding: 24px; margin-bottom: 32px;">
    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 20px; align-items: flex-end; flex-wrap: wrap;">
        @csrf
        <div style="flex: 2; min-width: 250px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                Select Image File (JPG, PNG, WebP, SVG)
            </label>
            <input type="file" name="file" required accept="image/*" style="width: 100%; font-size: 13px; color: var(--color-text-secondary);">
        </div>

        <div style="flex: 2; min-width: 200px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                Alt Text (for Image SEO)
            </label>
            <input type="text" name="alt_text" placeholder="Descriptive text for search engines..." style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 10px 20px; background: var(--color-secondary); color: #fff; border: none; border-radius: 6px; font-weight: 600; font-family: var(--font-clash); font-size: 14px; cursor: pointer;">
            Upload File
        </button>
    </form>
</div>

<!-- Media Gallery Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
    @forelse($media as $item)
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-md); overflow: hidden; display: flex; flex-direction: column;">
            <div style="height: 140px; background: #000; position: relative;">
                <img src="{{ $item->file_url }}" alt="{{ $item->alt_text }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <div style="padding: 12px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="font-size: 12px; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->original_name }}">
                        {{ $item->original_name }}
                    </div>
                    <div style="font-size: 11px; color: var(--color-text-muted); margin-top: 2px;">
                        {{ round($item->file_size / 1024, 1) }} KB
                    </div>
                </div>

                <div style="margin-top: 12px; display: flex; gap: 6px;">
                    <button type="button" onclick="copyUrl('{{ $item->file_url }}', this)" style="flex: 1; padding: 6px 8px; background: rgba(39, 184, 21, 0.15); border: 1px solid var(--color-secondary-border); border-radius: 4px; color: #4ade80; font-size: 11px; font-weight: 600; cursor: pointer;">
                        Copy URL
                    </button>

                    <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete media file?');" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding: 6px 8px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 4px; color: #f87171; font-size: 11px; cursor: pointer;">
                            ✕
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: var(--color-text-muted);">
            No media uploaded yet. Use the upload box above to add images.
        </div>
    @endforelse
</div>

<div style="margin-top: 24px;">
    {{ $media->links() }}
</div>

@push('scripts')
<script>
    function copyUrl(url, btn) {
        navigator.clipboard.writeText(url).then(() => {
            const originalText = btn.textContent;
            btn.textContent = 'Copied!';
            btn.style.background = '#27b815';
            btn.style.color = '#fff';
            setTimeout(() => {
                btn.textContent = originalText;
                btn.style.background = 'rgba(39, 184, 21, 0.15)';
                btn.style.color = '#4ade80';
            }, 2000);
        });
    }
</script>
@endpush
@endsection

