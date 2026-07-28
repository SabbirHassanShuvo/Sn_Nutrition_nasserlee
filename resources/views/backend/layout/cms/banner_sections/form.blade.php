@extends('backend.master')
@section('title', 'Dashboard | Banner Form')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-sm-0">{{ isset($bannerSection) ? 'Edit Banner' : 'Create Banner' }}</h4>
                    <a href="{{ route('backend.banner-section.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">CMS</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('backend.banner-section.index') }}">Banners</a></li>
                        <li class="breadcrumb-item active">{{ isset($bannerSection) ? 'Edit' : 'Create' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form method="POST"
          action="{{ isset($bannerSection) ? route('backend.banner-section.update', $bannerSection->id) : route('backend.banner-section.store') }}"
          class="row"
          enctype="multipart/form-data">
        @csrf
        @if (isset($bannerSection))
            @method('PATCH')
        @endif

        {{-- ===================== LEFT COLUMN ===================== --}}
        <div class="col-lg-8">

            {{-- ── Card 1: Basic Info ── --}}
            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0">Basic Information</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="banner-badge-input">Small Badge Text</label>
                                <input type="text" name="small_badge"
                                       value="{{ old('small_badge', $bannerSection->small_badge ?? '') }}"
                                       class="form-control @error('small_badge') is-invalid @enderror"
                                       id="banner-badge-input"
                                       placeholder="e.g. Clinically Trusted Supplements">
                                @error('small_badge')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="button-text-input">Button Text</label>
                                <input type="text" name="button_text"
                                       value="{{ old('button_text', $bannerSection->button_text ?? '') }}"
                                       class="form-control @error('button_text') is-invalid @enderror"
                                       id="button-text-input"
                                       placeholder="e.g. Order Now">
                                @error('button_text')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Card 2: Title ── --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Banner Title</h5>
                    <small class="text-muted">Write the full title, then specify which word should be highlighted in green italic.</small>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="banner-title-input">
                            Full Title <span class="text-danger">*</span>
                            <span id="title-word-count" class="badge bg-secondary ms-2">0 / 4 words</span>
                        </label>
                        <input type="text" name="title"
                               value="{{ old('title', $bannerSection->title ?? '') }}"
                               class="form-control @error('title') is-invalid @enderror"
                               id="banner-title-input"
                               placeholder="e.g. Trusted Experts Proven Wellness">
                        <div id="title-word-error" class="text-danger" style="font-size:.85em; display:none;">
                            <i class="mdi mdi-alert-circle-outline"></i>
                            Maximum 4 words allowed in the title.
                        </div>
                        @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label" for="title-highlight-input">
                            Highlight Word
                            <span class="badge bg-success-subtle text-success ms-1">Green Italic</span>
                        </label>
                        <input type="text" name="title_highlight"
                               value="{{ old('title_highlight', $bannerSection->title_highlight ?? '') }}"
                               class="form-control @error('title_highlight') is-invalid @enderror"
                               id="title-highlight-input"
                               placeholder="e.g. Experts  (must be an exact word from the title above)">
                        <small class="text-muted">
                            <i class="mdi mdi-information-outline"></i>
                            Type the exact word from the title that should appear in
                            <strong class="text-success">green italic</strong>.
                        </small>
                        @error('title_highlight')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>

                    {{-- Live preview --}}
                    <div class="mt-3 p-3 bg-light rounded" id="title-preview-box" style="display:none;">
                        <label class="form-label text-muted mb-1">Preview:</label>
                        <div id="title-preview" class="fw-bold fs-5"></div>
                    </div>
                </div>
            </div>

            {{-- ── Card 3: Description ── --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Banner Description</h5>
                    <small class="text-muted">Write the full description, then paste the phrase that should be highlighted in green.</small>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="banner-description-input">Full Description</label>
                        <textarea name="description" id="banner-description-input"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="3"
                                  placeholder="e.g. Experience premium wellness products designed to nourish your body...">{{ old('description', $bannerSection->description ?? '') }}</textarea>
                        @error('description')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label" for="description-highlight-input">
                            Highlight Phrase
                            <span class="badge bg-success-subtle text-success ms-1">Green</span>
                        </label>
                        <input type="text" name="description_highlight"
                               value="{{ old('description_highlight', $bannerSection->description_highlight ?? '') }}"
                               class="form-control @error('description_highlight') is-invalid @enderror"
                               id="description-highlight-input"
                               placeholder="e.g. support your lifestyle, and help you thrive every day.">
                        <small class="text-muted">
                            <i class="mdi mdi-information-outline"></i>
                            Paste the exact phrase from the description above that should appear in
                            <strong class="text-success">green</strong>.
                        </small>
                        @error('description_highlight')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>

            {{-- ── Card 4: Key Points ── --}}
            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0">Key Points</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-0">
                                <label class="form-label" for="point-1-input">Point 1</label>
                                <input type="text" name="point_1"
                                       value="{{ old('point_1', $bannerSection->point_1 ?? '') }}"
                                       class="form-control @error('point_1') is-invalid @enderror"
                                       id="point-1-input" placeholder="e.g. Fast Delivery">
                                @error('point_1')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-0">
                                <label class="form-label" for="point-2-input">Point 2</label>
                                <input type="text" name="point_2"
                                       value="{{ old('point_2', $bannerSection->point_2 ?? '') }}"
                                       class="form-control @error('point_2') is-invalid @enderror"
                                       id="point-2-input" placeholder="e.g. Trusted Quality">
                                @error('point_2')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-0">
                                <label class="form-label" for="point-3-input">Point 3</label>
                                <input type="text" name="point_3"
                                       value="{{ old('point_3', $bannerSection->point_3 ?? '') }}"
                                       class="form-control @error('point_3') is-invalid @enderror"
                                       id="point-3-input" placeholder="e.g. Easy Ordering">
                                @error('point_3')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-4">
                <a href="{{ route('backend.banner-section.index') }}" class="btn btn-danger w-sm">Cancel</a>
                <button type="submit" class="btn btn-success w-sm">{{ isset($bannerSection) ? 'Update' : 'Create' }}</button>
            </div>
        </div>

        {{-- ===================== RIGHT COLUMN ===================== --}}
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0">Settings</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="choices-priority-input" class="form-label">
                            Priority / Sort Order <span class="text-danger">*</span>
                            <i class="mdi mdi-information-outline text-muted" data-bs-toggle="tooltip"
                               title="Lower number = shown first in the slider"></i>
                        </label>
                        <input type="number" name="priority"
                               value="{{ old('priority', $bannerSection->priority ?? '1') }}"
                               class="form-control @error('priority') is-invalid @enderror"
                               id="choices-priority-input" min="1">
                        <small class="text-muted">Slide with priority 1 appears first.</small>
                        @error('priority')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="choices-status-input" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" id="choices-status-input">
                            <option value="" disabled {{ !isset($bannerSection) ? 'selected' : '' }}>Select Status</option>
                            @foreach ($status as $key => $value)
                                <option value="{{ $value }}" {{ old('status', $bannerSection->status ?? '') == $value ? 'selected' : '' }}>
                                    {{ ucfirst(strtolower($key)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Banner Image</h5></div>
                <div class="card-body">
                    <input type="file" name="image"
                           class="form-control @error('image') is-invalid @enderror"
                           id="banner-image-input" accept="image/*">
                    @error('image')<small class="text-danger">{{ $message }}</small>@enderror

                    @if (isset($bannerSection) && $bannerSection->image)
                        <div class="mt-3">
                            <label class="form-label d-block text-muted">Current Image:</label>
                            <img src="{{ asset($bannerSection->image) }}" alt="Banner Image"
                                 class="img-thumbnail rounded" style="max-height: 150px;">
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </form>
@endsection

@push('scripts-bottom')
<script>
    // ── Word-count helper ──
    function countWords(str) {
        return str.trim() === '' ? 0 : str.trim().split(/\s+/).length;
    }

    // ── Title: enforce max 4 words ──
    const titleInput     = document.getElementById('banner-title-input');
    const wordCountBadge = document.getElementById('title-word-count');
    const wordError      = document.getElementById('title-word-error');
    const MAX_WORDS      = 4;

    function updateWordCount() {
        const words = countWords(titleInput.value);
        wordCountBadge.textContent = words + ' / ' + MAX_WORDS + ' words';

        if (words > MAX_WORDS) {
            wordCountBadge.className = 'badge bg-danger ms-2';
            titleInput.classList.add('is-invalid');
            wordError.style.display = 'block';
        } else if (words === MAX_WORDS) {
            wordCountBadge.className = 'badge bg-warning text-dark ms-2';
            titleInput.classList.remove('is-invalid');
            wordError.style.display = 'none';
        } else {
            wordCountBadge.className = 'badge bg-secondary ms-2';
            titleInput.classList.remove('is-invalid');
            wordError.style.display = 'none';
        }
    }

    // Prevent typing the 5th word (allow edits within 4 words)
    titleInput.addEventListener('keydown', function (e) {
        const allowedKeys = ['Backspace','Delete','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End','Tab'];
        if (allowedKeys.includes(e.key) || e.ctrlKey || e.metaKey) return;

        const words = countWords(this.value);
        // If already at 4+ words and user is adding a NEW word (typing a space when last char is not space)
        if (words >= MAX_WORDS && e.key === ' ') {
            e.preventDefault();
        }
    });

    titleInput.addEventListener('input', function () {
        updateWordCount();
        updateTitlePreview();
    });

    // Block form submit if title exceeds word limit
    document.querySelector('form').addEventListener('submit', function (e) {
        if (countWords(titleInput.value) > MAX_WORDS) {
            e.preventDefault();
            titleInput.focus();
            titleInput.classList.add('is-invalid');
            wordError.style.display = 'block';
        }
    });

    // ── Live preview for title highlight ──
    function updateTitlePreview() {
        const title     = titleInput.value.trim();
        const highlight = document.getElementById('title-highlight-input').value.trim();
        const box       = document.getElementById('title-preview-box');
        const preview   = document.getElementById('title-preview');

        if (!title) { box.style.display = 'none'; return; }

        box.style.display = 'block';

        if (highlight && title.includes(highlight)) {
            const escaped = highlight.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            preview.innerHTML = title.replace(
                new RegExp(escaped),
                `<span style="color:#00a854; font-style:italic;">${highlight}</span>`
            );
        } else {
            preview.innerHTML = title;
        }
    }

    document.getElementById('title-highlight-input').addEventListener('input', updateTitlePreview);

    // Run on page load (edit mode)
    updateWordCount();
    updateTitlePreview();

    // Bootstrap tooltip init
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el) });
</script>
@endpush
