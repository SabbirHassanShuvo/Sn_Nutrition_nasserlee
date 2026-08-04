@extends('backend.master')

@section('title', 'Onboarding Questions & CMS Settings')

@section('content')
    <!-- Onboarding Card CMS Settings -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold text-primary">Onboarding Assessment Card CMS Settings</h5>
                    <p class="text-muted mb-0 fs-12">Customize the texts displayed on the left side of the assessment / onboarding card.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('backend.onboarding-question.settings.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="badge_text" class="form-label fw-medium">Badge/Tag Text</label>
                                <input type="text" class="form-control" id="badge_text" name="badge_text" value="{{ old('badge_text', $cmsSettings->badge_text) }}" placeholder="e.g. Tailored for you">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="title" class="form-label fw-medium">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $cmsSettings->title) }}" placeholder="e.g. Not sure where to start?" required>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="footnote_text" class="form-label fw-medium">Footnote/Info Text</label>
                                <input type="text" class="form-control" id="footnote_text" name="footnote_text" value="{{ old('footnote_text', $cmsSettings->footnote_text) }}" placeholder="e.g. 2min • No account needed • 100% free">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label fw-medium">Description/Subtitle <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Describe the assessment to the user..." required>{{ old('description', $cmsSettings->description) }}</textarea>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success btn-md px-4 shadow-sm">
                                <i class="ri-save-line align-bottom me-1"></i> Save Card Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Onboarding Questions Table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" id="onboardingList">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Onboarding Assessment Questions</h5>
                        <p class="text-muted mb-0 fs-12">Manage questions and their choice answers for user onboarding</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm shadow-sm d-none align-items-center" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> Bulk Delete
                        </button>
                        <a href="{{ route('backend.onboarding-question.create') }}" class="btn btn-primary btn-sm add-btn shadow-sm d-flex align-items-center">
                            <i class="ri-add-line align-bottom me-1"></i> Add New Question
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0 data-table custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px; text-align: center;"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                    <th class="ps-3" style="width: 60px;">Sl No.</th>
                                    <th class="text-start">Question</th>
                                    <th class="text-start">Options/Answers</th>
                                    <th class="text-center" style="width: 120px;">Status</th>
                                    <th class="text-center" style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles-top')
    <style>
        .custom-table thead th {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            padding: 12px 15px;
            letter-spacing: 0.5px;
        }
        .custom-table tbody td {
            padding: 10px 15px;
            font-size: 13.5px;
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e9ebec;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            background-color: #f3f6f9;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e9ebec;
            border-radius: 6px;
            padding: 0.3rem 1.5rem 0.3rem 0.7rem;
        }
        .btn-soft-info { background-color: rgba(41, 156, 219, 0.1); color: #299cdb; border: none; }
        .btn-soft-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; border: none; }
        .btn-soft-info:hover { background-color: #299cdb; color: #fff; }
        .btn-soft-danger:hover { background-color: #f06548; color: #fff; }
    </style>
@endpush

@push('scripts-bottom')
    <script>
        (function ($) {
            $(function () {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: { details: true },
                    dom: '<"row mb-3 px-3 mt-3"<"col-md-6"l><"col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3 px-3 pb-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                    ajax: "{{ route('backend.onboarding-question.index') }}",
                    columns: [
                        {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                        {data: 'serial_number', name: 'serial_number', className: 'ps-3 text-center fw-medium'},
                        {data: 'question_text', name: 'question_text', className: 'text-start fw-medium'},
                        {data: 'answers', name: 'answers', className: 'text-start text-wrap', orderable: false, searchable: false},
                        {data: 'status', name: 'status', orderable: false, searchable: false, className: 'text-center'},
                        {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                    ]
                });
                
                initBulkDelete("{{ route('backend.onboarding-question.bulk-destroy') }}");
            });
        })(jQuery);

        function showStatusChangeAlert(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to change the status!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('backend.onboarding-question.status', ':id') }}";
                    $.ajax({
                        url: url.replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Success!', response.message, 'success');
                                $('.data-table').DataTable().ajax.reload();
                            }
                        }
                    });
                } else {
                    var checkbox = $('#customSwitch' + id);
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
            });
        }

        function deleteData(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('.data-table').DataTable().ajax.reload();
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
