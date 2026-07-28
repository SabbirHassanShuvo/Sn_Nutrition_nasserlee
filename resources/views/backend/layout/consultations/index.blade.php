@extends('backend.master')

@section('title', 'Consultation Bookings')

@push('styles-top')
    <style>
        .custom-filter-btn {
            font-size: 12px;
            font-weight: 500;
            padding: 5px 14px;
            border-radius: 20px;
            transition: all 0.2s ease-in-out;
        }
        .custom-filter-btn.active {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" id="consultationCard">
                <div class="card-header border-0 bg-white py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Consultation Bookings</h5>
                        <p class="text-muted mb-0 fs-12">Manage customer video & audio call bookings with automatic Zoom meeting generation</p>
                    </div>

                    <!-- Status Filter Tabs -->
                    <div class="d-flex align-items-center gap-1 bg-light p-1 rounded-pill" id="statusFilterGroup">
                        <button type="button" class="btn btn-primary btn-sm rounded-pill custom-filter-btn active" data-status="all">All</button>
                        <button type="button" class="btn btn-ghost-warning btn-sm rounded-pill custom-filter-btn text-warning" data-status="pending">Pending</button>
                        <button type="button" class="btn btn-ghost-success btn-sm rounded-pill custom-filter-btn text-success" data-status="approved">Approved</button>
                        <button type="button" class="btn btn-ghost-danger btn-sm rounded-pill custom-filter-btn text-danger" data-status="rejected">Rejected</button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0" id="consultationTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Booking Ref / Type</th>
                                    <th>Customer Details</th>
                                    <th>Assigned Specialist</th>
                                    <th>Date & Slot</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Zoom Meeting Link</th>
                                    <th class="text-center" style="width: 180px;">Actions</th>
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
            var currentStatus = 'all';

            var table = $('#consultationTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('backend.consultation-booking.index') }}",
                    data: function(d) {
                        d.status = currentStatus;
                    }
                },
                columns: [
                    { data: 'booking_info', name: 'booking_number' },
                    { data: 'user_details', name: 'user_name' },
                    { data: 'specialist_info', name: 'specialist.name' },
                    { data: 'schedule', name: 'booking_date' },
                    { data: 'status_badge', name: 'status', className: 'text-center' },
                    { data: 'zoom_link', name: 'zoom_join_url', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ]
            });

            // Filter Tabs Click Event
            $('.custom-filter-btn').on('click', function() {
                $('.custom-filter-btn').removeClass('active btn-primary btn-warning btn-success btn-danger')
                                       .addClass('btn-ghost-secondary');
                
                $(this).removeClass('btn-ghost-secondary').addClass('active btn-primary');
                currentStatus = $(this).data('status');
                table.draw();
            });

            // Generate Zoom Link & Approve Booking Action
            $(document).on('click', '.generate-zoom-btn', function() {
                var id = $(this).data('id');
                var btn = $(this);

                Swal.fire({
                    title: 'Approve & Create Zoom Meeting?',
                    text: "This will create a Zoom meeting and send the link to the customer email automatically.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="ri-video-chat-line me-1"></i> Approve & Generate Link',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return $.ajax({
                            url: "{{ url('admin/consultation-booking') }}/" + id + "/approve-zoom",
                            type: "POST",
                            data: { _token: "{{ csrf_token() }}" }
                        }).then(function(res) {
                            return res;
                        }).catch(function(err) {
                            Swal.showValidationMessage(err.responseJSON ? err.responseJSON.message : 'Action failed');
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        Swal.fire({
                            title: 'Success!',
                            text: result.value.message,
                            icon: 'success',
                            confirmButtonColor: '#405189'
                        });
                        table.draw(false);
                    }
                });
            });

            // Resend Email Action
            $(document).on('click', '.resend-email-btn', function() {
                var id = $(this).data('id');
                var btn = $(this);

                Swal.fire({
                    title: 'Resend Zoom Email?',
                    text: "Send the Zoom meeting details email to customer again?",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#0284c7',
                    confirmButtonText: 'Yes, Send Email'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.prop('disabled', true);
                        $.ajax({
                            url: "{{ url('admin/consultation-booking') }}/" + id + "/approve-zoom",
                            type: "POST",
                            data: { _token: "{{ csrf_token() }}" },
                            success: function(res) {
                                btn.prop('disabled', false);
                                toastr.success('Zoom email resent successfully to customer!');
                            },
                            error: function() {
                                btn.prop('disabled', false);
                                toastr.error('Failed to resend email.');
                            }
                        });
                    }
                });
            });

            // Reject Booking Action with SweetAlert2
            $(document).on('click', '.reject-btn', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Reject Consultation Booking?',
                    text: "Are you sure you want to reject this booking?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Reject It'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('admin/consultation-booking') }}/" + id + "/status",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                status: 'rejected'
                            },
                            success: function(res) {
                                toastr.success('Booking status updated to Rejected');
                                table.draw(false);
                            },
                            error: function() {
                                toastr.error('Failed to reject booking.');
                            }
                        });
                    }
                });
            });

            // Copy Zoom Link Handler
            $(document).on('click', '.copy-zoom-link', function() {
                var url = $(this).data('url');
                if (url) {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(url).then(function() {
                            toastr.success('Zoom meeting link copied to clipboard!');
                        });
                    } else {
                        var temp = $('<input>');
                        $('body').append(temp);
                        temp.val(url).select();
                        document.execCommand('copy');
                        temp.remove();
                        toastr.success('Zoom meeting link copied to clipboard!');
                    }
                }
            });

            // Copy Passcode Handler
            $(document).on('click', '.copy-passcode', function() {
                var passcode = $(this).data('passcode');
                if (passcode) {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(passcode).then(function() {
                            toastr.info('Passcode copied: ' + passcode);
                        });
                    } else {
                        var temp = $('<input>');
                        $('body').append(temp);
                        temp.val(passcode).select();
                        document.execCommand('copy');
                        temp.remove();
                        toastr.info('Passcode copied: ' + passcode);
                    }
                }
            });
        });
    </script>
@endpush
