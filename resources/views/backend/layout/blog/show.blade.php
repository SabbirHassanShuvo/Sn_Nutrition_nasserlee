@extends('backend.master')
@section('title', 'Blogs | ' . $blog->title)

@push('styles-top')
<style>
    .blog-detail-container {
        max-width: 900px;
        margin: 0 auto;
    }
    .blog-banner-img {
        width: 100%;
        max-height: 450px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .blog-meta {
        font-size: 0.9rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 15px;
        margin-bottom: 20px;
    }
    .blog-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: #212529;
        line-height: 1.3;
        margin-top: 10px;
        margin-bottom: 15px;
    }
    .blog-body-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #495057;
    }
    .blog-body-content p {
        margin-bottom: 1.5rem;
    }
    .blog-body-content h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #212529;
        margin-top: 2rem;
        margin-bottom: 1rem;
        border-left: 4px solid #15803d;
        padding-left: 10px;
    }
    .blog-body-content ul, .blog-body-content ol {
        margin-bottom: 1.5rem;
        padding-left: 20px;
    }
    .blog-body-content li {
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-sm-0">Blog Detail</h4>
                    <a href="{{ route('backend.blog.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back to List
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Blogs</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card p-4">
                <div class="blog-detail-container">
                    {{-- Back button --}}
                    <div class="mb-3">
                        <a href="{{ route('backend.blog.index') }}" class="text-success fw-semibold">
                            <i class="ri-arrow-left-line me-1"></i> Back to Blog
                        </a>
                    </div>

                    {{-- Featured Image --}}
                    @if ($blog->image)
                        <div class="mb-4">
                            <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="blog-banner-img">
                        </div>
                    @endif

                    {{-- Title --}}
                    <h1 class="blog-title">{{ $blog->title }}</h1>

                    {{-- Meta data (Date, Time, Status) --}}
                    <div class="blog-meta">
                        <span><i class="ri-calendar-line me-1 text-success"></i> {{ $blog->published_at->format('M d, Y') }}</span>
                        <span><i class="ri-time-line me-1 text-primary"></i> {{ $blog->published_at->format('h:i A') }}</span>
                        @if ($blog->status == 1)
                            <span class="badge bg-success-subtle text-success px-3 py-1">Published</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning px-3 py-1">Draft</span>
                        @endif
                    </div>

                    <hr class="my-4">

                    {{-- Content --}}
                    <div class="blog-body-content">
                        {!! $blog->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
