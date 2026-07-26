@extends('backend.master')
@section('title', 'Blogs | Create Blog')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-sm-0">Create Blog</h4>
                    <a href="{{ route('backend.blog.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Blogs</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form method="post" action="{{ route('backend.blog.store') }}" enctype="multipart/form-data" class="row">
        @csrf

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="blog-title-input">Blog Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="form-control @error('title') is-invalid @enderror" id="blog-title-input"
                            placeholder="Enter Blog Title" required>
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Blog Content <span class="text-danger">*</span></label>
                        <textarea name="content" id="ckeditor-classic" class="@error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                        @error('content')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end mb-4">
                <a href="{{ route('backend.blog.index') }}" class="btn btn-danger w-sm">Cancel</a>
                <button type="submit" class="btn btn-success w-sm">Create</button>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="choices-status-input" class="form-label">Status</label>
                        <select name="status" class="form-select" id="choices-status-input">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active / Published</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive / Draft</option>
                        </select>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="published-at-input" class="form-label">Publish Date & Time</label>
                        <input type="datetime-local" name="published_at" id="published-at-input"
                            value="{{ old('published_at') }}" class="form-control @error('published_at') is-invalid @enderror">
                        @error('published_at')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Featured Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts-top')
    <!-- ckeditor -->
    <script src="{{ asset('assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/pages/project-create.init.js') }}"></script>
@endpush
