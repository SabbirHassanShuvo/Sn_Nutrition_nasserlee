@extends('backend.master')

@section('title', 'Promo Codes')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Promo Codes</h5>
                        <p class="text-muted mb-0 fs-12">Create and manage discount coupons</p>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-primary btn-sm add-btn shadow-sm" onclick="openCreateModal()">
                            <i class="ri-add-line align-bottom me-1"></i> Create Promo Code
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0 data-table custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 60px;">ID</th>
                                    <th>Code</th>
                                    <th>Discount (%)</th>
                                    <th>Expiry</th>
                                    <th>Limit/Used</th>
                                    <th>Per User Limit</th>
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

    <!-- Promo Code Modal -->
    <div class="modal fade" id="promoCodeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold" id="modalTitle">Create Promo Code</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="promoCodeForm">
                    @csrf
                    <input type="hidden" id="promo_id" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="code" class="form-label">Promo Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="code" name="code" placeholder="e.g. SUMMER25" required>
                        </div>
                        <div class="mb-3">
                            <label for="discount_percent" class="form-label">Discount Percentage (%) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="discount_percent" name="discount_percent" placeholder="e.g. 15" required step="0.01" min="0" max="100">
                        </div>
                        <div class="mb-3">
                            <label for="expiry_date" class="form-label">Expiry Date & Time</label>
                            <input type="datetime-local" class="form-control" id="expiry_date" name="expiry_date">
                        </div>
                        <div class="mb-3">
                            <label for="usage_limit" class="form-label">Total Usage Limit</label>
                            <input type="number" class="form-control" id="usage_limit" name="usage_limit" placeholder="Empty for unlimited">
                        </div>
                        <div class="mb-3">
                            <label for="per_user_limit" class="form-label">Per User Usage Limit</label>
                            <input type="number" class="form-control" id="per_user_limit" name="per_user_limit" placeholder="Empty for unlimited">
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-2">
                        <button type="button" class="btn btn-ghost-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm px-3">Save Changes</button>
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
    </style>
@endpush

@push('scripts-bottom')
    <script>
        (function ($) {
            $(function () {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('backend.promo-code.index') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'code', name: 'code', className: 'fw-bold text-primary' },
                        { data: 'discount_percent', name: 'discount_percent', render: function(data) { return data + '%'; } },
                        { data: 'expiry_date', name: 'expiry_date', render: function(data) { 
                            if (!data) return '<span class="text-muted">No Expiry</span>';
                            let date = new Date(data);
                            return date.toLocaleString();
                        } },
                        { data: 'usage_limit', name: 'usage_limit', render: function(data, type, row) { 
                            let limit = data ? data : '∞';
                            return row.used_count + ' / ' + limit;
                        } },
                        { data: 'per_user_limit', name: 'per_user_limit', render: function(data) { 
                            return data ? data : '∞';
                        } },
                        { data: 'status', name: 'status', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ]
                });

                $('#promoCodeForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#promo_id').val();
                    let url = id ? "{{ route('backend.promo-code.update', ':id') }}".replace(':id', id) : "{{ route('backend.promo-code.store') }}";
                    let method = id ? 'PUT' : 'POST';

                    $.ajax({
                        url: url,
                        type: method,
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.success) {
                                $('#promoCodeModal').modal('hide');
                                $('.data-table').DataTable().ajax.reload();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON.errors;
                            Object.values(errors).forEach(err => toastr.error(err[0]));
                        }
                    });
                });
            });
        })(jQuery);

        function openCreateModal() {
            $('#promo_id').val('');
            $('#promoCodeForm')[0].reset();
            $('#modalTitle').text('Create Promo Code');
            $('#promoCodeModal').modal('show');
        }

        function editPromoCode(id) {
            let url = "{{ route('backend.promo-code.show', ':id') }}".replace(':id', id);
            $.get(url, function(response) {
                if (response.success) {
                    let data = response.data;
                    $('#promo_id').val(data.id);
                    $('#code').val(data.code);
                    $('#discount_percent').val(data.discount_percent);
                    if (data.expiry_date) {
                        let expiryDate = new Date(data.expiry_date);
                        // Format to YYYY-MM-DDTHH:MM
                        let formattedDate = expiryDate.getFullYear() + '-' + 
                            String(expiryDate.getMonth() + 1).padStart(2, '0') + '-' + 
                            String(expiryDate.getDate()).padStart(2, '0') + 'T' + 
                            String(expiryDate.getHours()).padStart(2, '0') + ':' + 
                            String(expiryDate.getMinutes()).padStart(2, '0');
                        $('#expiry_date').val(formattedDate);
                    }
                    $('#usage_limit').val(data.usage_limit);
                    $('#per_user_limit').val(data.per_user_limit);
                    $('#modalTitle').text('Edit Promo Code');
                    $('#promoCodeModal').modal('show');
                }
            });
        }

        function changeStatus(id) {
            let url = "{{ route('backend.promo-code.status', ':id') }}".replace(':id', id);
            $.post(url, { _token: "{{ csrf_token() }}" }, function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('.data-table').DataTable().ajax.reload();
                }
            });
        }

        function deleteData(url) {
            if (confirm('Are you sure you want to delete this promo code?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        if (response.success) {
                            $('.data-table').DataTable().ajax.reload();
                            toastr.success(response.message);
                        }
                    }
                });
            }
        }
    </script>
@endpush
