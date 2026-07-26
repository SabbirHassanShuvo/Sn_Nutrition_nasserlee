@extends('backend.master')
@section('title', 'CMS | About Us Section')

@push('styles-top')
<style>
    /* ── Section-level banner ─────────────────────────────────── */
    .about-hero {
        background: linear-gradient(135deg, #15803d 0%, #166534 100%);
        border-radius: 14px;
        padding: 28px 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .about-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .about-hero .hero-title   { color: #fff; font-size: 1.35rem; font-weight: 700; margin-bottom: 4px; }
    .about-hero .hero-subtitle{ color: rgba(255,255,255,.65); font-size: .875rem; margin: 0; }
    .about-hero .hero-icon    { font-size: 3.5rem; color: rgba(255,255,255,.15); }

    /* ── Card overrides ───────────────────────────────────────── */
    .about-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        transition: box-shadow .2s;
    }
    .about-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.10); }

    .about-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 20px 14px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    .about-card .card-header .header-icon {
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
    .about-card .card-header .header-icon.green  { background: #d1fae5; color: #059669; }
    .about-card .card-header .header-icon.blue   { background: #dbeafe; color: #2563eb; }
    .about-card .card-header .header-icon.orange { background: #ffedd5; color: #ea580c; }
    .about-card .card-header .header-icon.purple { background: #ede9fe; color: #7c3aed; }
    .about-card .card-header .header-icon.teal   { background: #ccfbf1; color: #0d9488; }
    .about-card .card-body { padding: 20px; }

    /* ── Inputs ───────────────────────────────────────────────── */
    .about-input, .about-textarea {
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: .875rem;
        transition: border-color .2s, box-shadow .2s;
        background: #fcfcfc;
    }
    .about-input:focus, .about-textarea:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,.12);
        background: #fff;
    }
    .about-label {
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
    .upload-zone:hover { border-color: #16a34a; background: #f0fdf4; }
    .upload-zone .upload-icon { font-size: 2rem; color: #94a3b8; margin-bottom: 8px; }
    .upload-zone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }

    /* ── Save button ──────────────────────────────────────────── */
    .btn-save {
        background: linear-gradient(135deg, #16a34a, #15803d);
        border: none;
        color: #fff;
        padding: 10px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: .9rem;
        box-shadow: 0 4px 12px rgba(22,163,74,.35);
        transition: transform .15s, box-shadow .15s;
    }
    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(22,163,74,.45);
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
                <h4 class="mb-sm-0">CMS &mdash; About Us Page</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">CMS</a></li>
                    <li class="breadcrumb-item active">About Us</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ── Hero banner ── --}}
    <div class="about-hero">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <p class="hero-title mb-1">
                    <i class="ri-pages-line me-2"></i>About Us Page CMS
                </p>
                <p class="hero-subtitle">
                    Configure the Our Story section, Mission, stats, Product Standards, and Brand values shown on the About Us page.
                </p>
            </div>
            <i class="ri-heart-pulse-line hero-icon d-none d-md-block"></i>
        </div>
    </div>

    {{-- ── Form ── --}}
    <form method="POST"
          action="{{ route('backend.about-us.update') }}"
          enctype="multipart/form-data"
          class="row g-4">
        @csrf
        @method('PUT')

        {{-- ═══════════════ LEFT COLUMN ═══════════════ --}}
        <div class="col-md-12">

            {{-- ── Card: Our Story (Top Section) ── --}}
            <div class="card about-card mb-4">
                <div class="card-header">
                    <div class="header-icon green"><i class="ri-history-line"></i></div>
                    <div>
                        <h5 class="card-title mb-0">1. Our Story (Top Section)</h5>
                        <small class="text-muted">Heading, badge description, highlight word, and side image.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="about-label" for="story_badge">Story Badge Label</label>
                            <input type="text" name="story_badge" id="story_badge"
                                   value="{{ old('story_badge', $section->story_badge ?? '') }}"
                                   class="form-control about-input @error('story_badge') is-invalid @enderror"
                                   placeholder="e.g. Our Story">
                            @error('story_badge')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="about-label" for="story_title">Story Title</label>
                            <input type="text" name="story_title" id="story_title"
                                   value="{{ old('story_title', $section->story_title ?? '') }}"
                                   class="form-control about-input @error('story_title') is-invalid @enderror"
                                   placeholder="e.g. Nutrition you can trust">
                            @error('story_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="about-label" for="story_title_highlight">
                            Title Highlight Word &nbsp;<span class="hl-badge"><i class="ri-magic-line"></i> Green Italic</span>
                        </label>
                        <input type="text" name="story_title_highlight" id="story_title_highlight"
                               value="{{ old('story_title_highlight', $section->story_title_highlight ?? '') }}"
                               class="form-control about-input @error('story_title_highlight') is-invalid @enderror"
                               placeholder="e.g. trust">
                        @error('story_title_highlight')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-0">
                        <label class="about-label" for="story_description">Story Description Text</label>
                        <textarea name="story_description" id="story_description"
                                  class="form-control about-textarea @error('story_description') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Provide story details...">{{ old('story_description', $section->story_description ?? '') }}</textarea>
                        @error('story_description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-0 mt-2">
                        <label class="about-label" for="story_image">Story Section Image</label>
                        <div class="upload-zone position-relative mb-3" id="upload-zone">
                            <input type="file" name="story_image" id="story-image-input" accept="image/*">
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

                        @error('story_image')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                        @if (!empty($section->story_image))
                            <div class="p-2 bg-light rounded">
                                <p class="text-muted small fw-semibold mb-2"><i class="ri-image-line me-1"></i>Current Image</p>
                                <img src="{{ asset($section->story_image) }}" alt="Story Image"
                                    class="img-fluid rounded shadow-sm" style="max-height:180px; width:100%; object-fit:cover;">
                                <p class="text-muted mt-1 mb-0" style="font-size:.72rem;">Upload a new file above to replace this image.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Card: Our Mission & Stats ── --}}
            <div class="card about-card mb-4">
                <div class="card-header">
                    <div class="header-icon blue"><i class="ri-compass-3-line"></i></div>
                    <div>
                        <h5 class="card-title mb-0">2. Our Mission &amp; Stats</h5>
                        <small class="text-muted">Mission description and the 4 statistics cards.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="about-label" for="mission_title">Mission Section Title</label>
                            <input type="text" name="mission_title" id="mission_title"
                                   value="{{ old('mission_title', $section->mission_title ?? '') }}"
                                   class="form-control about-input @error('mission_title') is-invalid @enderror"
                                   placeholder="e.g. Our Mission">
                            @error('mission_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="about-label" for="mission_title_highlight">Mission Highlight</label>
                            <input type="text" name="mission_title_highlight" id="mission_title_highlight"
                                   value="{{ old('mission_title_highlight', $section->mission_title_highlight ?? '') }}"
                                   class="form-control about-input @error('mission_title_highlight') is-invalid @enderror"
                                   placeholder="e.g. Mission">
                            @error('mission_title_highlight')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="about-label" for="mission_description">Mission Description Text</label>
                        <textarea name="mission_description" id="mission_description"
                                  class="form-control about-textarea @error('mission_description') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Provide mission details...">{{ old('mission_description', $section->mission_description ?? '') }}</textarea>
                        @error('mission_description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-semibold mb-3 text-muted"><i class="ri-bar-chart-box-line me-1"></i>Statistics Cards (4 items)</h6>

                    <div class="row g-3">
                        {{-- Stat 1 --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <label class="about-label">Stat 1 Value</label>
                                <input type="text" name="stat1_value" value="{{ old('stat1_value', $section->stat1_value ?? '') }}" class="form-control about-input mb-2" placeholder="e.g. 4,200+">
                                <label class="about-label">Stat 1 Label</label>
                                <input type="text" name="stat1_label" value="{{ old('stat1_label', $section->stat1_label ?? '') }}" class="form-control about-input" placeholder="e.g. Health pros">
                            </div>
                        </div>
                        {{-- Stat 2 --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <label class="about-label">Stat 2 Value</label>
                                <input type="text" name="stat2_value" value="{{ old('stat2_value', $section->stat2_value ?? '') }}" class="form-control about-input mb-2" placeholder="e.g. $8.4M">
                                <label class="about-label">Stat 2 Label</label>
                                <input type="text" name="stat2_label" value="{{ old('stat2_label', $section->stat2_label ?? '') }}" class="form-control about-input" placeholder="e.g. Paid out in 2024">
                            </div>
                        </div>
                        {{-- Stat 3 --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <label class="about-label">Stat 3 Value</label>
                                <input type="text" name="stat3_value" value="{{ old('stat3_value', $section->stat3_value ?? '') }}" class="form-control about-input mb-2" placeholder="e.g. 98.6%">
                                <label class="about-label">Stat 3 Label</label>
                                <input type="text" name="stat3_label" value="{{ old('stat3_label', $section->stat3_label ?? '') }}" class="form-control about-input" placeholder="e.g. On-time payouts">
                            </div>
                        </div>
                        {{-- Stat 4 --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <label class="about-label">Stat 4 Value</label>
                                <input type="text" name="stat4_value" value="{{ old('stat4_value', $section->stat4_value ?? '') }}" class="form-control about-input mb-2" placeholder="e.g. 25%">
                                <label class="about-label">Stat 4 Label</label>
                                <input type="text" name="stat4_label" value="{{ old('stat4_label', $section->stat4_label ?? '') }}" class="form-control about-input" placeholder="e.g. Top commission">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Card: Product Standards ── --}}
            <div class="card about-card mb-4">
                <div class="card-header">
                    <div class="header-icon teal"><i class="ri-shield-star-line"></i></div>
                    <div>
                        <h5 class="card-title mb-0">3. Our Product Standards</h5>
                        <small class="text-muted">Heading and 4 Product Standard cards with icons.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="about-label" for="standards_title">Section Title</label>
                            <input type="text" name="standards_title" id="standards_title"
                                   value="{{ old('standards_title', $section->standards_title ?? '') }}"
                                   class="form-control about-input" placeholder="e.g. Our Product Standards">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="about-label" for="standards_title_highlight">Highlight Word</label>
                            <input type="text" name="standards_title_highlight" id="standards_title_highlight"
                                   value="{{ old('standards_title_highlight', $section->standards_title_highlight ?? '') }}"
                                   class="form-control about-input" placeholder="e.g. Standards">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="about-label" for="standards_description">Section Description</label>
                        <input type="text" name="standards_description" id="standards_description"
                               value="{{ old('standards_description', $section->standards_description ?? '') }}"
                               class="form-control about-input" placeholder="e.g. Every product meets strict quality standards...">
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-semibold mb-3 text-muted"><i class="ri-grid-fill me-1"></i>Standards Items (4 cards)</h6>

                    @for ($i = 1; $i <= 4; $i++)
                        <div class="p-3 bg-light rounded mb-3 border-start border-4 border-success">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="fw-semibold mb-0">Standard Item {{ $i }}</h6>
                            </div>
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="about-label">Title</label>
                                    <input type="text" name="standard{{ $i }}_title" value="{{ old('standard'.$i.'_title', $section->{"standard".$i."_title"} ?? '') }}" class="form-control about-input" placeholder="e.g. Halal Certified">
                                </div>
                                <div class="col-12 mt-2">
                                    <label class="about-label">Description</label>
                                    <input type="text" name="standard{{ $i }}_description" value="{{ old('standard'.$i.'_description', $section->{"standard".$i."_description"} ?? '') }}" class="form-control about-input" placeholder="Enter standard description...">
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- ── Card: What We Stand For ── --}}
            <div class="card about-card mb-4">
                <div class="card-header">
                    <div class="header-icon purple"><i class="ri-focus-3-line"></i></div>
                    <div>
                        <h5 class="card-title mb-0">4. What We Stand For</h5>
                        <small class="text-muted">Section title and 4 value cards with descriptions.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="about-label" for="stand_title">Section Title</label>
                            <input type="text" name="stand_title" id="stand_title"
                                   value="{{ old('stand_title', $section->stand_title ?? '') }}"
                                   class="form-control about-input" placeholder="e.g. What We Stand For">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="about-label" for="stand_title_highlight">Highlight Word</label>
                            <input type="text" name="stand_title_highlight" id="stand_title_highlight"
                                   value="{{ old('stand_title_highlight', $section->stand_title_highlight ?? '') }}"
                                   class="form-control about-input" placeholder="e.g. Stand For">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="about-label" for="stand_description">Section Description</label>
                        <input type="text" name="stand_description" id="stand_description"
                               value="{{ old('stand_description', $section->stand_description ?? '') }}"
                               class="form-control about-input" placeholder="e.g. The principles that guide every product we make.">
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-semibold mb-3 text-muted"><i class="ri-grid-fill me-1"></i>Stand For Items (4 cards)</h6>

                    @for ($i = 1; $i <= 4; $i++)
                        <div class="p-3 bg-light rounded mb-3 border-start border-4 border-primary">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="fw-semibold mb-0">Stand For Item {{ $i }}</h6>
                            </div>
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="about-label">Title</label>
                                    <input type="text" name="stand{{ $i }}_title" value="{{ old('stand'.$i.'_title', $section->{"stand".$i."_title"} ?? '') }}" class="form-control about-input" placeholder="e.g. Science-Backed">
                                </div>
                                <div class="col-12 mt-2">
                                    <label class="about-label">Description</label>
                                    <input type="text" name="stand{{ $i }}_description" value="{{ old('stand'.$i.'_description', $section->{"stand".$i."_description"} ?? '') }}" class="form-control about-input" placeholder="Enter description...">
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- ── Submit ── --}}
            <div class="d-flex justify-content-end mb-5">
                <button type="submit" class="btn btn-save">
                    <i class="ri-save-3-line me-2"></i> Save Changes
                </button>
            </div>

        </div>
        {{-- /LEFT COLUMN --}}
    </form>

@endsection

@push('scripts-bottom')
<script>
    // ── Image upload preview ──────────────────────────────────────
    document.getElementById('story-image-input').addEventListener('change', function () {
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
    zone.addEventListener('dragover',  e => { e.preventDefault(); zone.style.borderColor = '#16a34a'; zone.style.background = '#f0fdf4'; });
    zone.addEventListener('dragleave', ()  => { zone.style.borderColor = '#cbd5e1'; zone.style.background = '#f8fafc'; });
    zone.addEventListener('drop',      e => { zone.style.borderColor = '#cbd5e1'; zone.style.background = '#f8fafc'; });
</script>
@endpush
