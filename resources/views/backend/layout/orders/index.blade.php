@extends('backend.master')

@section('title', 'Order Management')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                </div>
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Order Management</h5>
                        <p class="text-muted mb-0 fs-12">View and manage customer orders and payments</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm shadow-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                            <i class="ri-add-line align-bottom me-1"></i> Add Order
                        </button>
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
                                    <th class="ps-3" style="width: 60px;">ID</th>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Referred By</th>
                                    <th>Amount</th>
                                    <th>Payment</th>
                                    <th>Sendit</th>
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

                    <!-- Sendit Details Section -->
                    <div class="row mb-4 bg-light p-3 rounded" id="senditDetailsSection" style="display: none;">
                        <div class="col-md-6">
                            <h6 class="text-primary text-uppercase fw-bold mb-2 fs-11">Sendit Tracking Code</h6>
                            <p class="mb-0 fw-bold fs-14 text-dark" id="modalSenditCode"></p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-semibold mb-2 fs-11">Payment Method</h6>
                            <span class="badge bg-soft-info text-info fs-12" id="modalPaymentMethod"></span>
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

    <!-- Add Order Modal -->
    <div class="modal fade" id="addOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form id="addOrderForm" method="POST">
                    @csrf
                    <div class="modal-header bg-primary py-3">
                        <h5 class="modal-title text-white fw-bold">Add Order</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                        <!-- Customer Details -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">First Name *</label>
                                <input type="text" name="first_name" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Last Name</label>
                                <input type="text" name="last_name" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email *</label>
                                <input type="email" name="email" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone *</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">+212</span>
                                    <input type="text" name="phone" class="form-control form-control-sm" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Address *</label>
                                <textarea name="address" class="form-control form-control-sm" rows="2" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Secondary Phone</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">+212</span>
                                    <input type="text" name="secondary_phone" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Select City *</label>
                                <select name="city" id="selectCityDropdown" class="form-select form-select-sm" required>
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Payment Method *</label>
                                <select name="payment_method" class="form-select form-select-sm" required>
                                    <option value="cod">Cash On Delivery</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Delivery Date *</label>
                                <input type="date" name="preferred_delivery_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <!-- Product Selection Area -->
                        <h6 class="text-primary text-uppercase fw-bold mb-3 fs-11 border-bottom pb-2">Products</h6>
                        <div id="productRowsContainer">
                            <!-- Template Row -->
                            <div class="product-row border-bottom pb-3 mb-3">
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label fs-12 fw-semibold">Brand</label>
                                        <select class="form-select form-select-sm brand-select">
                                            <option value="">Select Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fs-12 fw-semibold">Product *</label>
                                        <select class="form-select form-select-sm product-select" required>
                                            <option value="">Select Product</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fs-12 fw-semibold">Quantity *</label>
                                        <input type="number" class="form-control form-control-sm qty-input" value="1" min="1" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center justify-content-between">
                                        <div>
                                            <label class="form-label fs-12 fw-semibold">Price</label>
                                            <div class="price-display fw-bold text-dark fs-13">0.00 MAD</div>
                                        </div>
                                        <button type="button" class="btn btn-soft-danger btn-sm remove-row-btn ms-2" style="display: none;">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <button type="button" id="addMoreProductsBtn" class="btn btn-soft-primary btn-sm">
                                <i class="ri-add-line align-bottom me-1"></i> Add More
                            </button>
                        </div>

                        <!-- Calculation details -->
                        <div class="row align-items-center bg-light p-3 rounded">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Shipping Fees *</label>
                                <input type="number" id="addShippingFee" name="delivery_fee" class="form-control form-control-sm" value="0.00" step="0.01" min="0">
                            </div>
                            <div class="col-md-4" id="discountInputWrapper" style="display: none;">
                                <label class="form-label fw-semibold">Discount Amount</label>
                                <input type="number" id="addDiscount" name="discount" class="form-control form-control-sm" value="0.00" step="0.01" min="0">
                            </div>
                            <div class="col-md-4 ms-auto">
                                <label class="form-label fw-semibold">Total Price *</label>
                                <input type="text" id="totalPriceDisplay" class="form-control form-control-sm fw-bold text-primary" readonly value="0.00 MAD">
                            </div>
                        </div>

                        <!-- Options checkboxes -->
                        <div class="row mt-3">
                            <div class="col-md-12 d-flex flex-column gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="freeDeliveryCheckbox">
                                    <label class="form-check-label fs-13 fw-semibold text-dark" for="freeDeliveryCheckbox">Free delivery</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="discountedPriceCheckbox">
                                    <label class="form-check-label fs-13 fw-semibold text-dark" for="discountedPriceCheckbox">Discounted Price</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="buy4get1Checkbox">
                                    <label class="form-check-label fs-13 fw-semibold text-dark" for="buy4get1Checkbox">Buy 4 Get 1 Free Discount</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="buy3get30Checkbox">
                                    <label class="form-check-label fs-13 fw-semibold text-dark" for="buy3get30Checkbox">Buy 3 Get 30% Discount on Cheapest Product</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer bg-light border-0 py-2">
                        <button type="button" class="btn btn-ghost-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm px-4">
                            <i class="ri-save-line align-bottom me-1"></i> Add Order
                        </button>
                    </div>
                </form>
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
                        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'order_number', name: 'order_number' },
                        { data: 'customer', name: 'customer' },
                        { data: 'referred_by', name: 'referred_by' },
                        { data: 'amount', name: 'amount' },
                        { data: 'payment', name: 'payment' },
                        { data: 'sendit_shipment', name: 'sendit_shipment' },
                        { data: 'status', name: 'status', className: 'text-center' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ]
                });
                
                initBulkDelete("{{ route('backend.order.bulk-destroy') }}");
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

                        // Populate Sendit Details
                        if (order.sendit_delivery_code) {
                            $('#senditDetailsSection').show();
                            $('#modalSenditCode').text(order.sendit_delivery_code);
                        } else {
                            $('#senditDetailsSection').hide();
                        }
                        
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

        // Add Order dynamic JS
        const allProducts = @json($products);

        $('#addOrderModal').on('show.bs.modal', function() {
            $('#selectCityDropdown').html('<option value="">Loading Cities...</option>');
            $.ajax({
                type: "GET",
                url: "{{ route('backend.order.districts') }}",
                success: function(response) {
                    let html = '<option value="">Select City</option>';
                    if (response && response.length > 0) {
                        response.forEach(city => {
                            html += `<option value="${city.ville}">${city.ville} (${city.name})</option>`;
                        });
                    } else {
                        html = '<option value="">No cities returned from Sendit</option>';
                    }
                    $('#selectCityDropdown').html(html);
                },
                error: function() {
                    $('#selectCityDropdown').html('<option value="">Failed to load cities</option>');
                }
            });
        });

        $(document).on('change', '.brand-select', function() {
            let brandId = $(this).val();
            let row = $(this).closest('.product-row');
            let productSelect = row.find('.product-select');
            
            let html = '<option value="">Select Product</option>';
            let filtered = allProducts;
            if (brandId) {
                filtered = allProducts.filter(p => p.brand_id == brandId);
            }
            
            filtered.forEach(p => {
                html += `<option value="${p.id}" data-price="${p.price}">${p.name} (${parseFloat(p.price).toFixed(2)} MAD)</option>`;
            });
            productSelect.html(html);
            
            row.find('.price-display').text('0.00 MAD');
            calculateTotal();
        });

        $(document).on('change', '.product-select', function() {
            let option = $(this).find('option:selected');
            let price = parseFloat(option.data('price') || 0);
            let row = $(this).closest('.product-row');
            row.find('.price-display').text(price.toFixed(2) + ' MAD');
            calculateTotal();
        });

        $(document).on('input change', '.qty-input', function() {
            calculateTotal();
        });

        $(document).on('input change', '#addShippingFee, #addDiscount', function() {
            calculateTotal();
        });

        $(document).on('change', '#freeDeliveryCheckbox', function() {
            if ($(this).is(':checked')) {
                $('#addShippingFee').val('0.00').prop('readonly', true);
            } else {
                $('#addShippingFee').prop('readonly', false);
            }
            calculateTotal();
        });

        $(document).on('change', '#discountedPriceCheckbox, #buy4get1Checkbox, #buy3get30Checkbox', function() {
            calculateTotal();
        });

        $('#addMoreProductsBtn').on('click', function() {
            let container = $('#productRowsContainer');
            let newRow = container.find('.product-row').first().clone();
            
            newRow.find('.brand-select').val('');
            newRow.find('.product-select').html('<option value="">Select Product</option>').val('');
            newRow.find('.qty-input').val(1);
            newRow.find('.price-display').text('0.00 MAD');
            newRow.find('.remove-row-btn').show();
            
            container.append(newRow);
            calculateTotal();
        });

        $(document).on('click', '.remove-row-btn', function() {
            $(this).closest('.product-row').remove();
            calculateTotal();
        });

        function calculateTotal() {
            let subtotal = 0;
            let totalQty = 0;
            let productPrices = [];

            $('.product-row').each(function() {
                let price = parseFloat($(this).find('.product-select option:selected').data('price') || 0);
                let qty = parseInt($(this).find('.qty-input').val() || 0);
                subtotal += price * qty;
                totalQty += qty;
                for (let i = 0; i < qty; i++) {
                    productPrices.push(price);
                }
            });

            productPrices.sort((a, b) => a - b);

            let shippingFee = parseFloat($('#addShippingFee').val() || 0);
            let discount = 0;

            if ($('#discountedPriceCheckbox').is(':checked')) {
                $('#discountInputWrapper').show();
                discount = parseFloat($('#addDiscount').val() || 0);
            } else {
                $('#discountInputWrapper').hide();
                $('#addDiscount').val('0.00');
            }

            if ($('#buy4get1Checkbox').is(':checked')) {
                if (totalQty >= 5 && productPrices.length > 0) {
                    discount += productPrices[0];
                }
            }

            if ($('#buy3get30Checkbox').is(':checked')) {
                if (totalQty >= 3 && productPrices.length > 0) {
                    discount += productPrices[0] * 0.3;
                }
            }

            let total = subtotal + shippingFee - discount;
            if (total < 0) total = 0;

            $('#totalPriceDisplay').val(total.toFixed(2) + ' MAD');
        }

        $('#addOrderForm').on('submit', function(e) {
            e.preventDefault();
            
            let items = [];
            let valid = true;
            
            $('.product-row').each(function() {
                let productId = $(this).find('.product-select').val();
                let qty = $(this).find('.qty-input').val();
                let price = $(this).find('.product-select option:selected').data('price');
                
                if (!productId || !qty) {
                    valid = false;
                    return false;
                }
                
                items.push({
                    product_id: productId,
                    quantity: qty,
                    price: price
                });
            });
            
            if (!valid || items.length === 0) {
                toastr.error('Please select at least one product with quantity.');
                return;
            }
            
            let data = {
                _token: "{{ csrf_token() }}",
                first_name: $('input[name="first_name"]').val(),
                last_name: $('input[name="last_name"]').val(),
                email: $('input[name="email"]').val(),
                phone: $('input[name="phone"]').val(),
                secondary_phone: $('input[name="secondary_phone"]').val(),
                address: $('textarea[name="address"]').val(),
                city: $('#selectCityDropdown').val(),
                payment_method: $('select[name="payment_method"]').val(),
                preferred_delivery_date: $('input[name="preferred_delivery_date"]').val(),
                delivery_fee: $('#addShippingFee').val(),
                discount: $('#addDiscount').val(),
                items: items
            };
            
            let submitBtn = $('#addOrderForm').find('button[type="submit"]');
            let originalHtml = submitBtn.html();
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Creating...');
            
            $.ajax({
                type: "POST",
                url: "{{ route('backend.order.store') }}",
                data: data,
                success: function(response) {
                    submitBtn.prop('disabled', false).html(originalHtml);
                    if (response.success) {
                        $('#addOrderModal').modal('hide');
                        $('#addOrderForm')[0].reset();
                        
                        $('.product-row').not(':first').remove();
                        $('.product-row').find('.brand-select').val('');
                        $('.product-row').find('.product-select').html('<option value="">Select Product</option>');
                        $('.product-row').find('.qty-input').val(1);
                        $('.product-row').find('.price-display').text('0.00 MAD');
                        $('#addShippingFee').prop('readonly', false);
                        
                        $('.data-table').DataTable().ajax.reload();
                        Swal.fire('Success', response.message, 'success');
                    } else {
                        toastr.error(response.message || 'Failed to create order.');
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalHtml);
                    let msg = xhr.responseJSON?.message || 'Something went wrong. Please check fields.';
                    Swal.fire('Error', msg, 'error');
                }
            });
        });
    </script>
@endpush
