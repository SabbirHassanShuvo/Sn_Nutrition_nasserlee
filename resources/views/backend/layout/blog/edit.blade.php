@extends('backend.master')
@section('title', 'Blogs | Edit Blog')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-sm-0">Edit Blog</h4>
                    <a href="{{ route('backend.blog.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Blogs</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form method="post" action="{{ route('backend.blog.update', $blog->id) }}" enctype="multipart/form-data" class="row">
        @csrf
        @method('PUT')

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="blog-title-input">Blog Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $blog->title) }}"
                            class="form-control @error('title') is-invalid @enderror" id="blog-title-input"
                            placeholder="Enter Blog Title" required>
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control bg-light" value="{{ $blog->slug }}" disabled>
                        <small class="text-muted">Slug is automatically updated if the title changes.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Blog Content <span class="text-danger">*</span></label>
                        <textarea name="content" id="ckeditor-classic" class="@error('content') is-invalid @enderror">{{ old('content', $blog->content) }}</textarea>
                        @error('content')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end mb-4">
                <a href="{{ route('backend.blog.index') }}" class="btn btn-danger w-sm">Cancel</a>
                <button type="submit" class="btn btn-success w-sm">Update</button>
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
                            <option value="1" {{ old('status', $blog->status) == '1' ? 'selected' : '' }}>Active / Published</option>
                            <option value="0" {{ old('status', $blog->status) == '0' ? 'selected' : '' }}>Inactive / Draft</option>
                        </select>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="published-at-input" class="form-label">Publish Date & Time</label>
                        <input type="datetime-local" name="published_at" id="published-at-input"
                            value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}"
                            class="form-control @error('published_at') is-invalid @enderror">
                        @error('published_at')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Featured Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @if ($blog->image)
                            <div class="mt-2">
                                <img src="{{ asset($blog->image) }}" alt="Featured Image" style="max-width: 100%; max-height: 150px; object-fit: cover; border-radius: 5px;">
                            </div>
                        @endif
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
