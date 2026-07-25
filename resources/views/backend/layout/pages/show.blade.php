@extends('backend.master')
@section('title', 'Preview — ' . $page->page_title)

@section('content')

    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-sm-0">Page Preview</h4>
                    <a href="{{ route('backend.page.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                    <a href="{{ route('backend.page.edit', $page->id) }}" class="btn btn-sm btn-primary">
                        <i class="mdi mdi-pencil"></i> Edit
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('backend.page.index') }}">Pages</a></li>
                        <li class="breadcrumb-item active">Preview</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Meta info bar --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span class="badge {{ $page->status == \App\Models\Page::STATUS['ACTIVE'] ? 'bg-success' : 'bg-secondary' }} fs-12 px-3 py-2">
                    <i class="mdi mdi-circle me-1" style="font-size:8px;"></i>
                    {{ $page->status == \App\Models\Page::STATUS['ACTIVE'] ? 'Active' : 'Inactive' }}
                </span>
                <span class="text-muted fs-13">
                    <i class="ri-link me-1"></i>
                    Slug: <code class="ms-1">{{ $page->slug }}</code>
                </span>
                <span class="text-muted fs-13">
                    <i class="ri-time-line me-1"></i>
                    Last updated: {{ $page->updated_at->diffForHumans() }}
                </span>
            </div>
        </div>
    </div>

    {{-- Preview Card --}}
    <div class="row">
        <div class="col-12">
            {{-- Browser chrome mockup --}}
            <div class="card border-0 shadow-sm overflow-hidden">
                {{-- Fake browser bar --}}
                <div class="d-flex align-items-center px-4 py-2 bg-dark gap-2" style="min-height:44px;">
                    <span class="rounded-circle" style="width:12px;height:12px;background:#ff5f57;display:inline-block;"></span>
                    <span class="rounded-circle" style="width:12px;height:12px;background:#febc2e;display:inline-block;"></span>
                    <span class="rounded-circle" style="width:12px;height:12px;background:#28c840;display:inline-block;"></span>
                    <div class="flex-grow-1 ms-3">
                        <div class="bg-secondary rounded px-3 py-1 text-white-50 fs-12" style="max-width:420px;font-family:monospace;">
                            sn-nutrition.com/{{ $page->slug }}
                        </div>
                    </div>
                </div>

                {{-- Page content area --}}
                <div class="card-body p-0">
                    {{-- Hero strip --}}
                    <div class="py-5 px-4 text-center"
                        style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
                        <h1 class="fw-bold mb-0" style="color:#ffffff;font-size:2.2rem;letter-spacing:-0.5px;">
                            {{ $page->page_title }}
                        </h1>
                        <p class="mt-2 mb-0" style="color:rgba(255,255,255,0.55);font-size:0.9rem;">
                            Last updated: {{ $page->updated_at->format('d M Y') }}
                        </p>
                    </div>

                    {{-- Rich content --}}
                    <div class="page-content-preview mx-auto py-5 px-4" style="max-width:860px;">
                        {!! $page->page_content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles-top')
<style>
    /* ── Base typography ───────────────────────────── */
    .page-content-preview {
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        font-size: 15px;
        line-height: 1.8;
        color: #333;
    }

    /* ── Headings ──────────────────────────────────── */
    .page-content-preview h1,
    .page-content-preview h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #111;
        margin-top: 2.5rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
    }
    .page-content-preview h3 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-top: 2rem;
        margin-bottom: 0.5rem;
    }
    .page-content-preview h4 { font-size: 1rem; font-weight: 600; margin-top: 1.5rem; }

    /* ── Paragraphs ────────────────────────────────── */
    .page-content-preview p {
        margin-bottom: 1rem;
        color: #444;
    }

    /* ── Lists ─────────────────────────────────────── */
    .page-content-preview ul,
    .page-content-preview ol {
        padding-left: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .page-content-preview li {
        margin-bottom: 0.45rem;
        color: #444;
    }
    .page-content-preview ul li::marker { color: #0f3460; }
    .page-content-preview ol li::marker { color: #0f3460; font-weight: 600; }

    /* ── Links ─────────────────────────────────────── */
    .page-content-preview a {
        color: #0f3460;
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .page-content-preview a:hover { color: #e94560; }

    /* ── Strong / em ───────────────────────────────── */
    .page-content-preview strong { color: #111; }

    /* ── Blockquote ────────────────────────────────── */
    .page-content-preview blockquote {
        border-left: 4px solid #0f3460;
        margin: 1.5rem 0;
        padding: 0.75rem 1.25rem;
        background: #f4f6fb;
        border-radius: 0 8px 8px 0;
        color: #555;
    }

    /* ── Code / pre ────────────────────────────────── */
    .page-content-preview code {
        background: #f0f4ff;
        color: #0f3460;
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 0.85em;
    }

    /* ── HR ────────────────────────────────────────── */
    .page-content-preview hr {
        border: none;
        border-top: 2px solid #f0f0f0;
        margin: 2rem 0;
    }

    /* ── Table ─────────────────────────────────────── */
    .page-content-preview table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
        font-size: 14px;
    }
    .page-content-preview th {
        background: #0f3460;
        color: #fff;
        padding: 10px 14px;
        text-align: left;
        font-weight: 600;
    }
    .page-content-preview td {
        padding: 9px 14px;
        border-bottom: 1px solid #eee;
        color: #444;
    }
    .page-content-preview tr:last-child td { border-bottom: none; }
    .page-content-preview tr:nth-child(even) td { background: #f9fafc; }

    /* ── Soft-colour helper buttons ────────────────── */
    .btn-soft-success { background-color: rgba(10,179,156,.1); color: #0ab39c; border: none; }
    .btn-soft-success:hover { background-color: #0ab39c; color: #fff; }
</style>
@endpush
