@extends('backend.master')
@section('title', 'Dashboard | Page Form')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-sm-0">{{ @$page ? 'Edit' : 'Create' }} Page</h4>
                    <a href="{{ route('backend.page.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                        <li class="breadcrumb-item active">{{ @$page ? 'Edit' : 'Create' }} Page</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form method="POST"
        action="{{ @$page ? route('backend.page.update', @$page->id) : route('backend.page.store') }}"
        class="row">
        @csrf
        @if (@$page)
            @method('PATCH')
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="page_title">Page Title <span class="text-danger">*</span></label>
                        <input type="text" name="page_title" id="page_title"
                            value="{{ old('page_title', @$page->page_title) }}"
                            class="form-control @error('page_title') is-invalid @enderror"
                            placeholder="e.g. Privacy Policy">
                        @error('page_title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    @if (@$page)
                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" class="form-control bg-light" value="{{ $page->slug }}" disabled>
                            <small class="text-muted">Slug is auto-generated from title and cannot be changed manually.</small>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label" for="page_content">Page Content <span class="text-danger">*</span></label>
                        <textarea name="page_content" id="ckeditor-classic" class="@error('page_content') is-invalid @enderror">{{ old('page_content', @$page->page_content) }}</textarea>
                        @error('page_content')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end mb-4">
                <a href="{{ route('backend.page.index') }}" class="btn btn-danger w-sm">Cancel</a>
                <button type="submit" class="btn btn-success w-sm">{{ @$page ? 'Update' : 'Create' }}</button>
            </div>
        </div>
        <!-- end col -->
    </form>

@endsection

@push('scripts-top')
    <!-- ckeditor -->
    <script src="{{ asset('assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    <script src="{{ asset('') }}assets/js/pages/project-create.init.js"></script>
@endpush