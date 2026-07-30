@extends('backend.master')
@section('title', 'CMS | Contact Us Section')

@push('styles-top')
<style>
    /* ── Section-level banner ─────────────────────────────────── */
    .contact-hero {
        background: linear-gradient(135deg, #15803d 0%, #166534 100%);
        border-radius: 14px;
        padding: 28px 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .contact-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .contact-hero .hero-title   { color: #fff; font-size: 1.35rem; font-weight: 700; margin-bottom: 4px; }
    .contact-hero .hero-subtitle{ color: rgba(255,255,255,.65); font-size: .875rem; margin: 0; }
    .contact-hero .hero-icon    { font-size: 3.5rem; color: rgba(255,255,255,.15); }

    /* ── Card overrides ───────────────────────────────────────── */
    .contact-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        transition: box-shadow .2s;
    }
    .contact-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.10); }

    .contact-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 20px 14px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    .contact-card .card-header .header-icon {
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
    .contact-card .card-header .header-icon.green  { background: #d1fae5; color: #059669; }
    .contact-card .card-header .header-icon.blue   { background: #dbeafe; color: #2563eb; }
    .contact-card .card-header .header-icon.orange { background: #ffedd5; color: #ea580c; }
    .contact-card .card-header .header-icon.purple { background: #ede9fe; color: #7c3aed; }
    .contact-card .card-header .header-icon.teal   { background: #ccfbf1; color: #0d9488; }
    .contact-card .card-body { padding: 20px; }

    /* ── Inputs ───────────────────────────────────────────────── */
    .contact-input, .contact-textarea {
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: .875rem;
        transition: border-color .2s, box-shadow .2s;
        background: #fcfcfc;
    }
    .contact-input:focus, .contact-textarea:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,.12);
        background: #fff;
    }
    .contact-label {
        font-size: .8rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
        letter-spacing: .3px;
        text-transform: uppercase;
    }

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
                <h4 class="mb-sm-0">CMS &mdash; Contact Us Page</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">CMS</a></li>
                    <li class="breadcrumb-item active">Contact Us</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ── Hero banner ── --}}
    <div class="contact-hero">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <p class="hero-title mb-1">
                    <i class="ri-phone-line me-2"></i>Contact Us Page CMS
                </p>
                <p class="hero-subtitle">
                    Configure the main title, subtitle, contact cards (phone, email, address), map, and social media links.
                </p>
            </div>
            <i class="ri-map-pin-user-line hero-icon d-none d-md-block"></i>
        </div>
    </div>

    {{-- ── Form ── --}}
    <form method="POST"
          action="{{ route('backend.contact-us.update') }}"
          class="row g-4">
        @csrf
        @method('PUT')

        <div class="col-md-12">

            {{-- ── Card: Main Header Content ── --}}
            <div class="card contact-card mb-4">
                <div class="card-header">
                    <div class="header-icon green"><i class="ri-article-line"></i></div>
                    <div>
                        <h5 class="card-title mb-0">1. Header Information</h5>
                        <small class="text-muted">Top badge label, main heading, and description.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="contact-label" for="contact_badge">Contact Badge Label</label>
                            <input type="text" name="contact_badge" id="contact_badge"
                                   value="{{ old('contact_badge', $data->contact_badge ?? '') }}"
                                   class="form-control contact-input @error('contact_badge') is-invalid @enderror"
                                   placeholder="e.g. We're here to help">
                            @error('contact_badge')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="contact-label" for="contact_title">Contact Title (Regular Part)</label>
                            <input type="text" name="contact_title" id="data-contact_title"
                                   value="{{ old('contact_title', $data->contact_title ?? '') }}"
                                   class="form-control contact-input @error('contact_title') is-invalid @enderror"
                                   placeholder="e.g. Get in">
                            @error('contact_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="contact-label" for="contact_title_highlight">Contact Title (Highlighted Part)</label>
                            <input type="text" name="contact_title_highlight" id="data-contact_title_highlight"
                                   value="{{ old('contact_title_highlight', $data->contact_title_highlight ?? '') }}"
                                   class="form-control contact-input @error('contact_title_highlight') is-invalid @enderror"
                                   placeholder="e.g. touch">
                            @error('contact_title_highlight')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="contact-label" for="contact_subtitle">Contact Subtitle / Description</label>
                            <textarea name="contact_subtitle" id="data-contact_subtitle" rows="3"
                                      class="form-control contact-textarea @error('contact_subtitle') is-invalid @enderror"
                                      placeholder="Questions about product an order, or a partnership?...">{{ old('contact_subtitle', $data->contact_subtitle ?? '') }}</textarea>
                            @error('contact_subtitle')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Card: Contact Cards ── --}}
            <div class="card contact-card mb-4">
                <div class="card-header">
                    <div class="header-icon blue"><i class="ri-contacts-book-line"></i></div>
                    <div>
                        <h5 class="card-title mb-0">2. Contact Methods & Information</h5>
                        <small class="text-muted">Configure the Call us, Email, and Visit cards.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Call Card -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-dark font-weight-bold mb-3"><i class="ri-phone-fill text-success me-1"></i> Call Us Card</h6>
                            <div class="mb-3">
                                <label class="contact-label" for="footer_phone">Phone Number</label>
                                <input type="text" name="footer_phone" id="footer_phone"
                                       value="{{ old('footer_phone', $data->footer_phone ?? '') }}"
                                       class="form-control contact-input @error('footer_phone') is-invalid @enderror"
                                       placeholder="e.g. +44 7824 7394520">
                                @error('footer_phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="contact-label" for="contact_phone_hours">Working Hours</label>
                                <input type="text" name="contact_phone_hours" id="contact_phone_hours"
                                       value="{{ old('contact_phone_hours', $data->contact_phone_hours ?? '') }}"
                                       class="form-control contact-input @error('contact_phone_hours') is-invalid @enderror"
                                       placeholder="e.g. Mon - Sat, 9:00 - 12:00">
                                @error('contact_phone_hours')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Email Card -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-dark font-weight-bold mb-3"><i class="ri-mail-fill text-primary me-1"></i> Email Card</h6>
                            <div class="mb-3">
                                <label class="contact-label" for="footer_email">Email Address</label>
                                <input type="email" name="footer_email" id="footer_email"
                                       value="{{ old('footer_email', $data->footer_email ?? '') }}"
                                       class="form-control contact-input @error('footer_email') is-invalid @enderror"
                                       placeholder="e.g. contact@snnutrition.com">
                                @error('footer_email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="contact-label" for="contact_email_response">Response Time / Subtext</label>
                                <input type="text" name="contact_email_response" id="contact_email_response"
                                       value="{{ old('contact_email_response', $data->contact_email_response ?? '') }}"
                                       class="form-control contact-input @error('contact_email_response') is-invalid @enderror"
                                       placeholder="e.g. Replies within a few hours">
                                @error('contact_email_response')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Visit Card -->
                        <div class="col-md-12">
                            <hr class="my-3">
                            <h6 class="text-dark font-weight-bold mb-3"><i class="ri-map-pin-2-fill text-danger me-1"></i> Visit Card</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="contact-label" for="footer_address">Address / City</label>
                                    <input type="text" name="footer_address" id="footer_address"
                                           value="{{ old('footer_address', $data->footer_address ?? '') }}"
                                           class="form-control contact-input @error('footer_address') is-invalid @enderror"
                                           placeholder="e.g. Casablanca, Morocco">
                                    @error('footer_address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="contact-label" for="contact_address_details">Address Details / Street</label>
                                    <input type="text" name="contact_address_details" id="contact_address_details"
                                           value="{{ old('contact_address_details', $data->contact_address_details ?? '') }}"
                                           class="form-control contact-input @error('contact_address_details') is-invalid @enderror"
                                           placeholder="e.g. Boulevard, 20000">
                                    @error('contact_address_details')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Card: Map & Socials ── --}}
            <div class="card contact-card mb-4">
                <div class="card-header">
                    <div class="header-icon orange"><i class="ri-global-line"></i></div>
                    <div>
                        <h5 class="card-title mb-0">3. Map & Follow Us</h5>
                        <small class="text-muted">Configure the Google Map embed iframe code and Follow us headings.</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="contact-label" for="contact_map_iframe">Google Map link</label>
                            <input type="text" name="contact_map_iframe" id="contact_map_iframe"
                                   value="{{ old('contact_map_iframe', $data->contact_map_iframe ?? '') }}"
                                   class="form-control contact-input @error('contact_map_iframe') is-invalid @enderror"
                                   placeholder="e.g. Follow us">
                            @error('contact_map_iframe')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        {{-- <div class="col-md-12 mb-4">
                            <label class="contact-label" for="contact_map_iframe">Google Map Iframe Code</label>
                            <textarea name="contact_map_iframe" id="contact_map_iframe" rows="4"
                                      class="form-control contact-textarea @error('contact_map_iframe') is-invalid @enderror"
                                      placeholder="Paste <iframe> code here...">{{ old('contact_map_iframe', $settings->contact_map_iframe ?? '') }}</textarea>
                            <small class="text-muted mt-1 d-block">Ensure you paste the full <code>&lt;iframe&gt;</code> HTML code from Google Maps share options.</small>
                            @error('contact_map_iframe')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div> --}}

                        <div class="col-md-6 mb-3">
                            <label class="contact-label" for="contact_follow_title">Follow Us Title</label>
                            <input type="text" name="contact_follow_title" id="contact_follow_title"
                                   value="{{ old('contact_follow_title', $data->contact_follow_title ?? '') }}"
                                   class="form-control contact-input @error('contact_follow_title') is-invalid @enderror"
                                   placeholder="e.g. Follow us">
                            @error('contact_follow_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="contact-label" for="contact_follow_subtitle">Follow Us Subtitle</label>
                            <input type="text" name="contact_follow_subtitle" id="contact_follow_subtitle"
                                   value="{{ old('contact_follow_subtitle', $data->contact_follow_subtitle ?? '') }}"
                                   class="form-control contact-input @error('contact_follow_subtitle') is-invalid @enderror"
                                   placeholder="e.g. Tips, drops and behind the scenes.">
                            @error('contact_follow_subtitle')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Submit Section ── --}}
            <div class="d-flex justify-content-end mb-4">
                <button type="submit" class="btn btn-save text-white">
                    <i class="ri-save-line me-1"></i> Save Contact Settings
                </button>
            </div>

        </div>
    </form>
@endsection
