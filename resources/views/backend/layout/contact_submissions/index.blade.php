@extends('backend.master')
@section('title', 'Contact Submissions')
@section('content')


    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="contactSubmissionsList">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Contact Submissions</h5>
                        <p class="text-muted mb-0 fs-12">View and manage messages sent from the Contact Us page</p>
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
                                <th class="wd-20p border-bottom-0">Name</th>
                                <th class="wd-20p border-bottom-0">Email</th>
                                <th class="wd-25p border-bottom-0">Subject</th>
                                <th class="wd-15p border-bottom-0">Submitted At</th>
                                <th class="wd-10p border-bottom-0">Status</th>
                                <th class="wd-10p border-bottom-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list form-check-all"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- @dd('hi') --}}

    <!-- Details Modal -->
    <div class="modal fade" id="submissionDetailsModal" tabindex="-1" aria-labelledby="submissionDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="submissionDetailsModalLabel">Message Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-0">From</label>
                            <p class="fw-semibold fs-15 mb-0" id="detail-name"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-0">Email Address</label>
                            <p class="fw-semibold fs-15 mb-0"><a href="" id="detail-email-link"></a></p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted mb-0">Subject</label>
                            <p class="fw-semibold fs-15 mb-0" id="detail-subject"></p>
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                            <label class="form-label text-muted mb-1">Message</label>
                            <div class="bg-light p-3 rounded" style="white-space: pre-wrap;" id="detail-message"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    ajax: "{{ route('backend.contact-submissions.index') }}",
                    columns: [
                        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'subject', name: 'subject' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'status', name: 'status', orderable: false, searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });

                initBulkDelete("{{ route('backend.contact-submissions.bulk-destroy') }}");
            });
        })(jQuery);

        $(document).on('shown.bs.collapse shown.bs.tab', function () {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust()
                .responsive.recalc();
        });

        // Function to update the sidebar badge count and visibility
        function updateSidebarBadge(count) {
            let badge = $('#sidebar-contact-badge');
            if (count > 0) {
                badge.text(count).show();
            } else {
                badge.text('').hide();
            }
        }

        function markAsRead(id) {
            let url = "{{ route('backend.contact-submissions.read', ':id') }}";
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
                            title: response.message || "Message marked as read",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                }
            });
        }

        function viewDetails(submission) {
            // Fill details
            $('#detail-name').text(submission.name);
            $('#detail-email-link').text(submission.email).attr('href', 'mailto:' + submission.email);
            $('#detail-subject').text(submission.subject);
            $('#detail-message').text(submission.message);

            // Open Modal
            let detailsModal = new bootstrap.Modal(document.getElementById('submissionDetailsModal'));
            detailsModal.show();

            // If it is unread, mark it as read automatically
            if (!submission.is_read) {
                let url = "{{ route('backend.contact-submissions.read', ':id') }}";
                $.ajax({
                    type: "POST",
                    url: url.replace(':id', submission.id),
                    data: {
                        id: submission.id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            $('.data-table').DataTable().ajax.reload();
                            updateSidebarBadge(response.unread_count);
                        }
                    }
                });
            }
        }

        function deleteData(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this contact submission?",
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
                                    title: response.message || "Submission deleted successfully",
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
