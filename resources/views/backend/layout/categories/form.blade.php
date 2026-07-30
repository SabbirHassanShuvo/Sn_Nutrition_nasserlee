@extends('backend.master')
@section('title', 'Dashboard | Category form')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-transparent px-0">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-sm-0 text-dark fw-bold">{{ @$category ? 'Edit' : 'Create' }} Category</h4>
                    <a href="{{ route('backend.category.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form method="post" action="{{ @$category ? route('backend.category.update', @$category->id) : route('backend.category.store')}}" enctype="multipart/form-data">
        @csrf
        @if (@$category)
            @method('PATCH')
        @endif
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-soft-primary border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold text-primary">Category Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fs-14 fw-semibold" for="name">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{old('name', @$category->name)}}" class="form-control form-control-lg bg-light border-0 shadow-none @error('name') is-invalid @enderror" placeholder="Enter category name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fs-14 fw-semibold" for="category_color_text">Background Color</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white border-end-0 p-1 position-relative" style="overflow: hidden;">
                                    <div id="category_color_preview" style="width: 32px; height: 32px; background-color: {{ old('color', @$category->color ?? '#FF8000') }}; border-radius: 6px; border: 1px solid rgba(0,0,0,0.15); transition: background-color 0.2s ease;"></div>
                                    <input type="color" id="category_color" value="{{ old('color', @$category->color ?? '#FF8000') }}" class="position-absolute top-0 start-0 opacity-0 w-100 h-100" style="cursor: pointer; z-index: 2;" title="Click to pick color">
                                </span>
                                <input type="text" name="color" id="category_color_text" class="form-control border-start-0 border-end-0 bg-light shadow-none @error('color') is-invalid @enderror" value="{{ old('color', @$category->color ?? '#FF8000') }}" placeholder="#FF8000" maxlength="7" style="font-family: monospace; font-size: 16px; letter-spacing: 0.5px;">
                                <button type="button" class="btn btn-outline-secondary border-start-0" id="copyColorBtn" title="Copy Color Code">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1 fs-12">Click swatch to pick or paste hex code (e.g. #FF8000)</small>
                            @error('color')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fs-14 fw-semibold">Category Thumbnail</label>
                            <input type="file" name="image" class="dropify" data-default-file="{{ @$category->image ? asset($category->image) : '' }}" data-height="250" accept="image/*,.svg" data-allowed-file-extensions="png jpg jpeg gif webp svg" />
                            @error('image')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                            <p class="text-muted mt-2 fs-12 italic"><i class="ri-information-line me-1"></i> Recommended size: 512x512 px. Max file size: 2MB.</p>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 py-3 text-end">
                        <a href="{{route('backend.category.index')}}" class="btn btn-soft-danger w-sm me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary w-sm shadow-sm">{{@$category ? 'Update' : 'Create'}} Category</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts-bottom')
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();

            function updateColorPreview(hex) {
                if (hex) {
                    $('#category_color_preview').css('background-color', hex);
                }
            }

            // Sync color picker input and text input
            $(document).on('input change', '#category_color', function() {
                let hex = $(this).val().toUpperCase();
                $('#category_color_text').val(hex);
                updateColorPreview(hex);
            });

            $(document).on('input paste keyup', '#category_color_text', function() {
                let val = $(this).val().trim();
                if (val && !val.startsWith('#')) {
                    val = '#' + val;
                }
                val = val.toUpperCase();
                if (/^#[0-9A-F]{6}$/i.test(val)) {
                    $('#category_color').val(val);
                    updateColorPreview(val);
                }
            });

            $(document).on('blur', '#category_color_text', function() {
                let val = $(this).val().trim();
                if (val && !val.startsWith('#')) {
                    val = '#' + val;
                    $(this).val(val.toUpperCase());
                }
                let current = $(this).val();
                if (/^#[0-9A-F]{6}$/i.test(current)) {
                    $('#category_color').val(current);
                    updateColorPreview(current);
                }
            });

            // Copy color button
            $(document).on('click', '#copyColorBtn', function() {
                let colorCode = $('#category_color_text').val();
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(colorCode).then(() => {
                        toastr.success('Color code ' + colorCode + ' copied!');
                    });
                } else {
                    let tempInput = $('<input>');
                    $('body').append(tempInput);
                    tempInput.val(colorCode).select();
                    document.execCommand('copy');
                    tempInput.remove();
                    toastr.success('Color code ' + colorCode + ' copied!');
                }
            });
        });
    </script>
@endpush
