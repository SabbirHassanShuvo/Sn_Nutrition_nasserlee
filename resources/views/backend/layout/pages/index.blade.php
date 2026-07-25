@extends('backend.master')
@section('title', 'Pages Management')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Pages</h5>
                        <p class="text-muted mb-0 fs-12">Manage dynamic CMS pages</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <a href="{{ route('backend.page.create') }}" class="btn btn-primary btn-sm shadow-sm d-flex align-items-center">
                            <i class="ri-add-line align-bottom me-1"></i> Add Page
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
                                    <th class="text-start">Page Title</th>
                                    <th>Slug</th>
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
        .custom-table tbody td { padding: 10px 15px; font-size: 13.5px; }
        .btn-soft-info   { background-color: rgba(41,156,219,.1); color: #299cdb; border: none; }
        .btn-soft-danger { background-color: rgba(240,101,72,.1); color: #f06548; border: none; }
        .btn-soft-info:hover   { background-color: #299cdb; color: #fff; }
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
                    ajax: "{{ route('backend.page.index') }}",
                    columns: [
                        { data: 'checkbox',    name: 'checkbox',    orderable: false, searchable: false, className: 'text-center' },
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-3 text-muted fw-medium' },
                        { data: 'page_title',  name: 'page_title',  className: 'text-start fw-medium' },
                        { data: 'slug',        name: 'slug' },
                        { data: 'status',      name: 'status',      orderable: false, searchable: false, className: 'text-center' },
                        { data: 'action',      name: 'action',      orderable: false, searchable: false, className: 'text-center' },
                    ]
                });
            });
        })(jQuery);

        function statusChange(id) {
            let url = "{{ route('backend.page.status', ':id') }}".replace(':id', id);
            $.ajax({
                type: 'POST',
                url: url,
                data: { _token: "{{ csrf_token() }}" },
                success: function (res) {
                    if (res.success) {
                        $('.data-table').DataTable().ajax.reload(null, false);
                        toastr.success(res.message);
                    }
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
                cancelButtonColor:  '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (res) {
                            if (res.success) {
                                $('.data-table').DataTable().ajax.reload(null, false);
                                toastr.success(res.message);
                            } else {
                                toastr.error(res.message);
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
