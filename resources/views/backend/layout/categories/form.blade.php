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

                        <div class="mb-0">
                            <label class="form-label fs-14 fw-semibold">Category Thumbnail</label>
                            <input type="file" name="image" class="dropify" data-default-file="{{ @$category->image ? asset($category->image) : '' }}" data-height="250" accept="image/*" />
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
        });
    </script>
@endpush
