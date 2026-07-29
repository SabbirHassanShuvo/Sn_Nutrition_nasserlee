@extends('backend.master')

@section('title', isset($onboardingQuestion) ? 'Edit Question' : 'Add Question')

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
                    <h4 class="mb-sm-0">{{ isset($onboardingQuestion) ? 'Edit' : 'Add' }} Onboarding Question</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('backend.onboarding-question.index') }}">Onboarding Questions</a></li>
                            <li class="breadcrumb-item active">{{ isset($onboardingQuestion) ? 'Edit' : 'Add' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ isset($onboardingQuestion) ? route('backend.onboarding-question.update', $onboardingQuestion->id) : route('backend.onboarding-question.store') }}" method="POST">
            @csrf
            @if(isset($onboardingQuestion))
                @method('PUT')
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card card-custom shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="card-title mb-0 d-flex align-items-center">
                                <i class="ri-question-line me-2 text-primary"></i>
                                {{ isset($onboardingQuestion) ? 'Modify Question' : 'Define New Question' }}
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Question details -->
                            <div class="row">
                                <div class="col-md-9 mb-3">
                                    <label for="question_text" class="form-label fw-semibold">Question Text <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('question_text') is-invalid @enderror" id="question_text" name="question_text" value="{{ old('question_text', $onboardingQuestion->question_text ?? '') }}" placeholder="e.g. How is your sleep quality?" required>
                                    @error('question_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="serial_number" class="form-label fw-semibold">Serial Number <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('serial_number') is-invalid @enderror" id="serial_number" name="serial_number" value="{{ old('serial_number', $onboardingQuestion->serial_number ?? '0') }}" min="0" required>
                                    @error('serial_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4 text-muted opacity-25">

                            <!-- Answers Options Repeater -->
                            <div class="form-section-title">
                                <i class="ri-list-check-2"></i> Option/Choice Answers <span class="text-danger">*</span>
                            </div>

                            <div id="repeater-container">
                                @if(isset($onboardingQuestion) && $onboardingQuestion->answers->count() > 0)
                                    @foreach($onboardingQuestion->answers as $index => $answer)
                                        <div class="repeater-item card border bg-light mb-3 p-3">
                                            <div class="row align-items-end">
                                                <div class="col-md-10 mb-3 mb-md-0">
                                                    <label class="form-label">Answer Text</label>
                                                    <input type="text" name="answers[]" class="form-control" value="{{ $answer->answer_text }}" placeholder="e.g. Poor, Fair, Good, Great" required>
                                                </div>
                                                <div class="col-md-2 text-center mb-1">
                                                    <span class="btn-remove {{ $loop->first ? 'd-none' : '' }}"><i class="ri-delete-bin-line fs-20"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="repeater-item card border bg-light mb-3 p-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-10 mb-3 mb-md-0">
                                                <label class="form-label">Answer Text</label>
                                                <input type="text" name="answers[]" class="form-control" placeholder="e.g. Poor, Fair, Good, Great" required>
                                            </div>
                                            <div class="col-md-2 text-center mb-1">
                                                <span class="btn-remove d-none"><i class="ri-delete-bin-line fs-20"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="text-start mb-4">
                                <button type="button" id="btn-add-more" class="btn btn-soft-primary btn-sm d-flex align-items-center">
                                    <i class="ri-add-circle-line me-1"></i> Add More Answers
                                </button>
                            </div>

                            <hr class="my-4 text-muted opacity-25">

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('backend.onboarding-question.index') }}" class="btn btn-light">
                                    <i class="ri-arrow-left-line me-1"></i> Back to List
                                </a>
                                <button type="submit" class="btn btn-primary px-4 shadow">
                                    <i class="ri-save-line me-1"></i> {{ isset($onboardingQuestion) ? 'Update Question' : 'Save Question' }}
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
            function checkAnswerLimit() {
                let count = $('#repeater-container .repeater-item').length;
                if (count >= 4) {
                    $('#btn-add-more').prop('disabled', true).addClass('disabled').html('<i class="ri-error-warning-line me-1"></i> Max 4 Answers Reached');
                } else {
                    $('#btn-add-more').prop('disabled', false).removeClass('disabled').html('<i class="ri-add-circle-line me-1"></i> Add More Answers');
                }
            }

            // Run on load
            checkAnswerLimit();

            $('#btn-add-more').click(function() {
                let count = $('#repeater-container .repeater-item').length;
                if (count >= 4) return;

                let newItem = `
                    <div class="repeater-item card border bg-light mb-3 p-3" style="display:none;">
                        <div class="row align-items-end">
                            <div class="col-md-10 mb-3 mb-md-0">
                                <label class="form-label">Answer Text</label>
                                <input type="text" name="answers[]" class="form-control" placeholder="e.g. Poor, Fair, Good, Great" required>
                            </div>
                            <div class="col-md-2 text-center mb-1">
                                <span class="btn-remove"><i class="ri-delete-bin-line fs-20"></i></span>
                            </div>
                        </div>
                    </div>
                `;
                let $newItemHtml = $(newItem);
                $('#repeater-container').append($newItemHtml);
                $newItemHtml.fadeIn(200, function() {
                    checkAnswerLimit();
                });
            });

            $(document).on('click', '.btn-remove', function() {
                $(this).closest('.repeater-item').fadeOut(200, function() {
                    $(this).remove();
                    checkAnswerLimit();
                });
            });
        });
    </script>
@endpush
