@extends('backend.master')
@section('title', 'CMS | How It Works Section')

@push('styles-top')
<style>
    /* ── Section-level banner ─────────────────────────────────── */
    .how-hero {
        background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
        border-radius: 14px;
        padding: 28px 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .how-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .how-hero .hero-title   { color: #fff; font-size: 1.35rem; font-weight: 700; margin-bottom: 4px; }
    .how-hero .hero-subtitle{ color: rgba(255,255,255,.65); font-size: .875rem; margin: 0; }
    .how-hero .hero-icon    { font-size: 3.5rem; color: rgba(255,255,255,.15); }

    /* ── Card overrides ───────────────────────────────────────── */
    .how-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        transition: box-shadow .2s;
    }
    .how-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.10); }

    .how-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 20px 14px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    .how-card .card-header .header-icon {
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
    .how-card .card-header .header-icon.green  { background: #d1fae5; color: #059669; }
    .how-card .card-header .header-icon.blue   { background: #dbeafe; color: #2563eb; }
    .how-card .card-header .header-icon.orange { background: #ffedd5; color: #ea580c; }
    .how-card .card-header .header-icon.purple { background: #ede9fe; color: #7c3aed; }
    .how-card .card-header .header-icon.teal   { background: #ccfbf1; color: #0d9488; }
    .how-card .card-body { padding: 20px; }

    /* ── Inputs ───────────────────────────────────────────────── */
    .how-input, .how-textarea {
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: .875rem;
        transition: border-color .2s, box-shadow .2s;
        background: #fcfcfc;
    }
    .how-input:focus, .how-textarea:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15,118,110,.12);
        background: #fff;
    }
    .how-label {
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
        background: #ccfbf1;
        color: #0d9488;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
    }

    /* ── Save button ──────────────────────────────────────────── */
    .btn-save {
        background: linear-gradient(135deg, #0f766e, #115e59);
        border: none;
        color: #fff;
        padding: 10px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: .9rem;
        box-shadow: 0 4px 12px rgba(15,118,110,.35);
        transition: transform .15s, box-shadow .15s;
    }
    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(15,118,110,.45);
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
                <h4 class="mb-sm-0">CMS &mdash; How It Works Page</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">CMS</a></li>
                    <li class="breadcrumb-item active">How It Works</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ── Hero banner ── --}}
    <div class="how-hero">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <p class="hero-title mb-1">
                    <i class="ri-pages-line me-2"></i>How It Works Page CMS
                </p>
                <p class="hero-subtitle">
                    Configure banner details, statistics counters, system features, signup steps, and reward tiers shown on the How It Works page.
                </p>
            </div>
            <i class="ri-settings-4-line hero-icon d-none d-md-block"></i>
        </div>
    </div>

    {{-- ── Form ── --}}
    <form method="POST"
          action="{{ route('backend.how-it-works.update') }}"
          enctype="multipart/form-data"
          class="row g-4">
        @csrf
        @method('PUT')

        <div class="col-md-12">

            {{-- ── Card 0: Banner Section (Image 1) ── --}}
            <div class="card how-card mb-4">
                <div class="card-header">
                    <div class="header-icon orange"><i class="ri-image-line"></i></div>
                    <div>
                        <h5 class="card-title mb-0">0. Banner Section (Image 1)</h5>
                        <small class="text-muted">Define the top hero banner fields, points, highlights, and dynamic values.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="how-label" for="banner_small_badge">Small Badge Label</label>
                            <input type="text" name="banner_small_badge" id="banner_small_badge"
                                   value="{{ old('banner_small_badge', $section->banner_small_badge ?? '') }}"
                                   class="form-control how-input" placeholder="e.g. Trusted by Health Professionals">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="how-label" for="banner_title">Banner Title Text</label>
                            <input type="text" name="banner_title" id="banner_title"
                                   value="{{ old('banner_title', $section->banner_title ?? '') }}"
                                   class="form-control how-input" placeholder="e.g. Make Money From Your Nutrition Tips!">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="how-label" for="banner_title_highlight_1">
                                First Title Highlight Word &nbsp;<span class="hl-badge"><i class="ri-magic-line"></i> Green Italic</span>
                            </label>
                            <input type="text" name="banner_title_highlight_1" id="banner_title_highlight_1"
                                   value="{{ old('banner_title_highlight_1', $section->banner_title_highlight_1 ?? '') }}"
                                   class="form-control how-input" placeholder="e.g. Money">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="how-label" for="banner_title_highlight_2">
                                Second Title Highlight Word &nbsp;<span class="hl-badge"><i class="ri-magic-line"></i> Green Italic</span>
                            </label>
                            <input type="text" name="banner_title_highlight_2" id="banner_title_highlight_2"
                                   value="{{ old('banner_title_highlight_2', $section->banner_title_highlight_2 ?? '') }}"
                                   class="form-control how-input" placeholder="e.g. Nutrition">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="how-label" for="banner_description">Banner Description</label>
                        <textarea name="banner_description" id="banner_description"
                                  class="form-control how-textarea" rows="3"
                                  placeholder="Provide banner description text...">{{ old('banner_description', $section->banner_description ?? '') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="how-label" for="banner_button_text">Button Text</label>
                            <input type="text" name="banner_button_text" id="banner_button_text"
                                   value="{{ old('banner_button_text', $section->banner_button_text ?? '') }}"
                                   class="form-control how-input" placeholder="e.g. Start earning free →">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="how-label" for="banner_point_1">Point 1</label>
                            <input type="text" name="banner_point_1" id="banner_point_1"
                                   value="{{ old('banner_point_1', $section->banner_point_1 ?? '') }}"
                                   class="form-control how-input" placeholder="e.g. Free forever">
                        </div>
                        <div class="col-md-4">
                            <label class="how-label" for="banner_point_2">Point 2</label>
                            <input type="text" name="banner_point_2" id="banner_point_2"
                                   value="{{ old('banner_point_2', $section->banner_point_2 ?? '') }}"
                                   class="form-control how-input" placeholder="e.g. No clients required">
                        </div>
                        <div class="col-md-4">
                            <label class="how-label" for="banner_point_3">Point 3</label>
                            <input type="text" name="banner_point_3" id="banner_point_3"
                                   value="{{ old('banner_point_3', $section->banner_point_3 ?? '') }}"
                                   class="form-control how-input" placeholder="e.g. 5-min setup">
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-semibold mb-3 text-muted"><i class="ri-pie-chart-line me-1"></i>Right-Side Graphic Mockup Values (Dynamic UI Blocks)</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded">
                                <label class="how-label" for="banner_earnings_value">This Month Earnings Value</label>
                                <input type="text" name="banner_earnings_value" id="banner_earnings_value"
                                       value="{{ old('banner_earnings_value', $section->banner_earnings_value ?? '') }}"
                                       class="form-control how-input mb-2" placeholder="e.g. $3,820.20">

                                <label class="how-label" for="banner_earnings_comparison">Earnings Comparison Label</label>
                                <input type="text" name="banner_earnings_comparison" id="banner_earnings_comparison"
                                       value="{{ old('banner_earnings_comparison', $section->banner_earnings_comparison ?? '') }}"
                                       class="form-control how-input mb-2" placeholder="e.g. vs $3,310 last month">

                                <label class="how-label" for="banner_earnings_change">Earnings Growth Change</label>
                                <input type="text" name="banner_earnings_change" id="banner_earnings_change"
                                       value="{{ old('banner_earnings_change', $section->banner_earnings_change ?? '') }}"
                                       class="form-control how-input" placeholder="e.g. +16%">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="p-3 bg-light rounded h-100">
                                <label class="how-label">Top Categories List Values</label>
                                <div class="row g-2 mb-2">
                                    <div class="col-md-8">
                                        <input type="text" name="banner_category1_name" 
                                               value="{{ old('banner_category1_name', $section->banner_category1_name ?? '') }}" 
                                               class="form-control how-input" placeholder="Category 1 Name (e.g. Vitamins)">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="banner_category1_percent" 
                                               value="{{ old('banner_category1_percent', $section->banner_category1_percent ?? '') }}" 
                                               class="form-control how-input" placeholder="Percent (e.g. 80%)">
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-md-8">
                                        <input type="text" name="banner_category2_name" 
                                               value="{{ old('banner_category2_name', $section->banner_category2_name ?? '') }}" 
                                               class="form-control how-input" placeholder="Category 2 Name (e.g. Protein)">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="banner_category2_percent" 
                                               value="{{ old('banner_category2_percent', $section->banner_category2_percent ?? '') }}" 
                                               class="form-control how-input" placeholder="Percent (e.g. 62%)">
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-8">
                                        <input type="text" name="banner_category3_name" 
                                               value="{{ old('banner_category3_name', $section->banner_category3_name ?? '') }}" 
                                               class="form-control how-input" placeholder="Category 3 Name (e.g. Probiotics)">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="banner_category3_percent" 
                                               value="{{ old('banner_category3_percent', $section->banner_category3_percent ?? '') }}" 
                                               class="form-control how-input" placeholder="Percent (e.g. 48%)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Card 1: Statistics Counters ── --}}
            <div class="card how-card mb-4">
                <div class="card-header">
                    <div class="header-icon blue"><i class="ri-bar-chart-box-line"></i></div>
                    <div>
                        <h5 class="card-title mb-0">1. Statistics Counters (Image 2 Top)</h5>
                        <small class="text-muted">Define the 4 metrics values and labels.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <h6 class="fw-semibold mb-2">Metric Card {{ $i }}</h6>
                                    <div class="mb-2">
                                        <label class="how-label">Value</label>
                                        <input type="text" name="stat{{ $i }}_value" 
                                               value="{{ old('stat'.$i.'_value', $section->{"stat".$i."_value"} ?? '') }}" 
                                               class="form-control how-input" placeholder="e.g. 4,200+">
                                    </div>
                                    <div>
                                        <label class="how-label">Label</label>
                                        <input type="text" name="stat{{ $i }}_label" 
                                               value="{{ old('stat'.$i.'_label', $section->{"stat".$i."_label"} ?? '') }}" 
                                               class="form-control how-input" placeholder="e.g. Health pros">
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ── Card 2: Features Section ── --}}
            <div class="card how-card mb-4">
                <div class="card-header">
                    <div class="header-icon green"><i class="ri-grid-fill"></i></div>
                    <div>
                        <h5 class="card-title mb-0">2. Features Section ("Built for the way you actually work.")</h5>
                        <small class="text-muted">Specify the heading, highlight, description, and the 6 key features.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="how-label" for="features_title">Features Section Title</label>
                            <input type="text" name="features_title" id="features_title"
                                   value="{{ old('features_title', $section->features_title ?? '') }}"
                                   class="form-control how-input @error('features_title') is-invalid @enderror"
                                   placeholder="e.g. Built for the way you actually work.">
                            @error('features_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="how-label" for="features_title_highlight">
                                Title Highlight Word &nbsp;<span class="hl-badge"><i class="ri-magic-line"></i> Green Italic</span>
                            </label>
                            <input type="text" name="features_title_highlight" id="features_title_highlight"
                                   value="{{ old('features_title_highlight', $section->features_title_highlight ?? '') }}"
                                   class="form-control how-input @error('features_title_highlight') is-invalid @enderror"
                                   placeholder="e.g. actually work.">
                            @error('features_title_highlight')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="how-label" for="features_description">Features Section Description</label>
                        <textarea name="features_description" id="features_description"
                                  class="form-control how-textarea @error('features_description') is-invalid @enderror"
                                  rows="2"
                                  placeholder="Provide short section description...">{{ old('features_description', $section->features_description ?? '') }}</textarea>
                        @error('features_description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-semibold mb-3 text-muted"><i class="ri-grid-line me-1"></i>Feature Items (6 items)</h6>

                    <div class="row g-3">
                        @for ($i = 1; $i <= 6; $i++)
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded border-start border-4 border-success">
                                    <h6 class="fw-semibold mb-2">Feature Item {{ $i }}</h6>
                                    <div class="mb-2">
                                        <label class="how-label">Title</label>
                                        <input type="text" name="feature{{ $i }}_title" 
                                               value="{{ old('feature'.$i.'_title', $section->{"feature".$i."_title"} ?? '') }}" 
                                               class="form-control how-input" placeholder="Feature Title">
                                    </div>
                                    <div class="mb-2">
                                        <label class="how-label">Description</label>
                                        <textarea name="feature{{ $i }}_description" 
                                                  class="form-control how-textarea" rows="2"
                                                  placeholder="Feature Description">{{ old('feature'.$i.'_description', $section->{"feature".$i."_description"} ?? '') }}</textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="how-label">Feature Icon Image</label>
                                        <input type="file" name="feature{{ $i }}_icon" class="form-control how-input" accept="image/*">
                                    </div>
                                    @if (!empty($section->{"feature".$i."_icon"}))
                                        <div class="mt-2 text-center bg-white p-2 rounded shadow-sm">
                                            <img src="{{ asset($section->{"feature".$i."_icon"}) }}" alt="Feature Icon {{ $i }}" style="max-height: 40px; object-fit: contain;">
                                            <div class="text-muted small mt-1" style="font-size: 0.72rem;">Current Icon</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ── Card 3: Steps Section ── --}}
            <div class="card how-card mb-4">
                <div class="card-header">
                    <div class="header-icon teal"><i class="ri-node-tree"></i></div>
                    <div>
                        <h5 class="card-title mb-0">3. Steps Section ("From signup to first payout in days, not months.")</h5>
                        <small class="text-muted">Specify the heading, highlight, description, and the 4 signup-to-payout steps.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="how-label" for="steps_title">Steps Section Title</label>
                            <input type="text" name="steps_title" id="steps_title"
                                   value="{{ old('steps_title', $section->steps_title ?? '') }}"
                                   class="form-control how-input @error('steps_title') is-invalid @enderror"
                                   placeholder="e.g. From signup to first payout in days, not months.">
                            @error('steps_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="how-label" for="steps_title_highlight">
                                Title Highlight Word
                            </label>
                            <input type="text" name="steps_title_highlight" id="steps_title_highlight"
                                   value="{{ old('steps_title_highlight', $section->steps_title_highlight ?? '') }}"
                                   class="form-control how-input @error('steps_title_highlight') is-invalid @enderror"
                                   placeholder="e.g. not months.">
                            @error('steps_title_highlight')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="how-label" for="steps_description">Steps Section Description</label>
                        <textarea name="steps_description" id="steps_description"
                                  class="form-control how-textarea @error('steps_description') is-invalid @enderror"
                                  rows="2"
                                  placeholder="Provide short section description...">{{ old('steps_description', $section->steps_description ?? '') }}</textarea>
                        @error('steps_description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-semibold mb-3 text-muted"><i class="ri-todo-line me-1"></i>Steps Items (4 steps)</h6>

                    <div class="row g-3">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded border-start border-4 border-info">
                                    <h6 class="fw-semibold mb-2">Step {{ $i }} Details</h6>
                                    <div class="mb-2">
                                        <label class="how-label">Title</label>
                                        <input type="text" name="step{{ $i }}_title" 
                                               value="{{ old('step'.$i.'_title', $section->{"step".$i."_title"} ?? '') }}" 
                                               class="form-control how-input" placeholder="Step Title">
                                    </div>
                                    <div>
                                        <label class="how-label">Description</label>
                                        <textarea name="step{{ $i }}_description" 
                                                  class="form-control how-textarea" rows="3"
                                                  placeholder="Step Description">{{ old('step'.$i.'_description', $section->{"step".$i."_description"} ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ── Card 4: Tiers Section ── --}}
            <div class="card how-card mb-4">
                <div class="card-header">
                    <div class="header-icon purple"><i class="ri-vip-crown-line"></i></div>
                    <div>
                        <h5 class="card-title mb-0">4. Tiers Section ("The more you grow, the more you earn.")</h5>
                        <small class="text-muted">Specify the heading, highlight, description, and the 4 reward tiers details.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="how-label" for="tiers_title">Tiers Section Title</label>
                            <input type="text" name="tiers_title" id="tiers_title"
                                   value="{{ old('tiers_title', $section->tiers_title ?? '') }}"
                                   class="form-control how-input @error('tiers_title') is-invalid @enderror"
                                   placeholder="e.g. The more you grow, the more you earn.">
                            @error('tiers_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="how-label" for="tiers_title_highlight">
                                Title Highlight Word
                            </label>
                            <input type="text" name="tiers_title_highlight" id="tiers_title_highlight"
                                   value="{{ old('tiers_title_highlight', $section->tiers_title_highlight ?? '') }}"
                                   class="form-control how-input @error('tiers_title_highlight') is-invalid @enderror"
                                   placeholder="e.g. the more you earn.">
                            @error('tiers_title_highlight')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="how-label" for="tiers_description">Tiers Section Description</label>
                        <textarea name="tiers_description" id="tiers_description"
                                  class="form-control how-textarea @error('tiers_description') is-invalid @enderror"
                                  rows="2"
                                  placeholder="Provide short section description...">{{ old('tiers_description', $section->tiers_description ?? '') }}</textarea>
                        @error('tiers_description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-semibold mb-3 text-muted"><i class="ri-medal-line me-1"></i>Tier Items (4 levels)</h6>

                    <div class="row g-3">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded border-start border-4 border-primary">
                                    <h6 class="fw-semibold mb-2">Tier Level {{ $i }}</h6>
                                    <div class="mb-2">
                                        <label class="how-label">Tier Name</label>
                                        <input type="text" name="tier{{ $i }}_name" 
                                               value="{{ old('tier'.$i.'_name', $section->{"tier".$i."_name"} ?? '') }}" 
                                               class="form-control how-input" placeholder="e.g. Bronze">
                                    </div>
                                    <div class="mb-2">
                                        <label class="how-label">Commission Rate</label>
                                        <input type="text" name="tier{{ $i }}_commission" 
                                               value="{{ old('tier'.$i.'_commission', $section->{"tier".$i."_commission"} ?? '') }}" 
                                               class="form-control how-input" placeholder="e.g. 5%">
                                    </div>
                                    <div>
                                        <label class="how-label">Required Sales</label>
                                        <input type="text" name="tier{{ $i }}_sales" 
                                               value="{{ old('tier'.$i.'_sales', $section->{"tier".$i."_sales"} ?? '') }}" 
                                               class="form-control how-input" placeholder="e.g. $0 sales">
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ── Submit ── --}}
            <div class="d-flex justify-content-end mb-5">
                <button type="submit" class="btn btn-save">
                    <i class="ri-save-3-line me-2"></i> Save Changes
                </button>
            </div>

        </div>
    </form>

@endsection
