@extends('backend.master')

@section('title', 'Onboarding Options')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" id="onboardingList">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Onboarding Options</h5>
                        <p class="text-muted mb-0 fs-12">Manage specialties and certifications for partners</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm shadow-sm d-none align-items-center" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> Bulk Delete
                        </button>
                        <a href="{{ route('backend.onboarding-option.create') }}" class="btn btn-primary btn-sm add-btn shadow-sm d-flex align-items-center">
                            <i class="ri-add-line align-bottom me-1"></i> Add New Option
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0 data-table custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px; text-align: center;"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                    <th class="ps-3" style="width: 60px;">ID</th>
                                    <th class="text-start">Name</th>
                                    <th class="text-center" style="width: 150px;">Type</th>
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
                    ajax: "{{ route('backend.onboarding-option.index') }}",
                    columns: [
                        {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-3 text-muted fw-medium'},
                        {data: 'name', name: 'name', className: 'text-start fw-medium'},
                        {data: 'type', name: 'type', className: 'text-center'},
                        {data: 'status', name: 'status', orderable: false, searchable: false, className: 'text-center'},
                        {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                    ]
                });
                
                initBulkDelete("{{ route('backend.onboarding-option.bulk-destroy') }}");
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
                    let url = "{{ route('backend.onboarding-option.status', ':id') }}";
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
