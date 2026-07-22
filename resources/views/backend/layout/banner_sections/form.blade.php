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

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="banner-title-editor">Banner Title <span class="text-danger">*</span></label>
                                <textarea name="title" id="banner-title-editor" class="form-control @error('title') is-invalid @enderror" placeholder="Enter banner title">{{ old('title', $bannerSection->title ?? '') }}</textarea>
                                @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="banner-badge-input">Small Badge Text</label>
                                <input type="text" name="small_badge"
                                       value="{{ old('small_badge', $bannerSection->small_badge ?? '') }}"
                                       class="form-control @error('small_badge') is-invalid @enderror"
                                       id="banner-badge-input" placeholder="Enter small badge text (e.g., Clinically Trusted Supplements)">
                                @error('small_badge')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="button-text-input">Button Text</label>
                                <input type="text" name="button_text"
                                       value="{{ old('button_text', $bannerSection->button_text ?? '') }}"
                                       class="form-control @error('button_text') is-invalid @enderror"
                                       id="button-text-input" placeholder="Enter button text (e.g., Order Now)">
                                @error('button_text')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <!-- <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="button-link-input">Button Link</label>
                                <input type="text" name="button_link"
                                       value="{{ old('button_link', $bannerSection->button_link ?? '') }}"
                                       class="form-control @error('button_link') is-invalid @enderror"
                                       id="button-link-input" placeholder="Enter button redirect link (e.g., /shop)">
                                @error('button_link')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div> -->
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="banner-description-editor">Banner Description</label>
                        <textarea name="description" id="banner-description-editor" class="form-control @error('description') is-invalid @enderror" placeholder="Enter banner description">{{ old('description', $bannerSection->description ?? '') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row mt-4">
                        <h6 class="mb-3">Key Features (Display Points at the bottom)</h6>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label" for="point-1-input">Key Point 1</label>
                                <input type="text" name="point_1"
                                       value="{{ old('point_1', $bannerSection->point_1 ?? '') }}"
                                       class="form-control @error('point_1') is-invalid @enderror"
                                       id="point-1-input" placeholder="e.g. Fast Delivery">
                                @error('point_1')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label" for="point-2-input">Key Point 2</label>
                                <input type="text" name="point_2"
                                       value="{{ old('point_2', $bannerSection->point_2 ?? '') }}"
                                       class="form-control @error('point_2') is-invalid @enderror"
                                       id="point-2-input" placeholder="e.g. Trusted Quality">
                                @error('point_2')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label" for="point-3-input">Key Point 3</label>
                                <input type="text" name="point_3"
                                       value="{{ old('point_3', $bannerSection->point_3 ?? '') }}"
                                       class="form-control @error('point_3') is-invalid @enderror"
                                       id="point-3-input" placeholder="e.g. Easy Ordering">
                                @error('point_3')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
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

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="choices-priority-input" class="form-label">Priority / Sort Order <span class="text-danger">*</span></label>
                        <input type="number" name="priority"
                               value="{{ old('priority', $bannerSection->priority ?? '1') }}"
                               class="form-control @error('priority') is-invalid @enderror"
                               id="choices-priority-input" min="1">
                        @error('priority')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
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
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="banner-image-input">Banner Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="banner-image-input" accept="image/*">
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        
                        @if (isset($bannerSection) && $bannerSection->image)
                            <div class="mt-3">
                                <label class="form-label d-block">Current Image:</label>
                                <img src="{{ asset($bannerSection->image) }}" alt="Banner Image" class="img-thumbnail rounded" style="max-height: 150px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts-top')
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts-bottom')
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#banner-title-editor').summernote({
                placeholder: 'Enter banner title',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['view', ['codeview']]
                ]
            });
            
            $('#banner-description-editor').summernote({
                placeholder: 'Enter banner description',
                tabsize: 2,
                height: 180,
                toolbar: [
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['view', ['codeview']]
                ]
            });
        });
    </script>
@endpush
