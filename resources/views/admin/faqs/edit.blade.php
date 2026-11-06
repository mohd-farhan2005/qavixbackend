@extends('layouts.admin')

@section('title', 'Edit FAQ #' . $faq->id . ' | QAVIX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <div style="font-size: 12px; color: var(--color-secondary-bright); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
            Content & FAQs
        </div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff;">
            Edit FAQ Question
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Modifying Question #{{ $faq->id }} & Google FAQPage Schema definition.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary" style="font-size: 13px; text-decoration: none;">
            ← Back to All FAQs
        </a>
    </div>
</div>

<div style="max-width: 800px; margin: 0 auto;">
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 32px;">
        <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label for="question" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Question <span style="color: var(--color-secondary-bright);">*</span>
                </label>
                <input type="text" name="question" id="question" required value="{{ old('question', $faq->question) }}" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label for="category" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Category
                </label>
                <input type="text" name="category" id="category" list="categoryOptions" value="{{ old('category', $faq->category) }}" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px;">
                <datalist id="categoryOptions">
                    <option value="General">
                    <option value="Web Development">
                    <option value="SEO & Marketing">
                    <option value="React & Frontend">
                    <option value="API & Backend">
                    <option value="Pricing & Timelines">
                    @foreach($categoriesList as $cat)
                        <option value="{{ $cat }}">
                    @endforeach
                </datalist>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="answer" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                    Answer <span style="color: var(--color-secondary-bright);">*</span>
                </label>
                <textarea name="answer" id="answer" rows="8" required style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px; font-family: inherit; line-height: 1.6;">{{ old('answer', $faq->answer) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px;">
                <div>
                    <label for="sort_order" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Display Order
                    </label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" min="0" style="width: 100%; padding: 12px 14px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 14px;">
                    <div style="font-size: 11px; color: var(--color-text-muted); margin-top: 4px;">Lower numbers appear first</div>
                </div>

                <div style="display: flex; flex-direction: column; justify-content: center;">
                    <label style="font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Publication Status
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: #e2e8f0; font-size: 14px;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: var(--color-secondary);">
                        <span>Published (Live on Website & Schema)</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid var(--color-dark-border);">
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary" style="font-size: 13px; text-decoration: none;">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 12px 28px; background: var(--color-secondary); color: #fff; border: none; border-radius: 6px; font-weight: 600; font-family: var(--font-clash); font-size: 14px; cursor: pointer;">
                    Update FAQ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

