@extends('backend.master')

@section('title', 'Order Management')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Order Management</h5>
                        <p class="text-muted mb-0 fs-12">View and manage customer orders and payments</p>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0 data-table custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 60px;">ID</th>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Referred By</th>
                                    <th>Amount</th>
                                    <th>Payment</th>
                                    <th class="text-center">Status</th>
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

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold">Order Details: <span id="modalOrderNumber"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-semibold mb-2 fs-11">Customer Information</h6>
                            <p class="mb-1 fw-bold" id="modalCustomerName"></p>
                            <p class="mb-1 text-muted" id="modalCustomerEmail"></p>
                            <p class="mb-0 text-muted" id="modalCustomerPhone"></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-semibold mb-2 fs-11">Shipping Address</h6>
                            <p class="mb-0 text-muted" id="modalShippingAddress"></p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-semibold mb-2 fs-11">Delivery Method</h6>
                            <span class="badge bg-soft-primary text-primary fs-12" id="modalDeliveryMethod"></span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-semibold mb-2 fs-11">Preferred Delivery Date</h6>
                            <p class="mb-0 fw-medium" id="modalPreferredDate"></p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <h6 class="text-muted text-uppercase fw-semibold mb-2 fs-11">Order Status</h6>
                            <select class="form-select form-select-sm" id="orderStatusUpdate">
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipping">Shipping</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted text-uppercase fw-semibold mb-2 fs-11">Payment Method</h6>
                            <span class="badge bg-soft-info text-info fs-12" id="modalPaymentMethod"></span>
                        </div>
                        <div class="col-md-4 text-end">
                            <h6 class="text-muted text-uppercase fw-semibold mb-2 fs-11">Total Amount</h6>
                            <h5 class="text-primary fw-bold" id="modalOrderTotal"></h5>
                        </div>
                    </div>

                    <div id="referralSection" style="display: none;" class="bg-soft-success p-3 rounded mb-4 border border-success border-opacity-10">
                        <h6 class="text-success text-uppercase fw-bold mb-2 fs-11">Referral Information</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 fs-13"><strong>Partner:</strong> <span id="modalReferrerName" class="text-dark"></span></p>
                                <p class="mb-0 fs-12 text-muted" id="modalReferrerEmail"></p>
                            </div>
                            <div class="text-end">
                                <p class="mb-0 fs-13"><strong>Commission:</strong> <span id="modalCommissionAmount" class="badge bg-success"></span></p>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-muted text-uppercase fw-semibold mb-3 fs-11">Order Items</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm align-middle table-borderless">
                            <thead class="table-light fs-11">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody id="modalOrderItems"></tbody>
                        </table>
                    </div>

                    <div id="bankTransferSection" style="display: none;" class="mt-4 border-top pt-3">
                        <h6 class="text-primary text-uppercase fw-bold mb-3 fs-11">Bank Transfer Information</h6>
                        <div id="bankTransferData">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center">
                                    <a href="" id="modalReceiptLink" target="_blank">
                                        <img id="modalReceiptImage" src="" class="img-fluid rounded border p-1" style="max-height: 180px; width: 100%; object-fit: contain;">
                                    </a>
                                </div>
                                <div class="col-md-8">
                                    <div class="bg-light p-3 rounded shadow-sm">
                                        <div class="mb-2 fs-13"><strong>Sender Name:</strong> <span id="modalSenderName" class="text-muted"></span></div>
                                        <div class="mb-2 fs-13"><strong>Bank Name:</strong> <span id="modalSenderBank" class="text-muted"></span></div>
                                        <div class="mb-2 fs-13"><strong>Last 4 Digits:</strong> <span id="modalAccountDigits" class="text-muted"></span></div>
                                        <div class="mb-3 fs-13"><strong>Payment Status:</strong> <span id="modalTransferStatus" class="badge"></span></div>
                                        
                                        <div id="paymentActionBtns" class="d-flex gap-2">
                                            <button type="button" onclick="verifyPayment('approved')" class="btn btn-success btn-sm w-100">Approve Payment & Confirm Order</button>
                                            <button type="button" onclick="verifyPayment('rejected')" class="btn btn-danger btn-sm w-100">Reject</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="noReceiptMessage" style="display: none;" class="alert alert-warning py-2">
                            <i class="ri-error-warning-line me-2"></i> No bank transfer receipt found for this order.
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-2">
                    <button type="button" class="btn btn-ghost-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="updateOrderStatus()" class="btn btn-primary btn-sm px-3">Update Order</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles-top')
    <style>
        .custom-table thead th { font-size: 11px; text-transform: uppercase; font-weight: 700; padding: 12px 15px; letter-spacing: 0.5px; }
        .custom-table tbody td { padding: 10px 15px; font-size: 13.5px; }
        .btn-soft-primary { background-color: rgba(64, 81, 137, 0.1); color: #405189; border: none; }
        .btn-soft-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; border: none; }
        .btn-soft-primary:hover { background-color: #405189; color: #fff; }
        .btn-soft-danger:hover { background-color: #f06548; color: #fff; }
    </style>
@endpush

@push('scripts-bottom')
    <script>
        var currentOrderId = null;

        (function ($) {
            $(function () {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('backend.order.index') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'order_number', name: 'order_number' },
                        { data: 'customer', name: 'customer' },
                        { data: 'referred_by', name: 'referred_by' },
                        { data: 'amount', name: 'amount' },
                        { data: 'payment', name: 'payment' },
                        { data: 'status', name: 'status', className: 'text-center' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ]
                });
            });
        })(jQuery);

        function viewOrder(id) {
            currentOrderId = id;
            let url = "{{ route('backend.order.show', ':id') }}";
            $.ajax({
                type: "GET",
                url: url.replace(':id', id),
                success: function (response) {
                    if (response.success) {
                        let order = response.data;
                        $('#modalOrderNumber').text(order.order_number);
                        $('#modalCustomerName').text(order.full_name || (order.user ? order.user.name : 'Guest'));
                        $('#modalCustomerEmail').text(order.email);
                        $('#modalCustomerPhone').text(order.phone || '-');
                        $('#modalShippingAddress').html(`${order.address}<br>${order.city}, ${order.postal_code}<br>${order.country}`);
                        $('#modalDeliveryMethod').text(order.delivery_method ? order.delivery_method.toUpperCase() : 'STANDARD');
                        $('#modalPreferredDate').text(order.preferred_delivery_date || 'None');
                        $('#modalOrderTotal').text(parseFloat(order.total).toFixed(2) + ' MAD');
                        $('#orderStatusUpdate').val(order.status);
                        
                        let methodText = order.payment_method.toUpperCase().replace('_', ' ');
                        $('#modalPaymentMethod').text(methodText);

                        // Items
                        let itemsHtml = '';
                        if (order.items && order.items.length > 0) {
                            order.items.forEach(item => {
                                itemsHtml += `<tr>
                                    <td>
                                        <div class="fw-medium">${item.product ? item.product.name : 'Unknown Product'}</div>
                                    </td>
                                    <td class="text-center">${parseFloat(item.price).toFixed(2)}</td>
                                    <td class="text-center">${item.quantity}</td>
                                    <td class="text-end fw-bold">${(item.price * item.quantity).toFixed(2)}</td>
                                </tr>`;
                            });
                        } else {
                            itemsHtml = '<tr><td colspan="4" class="text-center text-muted">No items found</td></tr>';
                        }
                        $('#modalOrderItems').html(itemsHtml);

                        // Referral Data
                        if (order.affiliate_link && order.affiliate_link.user) {
                            $('#referralSection').show();
                            $('#modalReferrerName').text(order.affiliate_link.user.name);
                            $('#modalReferrerEmail').text(order.affiliate_link.user.email);
                            $('#modalCommissionAmount').text(parseFloat(order.commission_amount).toFixed(2) + ' MAD');
                        } else {
                            $('#referralSection').hide();
                        }

                        // Bank Transfer Section Handling
                        if (order.payment_method === 'bank_transfer') {
                            $('#bankTransferSection').show();
                            let bt = order.bank_transfer || order.bankTransfer;
                            
                            if (bt) {
                                $('#bankTransferData').show();
                                $('#noReceiptMessage').hide();
                                $('#modalSenderName').text(bt.sender_full_name);
                                $('#modalSenderBank').text(bt.sender_bank);
                                $('#modalAccountDigits').text(bt.account_last_4);
                                
                                let receiptUrl = bt.receipt_image ? ("{{ asset('') }}" + bt.receipt_image) : '';
                                $('#modalReceiptImage').attr('src', receiptUrl);
                                $('#modalReceiptLink').attr('href', receiptUrl);

                                let statusClass = bt.status === 'approved' ? 'bg-success' : (bt.status === 'rejected' ? 'bg-danger' : 'bg-warning');
                                $('#modalTransferStatus').text(bt.status.toUpperCase()).attr('class', 'badge ' + statusClass);
                                
                                if (bt.status === 'pending') {
                                    $('#paymentActionBtns').show();
                                } else {
                                    $('#paymentActionBtns').hide();
                                }
                            } else {
                                $('#bankTransferData').hide();
                                $('#noReceiptMessage').show();
                            }
                        } else {
                            $('#bankTransferSection').hide();
                        }

                        $('#orderViewModal').modal('show');
                    }
                }
            });
        }

        function updateOrderStatus() {
            let status = $('#orderStatusUpdate').val();
            let url = "{{ route('backend.order.status', ':id') }}";
            $.ajax({
                type: "POST",
                url: url.replace(':id', currentOrderId),
                data: { _token: "{{ csrf_token() }}", status: status },
                success: function (response) {
                    if (response.success) {
                        $('#orderViewModal').modal('hide');
                        $('.data-table').DataTable().ajax.reload();
                        toastr.success(response.message);
                    }
                }
            });
        }

        function verifyPayment(status) {
            let title = status === 'approved' ? 'Approve Payment?' : 'Reject Payment?';
            let text = status === 'approved' ? 'This will approve the payment and mark order as processing.' : 'Are you sure you want to reject this payment?';
            let confirmButtonText = status === 'approved' ? 'Yes, Approve' : 'Yes, Reject';
            let confirmButtonColor = status === 'approved' ? '#28a745' : '#dc3545';

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmButtonText
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('backend.order.verify-payment', ':id') }}";
                    $.ajax({
                        type: "POST",
                        url: url.replace(':id', currentOrderId),
                        data: { _token: "{{ csrf_token() }}", status: status },
                        success: function (response) {
                            if (response.success) {
                                toastr.success(response.message);
                                viewOrder(currentOrderId);
                                $('.data-table').DataTable().ajax.reload();
                            }
                        },
                        error: function() {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    });
                }
            });
        }

        function deleteData(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d',
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
                                Swal.fire(
                                    'Deleted!',
                                    'Order has been deleted.',
                                    'success'
                                );
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
