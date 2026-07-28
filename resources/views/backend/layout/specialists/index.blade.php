@extends('backend.master')

@section('title', 'Specialist Management')

@push('styles-top')
    <style>
        .btn-soft-primary {
            background-color: rgba(64, 81, 137, 0.1) !important;
            color: #405189 !important;
            border: none !important;
            transition: all 0.2s ease-in-out;
        }
        .btn-soft-primary:hover {
            background-color: #405189 !important;
            color: #ffffff !important;
        }
        .btn-soft-primary i, .btn-soft-primary:hover i {
            color: inherit !important;
        }

        .btn-soft-danger {
            background-color: rgba(240, 101, 72, 0.1) !important;
            color: #f06548 !important;
            border: none !important;
            transition: all 0.2s ease-in-out;
        }
        .btn-soft-danger:hover {
            background-color: #f06548 !important;
            color: #ffffff !important;
        }
        .btn-soft-danger i, .btn-soft-danger:hover i {
            color: inherit !important;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" id="specialistList">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Specialists</h5>
                        <p class="text-muted mb-0 fs-12">Manage consultants and specialists for customer bookings</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm shadow-sm d-none align-items-center" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> Bulk Delete
                        </button>
                        <a href="{{ route('backend.specialist.create') }}" class="btn btn-primary btn-sm shadow-sm d-flex align-items-center">
                            <i class="ri-add-line align-bottom me-1"></i> Add Specialist(s)
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0 custom-table" id="specialistTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px; text-align: center;"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                    <th>Specialist Info</th>
                                    <th>Specialties</th>
                                    <th>Available Slots</th>
                                    <th class="text-center" style="width: 100px;">Status</th>
                                    <th class="text-center" style="width: 130px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-bottom')
    <script>
        $(document.body).ready(function() {
            var table = $('#specialistTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('backend.specialist.index') }}",
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'info', name: 'name' },
                    { data: 'specialties_tags', name: 'specialties', orderable: false },
                    { data: 'slots', name: 'available_slots', orderable: false },
                    { data: 'status', name: 'is_active', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // Check all checkboxes
            $('#checkAll').on('change', function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkDeleteBtn();
            });

            $(document).on('change', '.row-checkbox', function() {
                toggleBulkDeleteBtn();
            });

            function toggleBulkDeleteBtn() {
                var selectedCount = $('.row-checkbox:checked').length;
                if (selectedCount > 0) {
                    $('#bulkDeleteBtn').removeClass('d-none').addClass('d-flex');
                } else {
                    $('#bulkDeleteBtn').addClass('d-none').removeClass('d-flex');
                }
            }

            // Status Toggle
            $(document).on('change', '.status-toggle', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ url('admin/specialist/status') }}/" + id,
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        toastr.success('Status updated successfully');
                    },
                    error: function() {
                        toastr.error('Failed to update status');
                    }
                });
            });

            // Single Delete with SweetAlert2
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this specialist record!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('admin/specialist') }}/" + id,
                            type: "DELETE",
                            data: { _token: "{{ csrf_token() }}" },
                            success: function(res) {
                                table.ajax.reload(null, false);
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Specialist deleted successfully',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            },
                            error: function() {
                                toastr.error('Failed to delete specialist');
                            }
                        });
                    }
                });
            });

            // Bulk Delete with SweetAlert2
            $('#bulkDeleteBtn').on('click', function() {
                var ids = [];
                $('.row-checkbox:checked').each(function() {
                    ids.push($(this).val());
                });

                if (ids.length > 0) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Delete ' + ids.length + ' selected specialists?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete selected!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('backend.specialist.bulk-destroy') }}",
                                type: "DELETE",
                                data: { _token: "{{ csrf_token() }}", ids: ids },
                                success: function(res) {
                                    table.ajax.reload(null, false);
                                    $('#bulkDeleteBtn').addClass('d-none').removeClass('d-flex');
                                    $('#checkAll').prop('checked', false);
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Selected specialists deleted successfully',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                },
                                error: function() {
                                    toastr.error('Failed to delete specialists');
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
