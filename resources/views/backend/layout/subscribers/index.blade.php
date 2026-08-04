@extends('backend.master')
@section('title', 'Newsletter Subscribers')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="subscribersList">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Newsletter Subscribers</h5>
                        <p class="text-muted mb-0 fs-12">View and manage newsletter subscription list</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm d-none align-items-center" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> Bulk Delete
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-striped mb-0 data-table">
                        <thead class="table-light text-muted">
                            <tr>
                                <th style="width: 40px; text-align: center;"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                <th class="wd-10p border-bottom-0">ID</th>
                                <th class="wd-40p border-bottom-0">Email</th>
                                <th class="wd-25p border-bottom-0">Subscribed At</th>
                                <th class="wd-15p border-bottom-0">Status</th>
                                <th class="wd-10p border-bottom-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list form-check-all"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-bottom')
    <script>
        (function ($) {
            $(function () {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: { details: true },
                    ajax: "{{ route('backend.subscribers.index') }}",
                    columns: [
                        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'email', name: 'email' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'status', name: 'status', orderable: false, searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });

                initBulkDelete("{{ route('backend.subscribers.bulk-destroy') }}");
            });
        })(jQuery);

        $(document).on('shown.bs.collapse shown.bs.tab', function () {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust()
                .responsive.recalc();
        });

        // Function to update the sidebar badge count and visibility
        function updateSidebarBadge(count) {
            let badge = $('#sidebar-subscribers-badge');
            if (count > 0) {
                badge.text(count).show();
            } else {
                badge.text('').hide();
            }
        }

        function markAsRead(id) {
            let url = "{{ route('backend.subscribers.read', ':id') }}";
            $.ajax({
                type: "POST",
                url: url.replace(':id', id),
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.success) {
                        $('.data-table').DataTable().ajax.reload();
                        updateSidebarBadge(response.unread_count);
                        Swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: response.message || "Subscriber marked as read",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                }
            });
        }

        function deleteData(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this subscriber?",
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
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (response) {
                            if (response.success) {
                                $('.data-table').DataTable().ajax.reload();
                                if (response.unread_count !== undefined) {
                                    updateSidebarBadge(response.unread_count);
                                }
                                Swal.fire({
                                    toast: true,
                                    position: "top-end",
                                    icon: "success",
                                    title: response.message || "Subscriber deleted successfully",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });
                            } else {
                                Swal.fire({
                                    toast: true,
                                    position: "top-end",
                                    icon: "error",
                                    title: response.message || "Something went wrong",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
