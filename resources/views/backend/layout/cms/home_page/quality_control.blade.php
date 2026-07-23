@extends('backend.master')
@section('title', 'CMS | Quality Control Section')

@push('styles-top')
<style>
    /* ── Section-level banner ─────────────────────────────────── */
    .qc-hero {
        background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
        border-radius: 14px;
        padding: 28px 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .qc-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .qc-hero .hero-title   { color: #fff; font-size: 1.35rem; font-weight: 700; margin-bottom: 4px; }
    .qc-hero .hero-subtitle{ color: rgba(255,255,255,.65); font-size: .875rem; margin: 0; }
    .qc-hero .hero-icon    { font-size: 3.5rem; color: rgba(255,255,255,.15); }

    /* ── Card overrides ───────────────────────────────────────── */
    .qc-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        transition: box-shadow .2s;
    }
    .qc-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.10); }

    .qc-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 20px 14px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    .qc-card .card-header .header-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .qc-card .card-header .header-icon.green  { background: #d1fae5; color: #059669; }
    .qc-card .card-header .header-icon.blue   { background: #dbeafe; color: #2563eb; }
    .qc-card .card-header .header-icon.orange { background: #ffedd5; color: #ea580c; }
    .qc-card .card-header .header-icon.purple { background: #ede9fe; color: #7c3aed; }
    .qc-card .card-header .header-icon.teal   { background: #ccfbf1; color: #0d9488; }
    .qc-card .card-body { padding: 20px; }

    /* ── Feature-card badge strip ─────────────────────────────── */
    .feature-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .4px;
        text-transform: uppercase;
        margin-bottom: 16px;
    }
    .feature-badge.badge-1 { background: #dbeafe; color: #2563eb; }
    .feature-badge.badge-2 { background: #f3e8ff; color: #7c3aed; }
    .feature-badge.badge-3 { background: #fef9c3; color: #a16207; }

    /* ── Inputs ───────────────────────────────────────────────── */
    .qc-input, .qc-textarea {
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: .875rem;
        transition: border-color .2s, box-shadow .2s;
        background: #fcfcfc;
    }
    .qc-input:focus, .qc-textarea:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13,148,136,.12);
        background: #fff;
    }
    .qc-label {
        font-size: .8rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
        letter-spacing: .3px;
        text-transform: uppercase;
    }

    /* ── Highlight badge ──────────────────────────────────────── */
    .hl-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        background: #d1fae5;
        color: #059669;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
    }

    /* ── Live preview box ─────────────────────────────────────── */
    .preview-box {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 1.5px dashed #cbd5e1;
        border-radius: 10px;
        padding: 14px 16px;
        margin-top: 14px;
    }
    .preview-box .preview-label {
        font-size: .7rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 6px;
    }

    /* ── Tips sidebar ─────────────────────────────────────────── */
    .tip-card {
        border-radius: 12px;
        border: 1.5px solid #e0f2fe;
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    }
    .tip-card .tip-icon {
        width: 34px; height: 34px;
        background: #bae6fd;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: #0284c7;
        font-size: 1rem;
        margin-bottom: 12px;
    }
    .tip-card ul li { margin-bottom: 8px; font-size: .82rem; color: #475569; }

    /* ── Image upload zone ────────────────────────────────────── */
    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        background: #f8fafc;
    }
    .upload-zone:hover { border-color: #0d9488; background: #f0fdfa; }
    .upload-zone .upload-icon { font-size: 2rem; color: #94a3b8; margin-bottom: 8px; }
    .upload-zone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }

    /* ── Save button ──────────────────────────────────────────── */
    .btn-save {
        background: linear-gradient(135deg, #0d9488, #0f766e);
        border: none;
        color: #fff;
        padding: 10px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: .9rem;
        box-shadow: 0 4px 12px rgba(13,148,136,.35);
        transition: transform .15s, box-shadow .15s;
    }
    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(13,148,136,.45);
        color: #fff;
    }
    .btn-save:active { transform: translateY(0); }
</style>
@endpush

@section('content')

    {{-- ── Breadcrumb ── --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">CMS &mdash; Home Page</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">CMS</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Home Page</a></li>
                    <li class="breadcrumb-item active">Quality Control</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ── Hero banner ── --}}
    <div class="qc-hero">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <p class="hero-title mb-1">
                    <i class="ri-shield-check-line me-2"></i>Quality Control Section
                </p>
                <p class="hero-subtitle">
                    Edit the left-side heading &amp; image, and the three feature cards shown on the homepage.
                </p>
            </div>
            <i class="ri-microscope-line hero-icon d-none d-md-block"></i>
        </div>
    </div>

    {{-- ── Form ── --}}
    <form method="POST"
          action="{{ route('backend.home-page.quality-control.update') }}"
          enctype="multipart/form-data"
          class="row g-4">
        @csrf
        @method('PUT')

        {{-- ═══════════════ LEFT COLUMN ═══════════════ --}}
        <div class="col-lg-8">

            {{-- ── Card: Left-side Content ── --}}
            <div class="card qc-card mb-4">
                <div class="card-header">
                    <div class="header-icon green"><i class="ri-text-wrap"></i></div>
                    <div>
                        <h5 class="card-title mb-0">Left-Side Content</h5>
                        <small class="text-muted">Heading, highlight word, description and section image.</small>
                    </div>
                </div>
                <div class="card-body">

                    {{-- Title --}}
                    <div class="mb-4">
                        <label class="qc-label" for="qc-title-input">
                            Section Title
                        </label>
                        <input type="text" name="title" id="qc-title-input"
                               value="{{ old('title', $section->title ?? '') }}"
                               class="form-control qc-input @error('title') is-invalid @enderror"
                               placeholder="e.g. Uncompromising quality control.">
                        @error('title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- Title Highlight --}}
                    <div class="mb-3">
                        <label class="qc-label" for="qc-title-highlight-input">
                            Title Highlight Word &nbsp;<span class="hl-badge"><i class="ri-magic-line"></i> Green Italic</span>
                        </label>
                        <input type="text" name="title_highlight" id="qc-title-highlight-input"
                               value="{{ old('title_highlight', $section->title_highlight ?? '') }}"
                               class="form-control qc-input @error('title_highlight') is-invalid @enderror"
                               placeholder="e.g. quality control.">
                        <small class="text-muted d-block mt-1">
                            <i class="ri-information-line text-primary"></i>
                            Type the <strong>exact word(s)</strong> from the title that should be styled in
                            <span class="text-success fw-semibold">green italic</span> on the front-end.
                        </small>
                        @error('title_highlight')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                        {{-- Live preview --}}
                        <div class="preview-box" id="qc-title-preview-box" style="display:none;">
                            <div class="preview-label"><i class="ri-eye-line me-1"></i>Live Preview</div>
                            <div id="qc-title-preview" class="fw-bold fs-5"></div>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Description --}}
                    <div class="mb-0">
                        <label class="qc-label" for="qc-description-input">Section Description</label>
                        <textarea name="description" id="qc-description-input"
                                  class="form-control qc-textarea @error('description') is-invalid @enderror"
                                  rows="3"
                                  placeholder="e.g. Every formula is rigorously tested at multiple stages to ensure absolute purity and potency...">{{ old('description', $section->description ?? '') }}</textarea>
                        @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>

            {{-- ── Feature Cards ── --}}
            @php
                $cardMeta = [
                    1 => [
                        'label'   => 'Feature Card 1',
                        'hint'    => 'e.g. Third-Party Tested',
                        'desc_ph' => 'e.g. Independently tested for purity, potency, and safety before it reaches your door.',
                        'hl_ph'   => 'e.g. purity, potency, and safety',
                        'icon'    => 'ri-test-tube-line',
                        'badge'   => 'badge-1',
                        'hicon'   => 'blue',
                    ],
                    2 => [
                        'label'   => 'Feature Card 2',
                        'hint'    => 'e.g. Clinically Studied',
                        'desc_ph' => 'e.g. Active ingredients in exact dosages proven effective in clinical research.',
                        'hl_ph'   => 'e.g. in exact dosages proven effective',
                        'icon'    => 'ri-heart-pulse-line',
                        'badge'   => 'badge-2',
                        'hicon'   => 'purple',
                    ],
                    3 => [
                        'label'   => 'Feature Card 3',
                        'hint'    => 'e.g. Clean Formulation',
                        'desc_ph' => 'e.g. Maximum absorption without synthetic fillers, artificial colors, or common allergens.',
                        'hl_ph'   => 'e.g. without synthetic fillers, artificial colors',
                        'icon'    => 'ri-leaf-line',
                        'badge'   => 'badge-3',
                        'hicon'   => 'orange',
                    ],
                ];
            @endphp

            @foreach ($cardMeta as $n => $meta)
            <div class="card qc-card mb-4">
                <div class="card-header">
                    <div class="header-icon {{ $meta['hicon'] }}">
                        <i class="{{ $meta['icon'] }}"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">{{ $meta['label'] }}</h5>
                        <small class="text-muted">{{ $meta['hint'] }}</small>
                    </div>
                </div>
                <div class="card-body">
                    <span class="feature-badge {{ $meta['badge'] }}">
                        <i class="{{ $meta['icon'] }}"></i> Card {{ $n }}
                    </span>

                    {{-- Card Title --}}
                    <div class="mb-3">
                        <label class="qc-label" for="card{{ $n }}-title">Card Title</label>
                        <input type="text" name="card{{ $n }}_title" id="card{{ $n }}-title"
                               value="{{ old('card'.$n.'_title', $section->{'card'.$n.'_title'} ?? '') }}"
                               class="form-control qc-input @error('card'.$n.'_title') is-invalid @enderror"
                               placeholder="{{ $meta['hint'] }}">
                        @error('card'.$n.'_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- Card Description --}}
                    <div class="mb-3">
                        <label class="qc-label" for="card{{ $n }}-desc">Card Description</label>
                        <textarea name="card{{ $n }}_description" id="card{{ $n }}-desc"
                                  class="form-control qc-textarea @error('card'.$n.'_description') is-invalid @enderror"
                                  rows="2"
                                  placeholder="{{ $meta['desc_ph'] }}">{{ old('card'.$n.'_description', $section->{'card'.$n.'_description'} ?? '') }}</textarea>
                        @error('card'.$n.'_description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- Highlight Phrase --}}
                    <div class="mb-0">
                        <label class="qc-label" for="card{{ $n }}-hl">
                            Highlight Phrase &nbsp;<span class="hl-badge"><i class="ri-paint-brush-line"></i> Green</span>
                        </label>
                        <input type="text" name="card{{ $n }}_description_highlight" id="card{{ $n }}-hl"
                               value="{{ old('card'.$n.'_description_highlight', $section->{'card'.$n.'_description_highlight'} ?? '') }}"
                               class="form-control qc-input @error('card'.$n.'_description_highlight') is-invalid @enderror"
                               placeholder="{{ $meta['hl_ph'] }}">
                        <small class="text-muted d-block mt-1">
                            <i class="ri-information-line text-primary"></i>
                            Paste the exact phrase from the description above that should appear in
                            <span class="text-success fw-semibold">green</span>.
                        </small>
                        @error('card'.$n.'_description_highlight')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>
            @endforeach

            {{-- ── Submit ── --}}
            <div class="d-flex justify-content-end mb-5">
                <button type="submit" class="btn btn-save">
                    <i class="ri-save-3-line me-2"></i> Save Changes
                </button>
            </div>

        </div>
        {{-- /LEFT COLUMN --}}

        {{-- ═══════════════ RIGHT COLUMN ═══════════════ --}}
        <div class="col-lg-4">

            {{-- ── Image Upload ── --}}
            <div class="card qc-card mb-4">
                <div class="card-header">
                    <div class="header-icon teal"><i class="ri-image-add-line"></i></div>
                    <div>
                        <h5 class="card-title mb-0">Section Image</h5>
                        <small class="text-muted">Doctor / lab photo (portrait, 600&times;700 px recommended).</small>
                    </div>
                </div>
                <div class="card-body">

                    <div class="upload-zone position-relative mb-3" id="upload-zone">
                        <input type="file" name="image" id="qc-image-input" accept="image/*">
                        <div id="upload-placeholder">
                            <div class="upload-icon"><i class="ri-upload-cloud-2-line"></i></div>
                            <p class="mb-1 text-muted small fw-semibold">Click or drag &amp; drop to upload</p>
                            <p class="mb-0 text-muted" style="font-size:.75rem;">PNG, JPG, WEBP &bull; Max 4 MB</p>
                        </div>
                        <div id="upload-preview" style="display:none;">
                            <img id="upload-preview-img" src="" alt="Preview"
                                 class="img-fluid rounded" style="max-height:220px; object-fit:cover; width:100%;">
                            <p class="text-muted small mt-2 mb-0 text-center" id="upload-file-name"></p>
                        </div>
                    </div>

                    @error('image')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                    @if (!empty($section->image))
                        <div class="p-2 bg-light rounded">
                            <p class="text-muted small fw-semibold mb-2"><i class="ri-image-line me-1"></i>Current Image</p>
                            <img src="{{ asset($section->image) }}" alt="Quality Control Image"
                                 class="img-fluid rounded shadow-sm" style="max-height:180px; width:100%; object-fit:cover;">
                            <p class="text-muted mt-1 mb-0" style="font-size:.72rem;">Upload a new file above to replace this image.</p>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ── Tips ── --}}
            <div class="card tip-card mb-4">
                <div class="card-body p-4">
                    <div class="tip-icon"><i class="ri-lightbulb-flash-line"></i></div>
                    <h6 class="fw-semibold mb-3">Tips &amp; Notes</h6>
                    <ul class="ps-3 mb-0">
                        <li>The <strong>Title Highlight</strong> word(s) will appear <span class="text-success fw-semibold">green &amp; italic</span> on the homepage.</li>
                        <li>Each <strong>Card Highlight Phrase</strong> must be an <em>exact</em> substring of the card's description text.</li>
                        <li>Recommended image: <strong>600 × 700 px</strong> portrait orientation.</li>
                        <li>All fields are optional — blank fields won't display on the front-end.</li>
                    </ul>
                </div>
            </div>

            {{-- ── Section Preview Guide ── --}}
            <div class="card qc-card border-0" style="background: linear-gradient(135deg,#fafafa,#f1f5f9);">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3 text-muted"><i class="ri-layout-5-line me-1"></i>Section Layout</h6>
                    <div class="d-flex gap-2">
                        <div class="flex-grow-1 p-2 rounded text-center" style="background:#e0f2fe; border:1.5px solid #7dd3fc; font-size:.72rem; color:#0284c7; font-weight:600;">
                            Left<br><span class="fw-normal">Title &amp; Image</span>
                        </div>
                        <div style="width:2px; background:#e2e8f0; border-radius:4px;"></div>
                        <div class="flex-grow-1 p-2 rounded text-center" style="background:#f3e8ff; border:1.5px solid #c4b5fd; font-size:.72rem; color:#7c3aed; font-weight:600;">
                            Right<br><span class="fw-normal">3 Cards</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        {{-- /RIGHT COLUMN --}}

    </form>

@endsection

@push('scripts-bottom')
<script>
    // ── Live title preview ────────────────────────────────────────
    function updateQcTitlePreview() {
        const title     = document.getElementById('qc-title-input').value.trim();
        const highlight = document.getElementById('qc-title-highlight-input').value.trim();
        const box       = document.getElementById('qc-title-preview-box');
        const preview   = document.getElementById('qc-title-preview');

        if (!title) { box.style.display = 'none'; return; }
        box.style.display = 'block';

        if (highlight && title.includes(highlight)) {
            const escaped = highlight.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            preview.innerHTML = title.replace(
                new RegExp(escaped, 'g'),
                `<span style="color:#059669;font-style:italic;">${highlight}</span>`
            );
        } else {
            preview.innerHTML = title;
        }
    }

    document.getElementById('qc-title-input').addEventListener('input', updateQcTitlePreview);
    document.getElementById('qc-title-highlight-input').addEventListener('input', updateQcTitlePreview);
    updateQcTitlePreview(); // run on page-load (edit mode)

    // ── Image upload preview ──────────────────────────────────────
    document.getElementById('qc-image-input').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('upload-placeholder').style.display = 'none';
            document.getElementById('upload-preview').style.display     = 'block';
            document.getElementById('upload-preview-img').src            = e.target.result;
            document.getElementById('upload-file-name').textContent      = file.name;
        };
        reader.readAsDataURL(file);
    });

    // ── Drag-over highlight ───────────────────────────────────────
    const zone = document.getElementById('upload-zone');
    zone.addEventListener('dragover',  e => { e.preventDefault(); zone.style.borderColor = '#0d9488'; zone.style.background = '#f0fdfa'; });
    zone.addEventListener('dragleave', ()  => { zone.style.borderColor = '#cbd5e1'; zone.style.background = '#f8fafc'; });
    zone.addEventListener('drop',      e => { zone.style.borderColor = '#cbd5e1'; zone.style.background = '#f8fafc'; });
</script>
@endpush
