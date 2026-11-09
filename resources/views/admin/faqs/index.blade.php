@extends('layouts.admin')

@section('title', 'FAQs & Schema Management | QAVYX Control Center')

@section('content')
<div class="top-navbar">
    <div>
        <div style="font-size: 12px; color: var(--color-secondary-bright); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
            Structured Data & FAQ Engine
        </div>
        <h1 style="font-family: var(--font-clash); font-size: 26px; font-weight: 700; color: #fff;">
            Frequently Asked Questions (FAQs)
        </h1>
        <p style="color: var(--color-text-secondary); font-size: 14px;">
            Manage customer FAQs and automatically generate Google FAQPage JSON-LD rich snippets for high SEO search rankings.
        </p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ url('/api/faqs') }}" target="_blank" class="btn btn-secondary" style="font-size: 13px; text-decoration: none;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
            </svg>
            <span>Live FAQ API & Schema ↗</span>
        </a>
    </div>
</div>

<!-- SUMMARY STATS -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 18px 22px; text-align: left;">
        <div style="font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600; margin-bottom: 6px;">Total Questions</div>
        <div style="font-family: var(--font-clash); font-size: 28px; font-weight: 700; color: #fff;">{{ $totalFaqs }}</div>
        <div style="font-size: 11px; color: var(--color-secondary-bright); margin-top: 4px;">In database</div>
    </div>
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 18px 22px; text-align: left;">
        <div style="font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600; margin-bottom: 6px;">Published / Live</div>
        <div style="font-family: var(--font-clash); font-size: 28px; font-weight: 700; color: #4ade80;">{{ $activeFaqs }}</div>
        <div style="font-size: 11px; color: #86efac; margin-top: 4px;">Serving React Frontend & Google Schema</div>
    </div>
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 18px 22px; text-align: left;">
        <div style="font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600; margin-bottom: 6px;">Hidden / Draft</div>
        <div style="font-family: var(--font-clash); font-size: 28px; font-weight: 700; color: #fbbf24;">{{ $hiddenFaqs }}</div>
        <div style="font-size: 11px; color: #fde68a; margin-top: 4px;">Unpublished</div>
    </div>
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 18px 22px; text-align: left;">
        <div style="font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600; margin-bottom: 6px;">Topic Categories</div>
        <div style="font-family: var(--font-clash); font-size: 28px; font-weight: 700; color: #38bdf8;">{{ $totalCategories }}</div>
        <div style="font-size: 11px; color: #7dd3fc; margin-top: 4px;">Different FAQ groups</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 28px; align-items: start;">
    
    <!-- ADD FAQ FORM (STICKY) -->
    <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 24px; position: sticky; top: 20px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(114, 33, 255, 0.2); color: var(--color-secondary-bright); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                ➕
            </div>
            <div>
                <h2 style="font-family: var(--font-clash); font-size: 18px; font-weight: 600; color: #fff; margin: 0;">
                    Add New FAQ
                </h2>
                <span style="font-size: 12px; color: var(--color-text-muted);">Will appear instantly in API and frontend</span>
            </div>
        </div>

        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 16px;">
                <label for="question" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Question <span style="color: var(--color-secondary-bright);">*</span>
                </label>
                <input type="text" name="question" id="question" required placeholder="e.g. Can you connect a React frontend with this Laravel backend?" value="{{ old('question') }}" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
            </div>

            <div style="margin-bottom: 16px;">
                <label for="category" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Category
                </label>
                <input type="text" name="category" id="category" list="categoryOptions" placeholder="Select or type new category..." value="{{ old('category', 'General') }}" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
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
                <div style="font-size: 11px; color: var(--color-text-muted); margin-top: 4px;">
                    Type any category or pick from the list.
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label for="answer" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                    Answer <span style="color: var(--color-secondary-bright);">*</span>
                </label>
                <textarea name="answer" id="answer" rows="5" required placeholder="Write a clear, comprehensive answer for your visitors..." style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px; font-family: inherit; line-height: 1.5;">{{ old('answer') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 22px;">
                <div>
                    <label for="sort_order" style="display: block; font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 6px;">
                        Display Order
                    </label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', ($faqs->max('sort_order') ?? 0) + 1) }}" min="0" style="width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.4); border: 1px solid var(--color-dark-border); border-radius: 6px; color: #fff; font-size: 13px;">
                    <div style="font-size: 11px; color: var(--color-text-muted); margin-top: 4px;">Lower numbers appear first</div>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center;">
                    <label style="font-size: 13px; font-weight: 600; color: #e5e7eb; margin-bottom: 8px;">
                        Publication Status
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #e2e8f0; font-size: 13px;">
                        <input type="checkbox" name="is_active" value="1" checked style="width: 18px; height: 18px; accent-color: var(--color-secondary);">
                        <span>Published (Live)</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; background: var(--color-secondary); color: #fff; border: none; border-radius: 6px; font-weight: 600; font-family: var(--font-clash); font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <span>Publish FAQ to Backend</span>
            </button>
        </form>
    </div>

    <!-- FAQS LIST & MANAGEMENT -->
    <div>
        <!-- SEARCH & CATEGORY FILTER BAR -->
        <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 16px 20px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 14px; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px; flex: 1; min-width: 220px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--color-text-muted);">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" id="adminFaqSearch" placeholder="Search questions or answers in real-time..." 
                       style="background: transparent; border: none; color: #fff; width: 100%; outline: none; font-size: 13px;"
                       onkeyup="filterAdminFaqs()">
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;" id="categoryFilterPills">
                <button type="button" class="category-pill active-pill" onclick="filterCategory('all', this)" style="border: 1px solid var(--color-secondary); background: rgba(114, 33, 255, 0.25); color: #fff; cursor: pointer; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                    All ({{ $faqs->count() }})
                </button>
                @foreach($categoriesList as $cat)
                    <button type="button" class="category-pill" onclick="filterCategory('{{ Str::slug($cat) }}', this)" style="border: 1px solid var(--color-dark-border); background: rgba(255,255,255,0.03); color: var(--color-text-secondary); cursor: pointer; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- FAQS CONTAINER -->
        <div id="faqListContainer" style="display: flex; flex-direction: column; gap: 14px;">
            @forelse($faqs as $faq)
                <div class="faq-item-card" data-category="{{ Str::slug($faq->category) }}" data-text="{{ strtolower($faq->question . ' ' . $faq->answer . ' ' . $faq->category) }}"
                     style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-left: 4px solid {{ $faq->is_active ? 'var(--color-secondary)' : '#64748b' }}; border-radius: var(--radius-lg); padding: 20px; transition: transform 0.15s ease;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 12px;">
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 8px;">
                                <span style="background: rgba(114, 33, 255, 0.2); color: var(--color-secondary-bright); font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 50px; text-transform: uppercase;">
                                    {{ $faq->category ?: 'General' }}
                                </span>
                                <span style="background: rgba(255, 255, 255, 0.06); color: var(--color-text-muted); font-size: 11px; padding: 3px 8px; border-radius: 6px;">
                                    Sort: #{{ $faq->sort_order }}
                                </span>
                                @if($faq->is_active)
                                    <span style="background: rgba(34, 197, 94, 0.15); color: #4ade80; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                        ● Live
                                    </span>
                                @else
                                    <span style="background: rgba(239, 68, 68, 0.15); color: #f87171; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                        ○ Hidden
                                    </span>
                                @endif
                            </div>

                            <h3 style="font-family: var(--font-clash); font-size: 17px; font-weight: 600; color: #fff; line-height: 1.4; margin: 0;">
                                {{ $faq->question }}
                            </h3>
                        </div>

                        <!-- ACTIONS -->
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <!-- FAST TOGGLE ACTIVE -->
                            <form action="{{ route('admin.faqs.toggle-active', $faq->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="padding: 6px 12px; font-size: 12px; background: {{ $faq->is_active ? 'rgba(234, 179, 8, 0.15)' : 'rgba(34, 197, 94, 0.15)' }}; color: {{ $faq->is_active ? '#fde047' : '#4ade80' }}; border: 1px solid {{ $faq->is_active ? '#eab308' : '#22c55e' }}; cursor: pointer; border-radius: 6px;" title="{{ $faq->is_active ? 'Click to hide' : 'Click to publish' }}">
                                    {{ $faq->is_active ? 'Hide' : 'Publish' }}
                                </button>
                            </form>

                            <!-- EDIT LINK -->
                            <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                <span>Edit</span>
                            </a>

                            <!-- DELETE -->
                            <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ question permanently?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm" style="padding: 6px 10px; font-size: 12px; color: #f87171; border-color: rgba(239, 68, 68, 0.2); cursor: pointer;" title="Delete FAQ">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- ANSWER PREVIEW -->
                    <div style="background: rgba(0, 0, 0, 0.3); border-radius: 8px; padding: 14px 16px; font-size: 13px; color: #cbd5e1; line-height: 1.6; border: 1px solid rgba(255, 255, 255, 0.05); white-space: pre-wrap;">{{ $faq->answer }}</div>
                </div>
            @empty
                <div style="background: var(--color-dark-card); border: 1px solid var(--color-dark-border); border-radius: var(--radius-lg); padding: 40px; text-align: center;">
                    <div style="font-size: 32px; margin-bottom: 12px;">❓</div>
                    <h3 style="font-family: var(--font-clash); font-size: 18px; color: #fff; margin-bottom: 6px;">No FAQs found</h3>
                    <p style="color: var(--color-text-secondary); font-size: 13px;">Add your first question using the form on the left.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentCategory = 'all';

    function filterAdminFaqs() {
        const query = (document.getElementById('adminFaqSearch').value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.faq-item-card');

        cards.forEach(card => {
            const text = card.getAttribute('data-text') || '';
            const category = card.getAttribute('data-category') || '';

            const matchesCategory = (currentCategory === 'all' || category === currentCategory);
            const matchesQuery = (!query || text.includes(query));

            if (matchesCategory && matchesQuery) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function filterCategory(categorySlug, buttonEl) {
        currentCategory = categorySlug;

        // Reset pill styles
        document.querySelectorAll('#categoryFilterPills .category-pill').forEach(btn => {
            btn.style.border = '1px solid var(--color-dark-border)';
            btn.style.background = 'rgba(255,255,255,0.03)';
            btn.style.color = 'var(--color-text-secondary)';
        });

        // Set active style
        buttonEl.style.border = '1px solid var(--color-secondary)';
        buttonEl.style.background = 'rgba(114, 33, 255, 0.25)';
        buttonEl.style.color = '#fff';

        filterAdminFaqs();
    }
</script>
@endpush
@endsection

