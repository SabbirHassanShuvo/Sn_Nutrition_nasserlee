@extends('backend.master')
@section('title', 'Web Settings')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Web Settings</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                        <li class="breadcrumb-item active">Web Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0 bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-primary">Web Logo, Banner & Footer Settings</h5>
                    <p class="text-muted mb-0 fs-12">Manage web logos, notification banner, footer descriptions and social profiles</p>
                </div>

                <div class="card-body">
                    <form action="{{ route('backend.settings.web-setting.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <!-- Logos Settings -->
                            <div class="col-md-12">
                                <h5 class="text-secondary fw-semibold border-bottom pb-2">Website Logos</h5>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Navbar Logo</label>
                                <input type="file" name="navbar_logo" class="dropify" data-height="120"
                                    @if(!empty($settings->navbar_logo)) data-default-file="{{ asset($settings->navbar_logo) }}" @endif>
                                @error('navbar_logo')
                                    <span class="text-danger fs-12">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Footer Logo</label>
                                <input type="file" name="footer_logo" class="dropify" data-height="120"
                                    @if(!empty($settings->footer_logo)) data-default-file="{{ asset($settings->footer_logo) }}" @endif>
                                @error('footer_logo')
                                    <span class="text-danger fs-12">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Header Top Banner Settings -->
                            <div class="col-md-12 mt-4">
                                <h5 class="text-secondary fw-semibold border-bottom pb-2">Header Top Banner</h5>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Header Top Banner Text</label>
                                <input type="text" name="top_banner_text"
                                    class="form-control @error('top_banner_text') is-invalid @enderror"
                                    placeholder="e.g. Free Delivery Starting From 1000 dh"
                                    value="{{ old('top_banner_text', $settings->top_banner_text ?? '') }}">
                                @error('top_banner_text')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Header Top Banner Status</label>
                                <select name="top_banner_status" class="form-select @error('top_banner_status') is-invalid @enderror">
                                    <option value="1" {{ old('top_banner_status', $settings->top_banner_status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('top_banner_status', $settings->top_banner_status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('top_banner_status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Footer Settings -->
                            <div class="col-md-12 mt-4">
                                <h5 class="text-secondary fw-semibold border-bottom pb-2">Footer Description & Contacts</h5>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Footer Description</label>
                                <textarea name="footer_description" class="form-control @error('footer_description') is-invalid @enderror" rows="3" placeholder="Enter footer intro text">{{ old('footer_description', $settings->footer_description ?? '') }}</textarea>
                                @error('footer_description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Copyright Text</label>
                                <input type="text" name="copyright_text"
                                    class="form-control @error('copyright_text') is-invalid @enderror"
                                    placeholder="e.g. © 2026 SN Nutrition. All rights reserved."
                                    value="{{ old('copyright_text', $settings->copyright_text ?? '') }}">
                                @error('copyright_text')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Footer Phone</label>
                                <input type="text" name="footer_phone"
                                    class="form-control @error('footer_phone') is-invalid @enderror"
                                    placeholder="+44 7824 739607"
                                    value="{{ old('footer_phone', $settings->footer_phone ?? '') }}">
                                @error('footer_phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Footer Email</label>
                                <input type="email" name="footer_email"
                                    class="form-control @error('footer_email') is-invalid @enderror"
                                    placeholder="contact@snnutrition.com"
                                    value="{{ old('footer_email', $settings->footer_email ?? '') }}">
                                @error('footer_email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Footer Address</label>
                                <input type="text" name="footer_address"
                                    class="form-control @error('footer_address') is-invalid @enderror"
                                    placeholder="Casablanca, Morocco"
                                    value="{{ old('footer_address', $settings->footer_address ?? '') }}">
                                @error('footer_address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Social Links (Footer) Settings -->
                            <div class="col-md-12 mt-4">
                                <h5 class="text-secondary fw-semibold border-bottom pb-2">Footer Social Links</h5>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Facebook URL</label>
                                <input type="url" name="facebook_url"
                                    class="form-control @error('facebook_url') is-invalid @enderror"
                                    placeholder="https://facebook.com/username"
                                    value="{{ old('facebook_url', $settings->facebook_url ?? '') }}">
                                @error('facebook_url')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Instagram URL</label>
                                <input type="url" name="instagram_url"
                                    class="form-control @error('instagram_url') is-invalid @enderror"
                                    placeholder="https://instagram.com/username"
                                    value="{{ old('instagram_url', $settings->instagram_url ?? '') }}">
                                @error('instagram_url')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Twitter / X URL</label>
                                <input type="url" name="twitter_url"
                                    class="form-control @error('twitter_url') is-invalid @enderror"
                                    placeholder="https://twitter.com/username"
                                    value="{{ old('twitter_url', $settings->twitter_url ?? '') }}">
                                @error('twitter_url')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">WhatsApp Number / Link</label>
                                <input type="text" name="whatsapp_url"
                                    class="form-control @error('whatsapp_url') is-invalid @enderror"
                                    placeholder="e.g. +447824739607 or URL"
                                    value="{{ old('whatsapp_url', $settings->whatsapp_url ?? '') }}">
                                @error('whatsapp_url')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">LinkedIn URL</label>
                                <input type="url" name="linkedin_url"
                                    class="form-control @error('linkedin_url') is-invalid @enderror"
                                    placeholder="https://linkedin.com/company/username"
                                    value="{{ old('linkedin_url', $settings->linkedin_url ?? '') }}">
                                @error('linkedin_url')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line align-middle me-1"></i> Save Web Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style-bottom')
    <style>
        .dropify-wrapper {
            background-color: #1a1d26 !important;
            border: 1px dashed #3a3f50 !important;
            color: #fff !important;
        }
        .dropify-wrapper .dropify-preview {
            background-color: #1a1d26 !important;
        }
        .dropify-wrapper .dropify-message p {
            color: #a6b0cf !important;
        }
    </style>
@endpush
