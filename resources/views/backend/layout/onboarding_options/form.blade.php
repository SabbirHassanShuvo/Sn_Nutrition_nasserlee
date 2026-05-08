@extends('backend.master')

@section('title', isset($onboardingOption) ? 'Edit Option' : 'Add Options')

@push('styles-top')
    <style>
        .repeater-item {
            transition: all 0.3s ease;
            border-left: 4px solid #405189;
        }
        .repeater-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-remove {
            color: #f06548;
            cursor: pointer;
            transition: color 0.2s;
        }
        .btn-remove:hover {
            color: #bd3214;
        }
        .card-custom {
            border-radius: 12px;
            overflow: hidden;
        }
        .form-section-title {
            font-weight: 600;
            color: #405189;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-section-title i {
            font-size: 1.2rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ isset($onboardingOption) ? 'Edit' : 'Bulk Add' }} Onboarding Options</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('backend.onboarding-option.index') }}">Onboarding Options</a></li>
                            <li class="breadcrumb-item active">{{ isset($onboardingOption) ? 'Edit' : 'Add' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ isset($onboardingOption) ? route('backend.onboarding-option.update', $onboardingOption->id) : route('backend.onboarding-option.store') }}" method="POST">
            @csrf
            @if(isset($onboardingOption))
                @method('PUT')
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card card-custom shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="card-title mb-0 d-flex align-items-center">
                                <i class="ri-settings-4-line me-2 text-primary"></i>
                                {{ isset($onboardingOption) ? 'Modify Option' : 'Define New Options' }}
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            
                            @if(!isset($onboardingOption))
                                <div class="form-section-title">
                                    <i class="ri-list-check-2"></i> Multiple Entries
                                </div>
                                <div id="repeater-container">
                                    <div class="repeater-item card border bg-light mb-3 p-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="options[0][name]" class="form-control" placeholder="e.g. Sports Nutrition" required>
                                            </div>
                                            <div class="col-md-4 mb-3 mb-md-0">
                                                <label class="form-label">Type</label>
                                                <select name="options[0][type]" class="form-select" required>
                                                    <option value="">Select Type</option>
                                                    <option value="specialty">Specialty</option>
                                                    <option value="certification">Certification</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="btn-remove d-none"><i class="ri-delete-bin-line fs-20"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-start mb-4">
                                    <button type="button" id="btn-add-more" class="btn btn-soft-primary btn-sm d-flex align-items-center">
                                        <i class="ri-add-circle-line me-1"></i> Add More Fields
                                    </button>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $onboardingOption->name ?? '') }}" placeholder="e.g. Sports Nutrition">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="type" class="form-label">Type</label>
                                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                            <option value="">Select Type</option>
                                            <option value="specialty" {{ old('type', $onboardingOption->type ?? '') == 'specialty' ? 'selected' : '' }}>Specialty</option>
                                            <option value="certification" {{ old('type', $onboardingOption->type ?? '') == 'certification' ? 'selected' : '' }}>Certification</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <hr class="my-4 text-muted opacity-25">

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('backend.onboarding-option.index') }}" class="btn btn-light">
                                    <i class="ri-arrow-left-line me-1"></i> Back to List
                                </a>
                                <button type="submit" class="btn btn-primary px-4 shadow">
                                    <i class="ri-save-line me-1"></i> {{ isset($onboardingOption) ? 'Update Option' : 'Save All Options' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts-bottom')
    <script>
        $(document).ready(function() {
            let rowIdx = 1;

            $('#btn-add-more').click(function() {
                let newItem = `
                    <div class="repeater-item card border bg-light mb-3 p-3">
                        <div class="row align-items-end">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label">Name</label>
                                <input type="text" name="options[${rowIdx}][name]" class="form-control" placeholder="e.g. Sports Nutrition" required>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label">Type</label>
                                <select name="options[${rowIdx}][type]" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="specialty">Specialty</option>
                                    <option value="certification">Certification</option>
                                </select>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="btn-remove"><i class="ri-delete-bin-line fs-20"></i></span>
                            </div>
                        </div>
                    </div>
                `;
                $('#repeater-container').append(newItem);
                rowIdx++;
            });

            $(document).on('click', '.btn-remove', function() {
                $(this).closest('.repeater-item').fadeOut(200, function() {
                    $(this).remove();
                });
            });
        });
    </script>
@endpush
