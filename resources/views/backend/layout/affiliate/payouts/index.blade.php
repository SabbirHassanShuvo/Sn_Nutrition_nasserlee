@extends('backend.master')

@section('title', 'Partner Payout Requests')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" id="payoutList">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Partner Payout Requests</h5>
                        <p class="text-muted mb-0 fs-12">Review partner withdrawal requests, deposit cash offline to their bank account/card, and upload receipt proof.</p>
                    </div>

                    <div class="flex-shrink-0 d-flex gap-2 align-items-center">
                        <select id="statusFilter" class="form-select form-select-sm" style="min-width: 140px;">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <button type="button" class="btn btn-danger btn-sm shadow-sm d-none align-items-center" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> Bulk Delete
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0 data-table custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px; text-align: center;"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                    <th class="ps-3" style="width: 100px;">Payout ID</th>
                                    <th>Partner Name</th>
                                    <th class="text-center" style="width: 130px;">Method</th>
                                    <th style="width: 120px;">Amount</th>
                                    <th>Payment Details</th>
                                    <th class="text-center" style="width: 100px;">Status</th>
                                    <th class="text-center" style="width: 120px;">Receipt Proof</th>
                                    <th style="width: 140px;">Date</th>
                                    <th class="text-end pe-3" style="width: 130px;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Dynamic Process Modal with Live Image Preview -->
    <div class="modal fade text-start" id="globalProcessModal" tabindex="-1" aria-labelledby="globalProcessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="processPayoutForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold text-primary" id="modalPayoutTitle">Process Payout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info py-2 fs-13 mb-3">
                            <div class="fw-bold mb-1" id="modalMethodTitle">Payment Details:</div>
                            <div id="modalDetailsContent"></div>
                            <div class="mt-2 text-success fw-bold fs-15" id="modalAmountText">Amount to Transfer: $0.00</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Update Status</label>
                            <select name="status" id="modalStatusSelect" class="form-select" required>
                                <option value="paid">Paid (Deposit Completed)</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload Deposit Receipt Proof</label>
                            <input type="file" name="receipt_image" id="receiptFileInput" class="form-control" accept="image/*,.pdf" onchange="previewSelectedImage(this)">
                            <small class="text-muted">Upload cash deposit receipt / bank slip screenshot as proof for partner.</small>
                        </div>

                        <!-- Receipt Live Image Preview Box -->
                        <div class="mb-3 text-center d-none" id="receiptPreviewBox">
                            <label class="form-label fw-bold d-block text-start">Receipt Preview:</label>
                            <a id="receiptPreviewLink" href="#" target="_blank">
                                <img id="receiptPreviewImg" src="" alt="Receipt Proof" class="img-thumbnail shadow-sm" style="max-height: 180px; width: auto; object-fit: contain;">
                            </a>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Admin Notes (Optional)</label>
                            <textarea name="admin_notes" id="modalAdminNotes" class="form-control" rows="2" placeholder="e.g. Cash deposited via Chase Bank Teller"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success btn-sm"><i class="ri-check-line me-1"></i> Save & Upload Proof</button>
                    </div>
                </form>
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
            padding: 0.45rem 0.9rem;
            border-radius: 6px;
            background-color: #f3f6f9;
            width: 240px;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #405189;
            background-color: #fff;
            box-shadow: 0 0 0 0.15rem rgba(64, 81, 137, 0.15);
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e9ebec;
            border-radius: 6px;
            padding: 0.3rem 1.5rem 0.3rem 0.7rem;
        }
        .btn-soft-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; border: none; }
        .btn-soft-danger:hover { background-color: #f06548; color: #fff; }
    </style>
@endpush

@push('scripts-bottom')
    <script>
        (function ($) {
            $(function () {
                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 10,
                    responsive: { details: true },
                    dom: '<"row mb-3 px-3 mt-3 align-items-center"<"col-md-6"l><"col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3 px-3 pb-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                    ajax: {
                        url: "{{ route('backend.affiliate-payout.index') }}",
                        data: function (d) {
                            d.status = $('#statusFilter').val();
                        }
                    },
                    columns: [
                        {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                        {data: 'payout_number', name: 'payout_number', className: 'ps-3 fw-bold'},
                        {data: 'partner_name', name: 'user.name'},
                        {data: 'method', name: 'type', className: 'text-center'},
                        {data: 'amount', name: 'amount'},
                        {data: 'payment_details', name: 'account_name'},
                        {data: 'status', name: 'status', className: 'text-center'},
                        {data: 'receipt_proof', name: 'receipt_proof', orderable: false, searchable: false, className: 'text-center'},
                        {data: 'date', name: 'created_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end pe-3'},
                    ]
                });

                $('#statusFilter').on('change', function () {
                    table.ajax.reload();
                });

                initBulkDelete("{{ route('backend.affiliate-payout.bulk-destroy') }}");
            });
        })(jQuery);

        function openProcessModal(id, payoutNum, type, amount, bankName, accountName, accountNo, routingNo, cardType, cardLastFour, status, notes, receiptUrl) {
            var url = "{{ route('backend.affiliate-payout.process', ':id') }}".replace(':id', id);
            $('#processPayoutForm').attr('action', url);
            $('#modalPayoutTitle').text('Process Payout ' + payoutNum);
            $('#modalAmountText').text('Amount to Transfer: $' + amount);
            $('#modalAdminNotes').val(notes || '');
            $('#receiptFileInput').val('');

            if (status === 'rejected') {
                $('#modalStatusSelect').val('rejected');
            } else {
                $('#modalStatusSelect').val('paid');
            }

            var html = '';
            if (type === 'debit_card') {
                $('#modalMethodTitle').text('Debit Card Details:');
                html += '<div><strong>Card Type:</strong> ' + (cardType || 'Visa') + '</div>';
                html += '<div><strong>Card Last 4:</strong> **' + (cardLastFour || '2917') + '</div>';
                html += '<div><strong>Cardholder Name:</strong> ' + (accountName || '') + '</div>';
            } else {
                $('#modalMethodTitle').text('Bank Transfer Details:');
                html += '<div><strong>Bank Name:</strong> ' + (bankName || '') + '</div>';
                html += '<div><strong>Account Name:</strong> ' + (accountName || '') + '</div>';
                html += '<div><strong>Account Number:</strong> ' + (accountNo || '') + '</div>';
                if (routingNo) {
                    html += '<div><strong>Routing/SWIFT:</strong> ' + routingNo + '</div>';
                }
            }
            $('#modalDetailsContent').html(html);

            // Existing Receipt Image Preview
            if (receiptUrl) {
                $('#receiptPreviewBox').removeClass('d-none');
                $('#receiptPreviewImg').attr('src', receiptUrl);
                $('#receiptPreviewLink').attr('href', receiptUrl);
            } else {
                $('#receiptPreviewBox').addClass('d-none');
            }

            var bsModal = new bootstrap.Modal(document.getElementById('globalProcessModal'));
            bsModal.show();
        }

        function previewSelectedImage(input) {
            if (input.files && input.files[0]) {
                var file = input.files[0];
                if (file.type.match('image.*')) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#receiptPreviewBox').removeClass('d-none');
                        $('#receiptPreviewImg').attr('src', e.target.result);
                        $('#receiptPreviewLink').attr('href', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
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
                            } else {
                                Swal.fire('Error!', response.message || 'Failed to delete.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush
