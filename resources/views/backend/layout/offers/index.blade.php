@extends('backend.master')

@section('title', 'Offers & Campaigns')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Offers & Campaigns</h5>
                        <p class="text-muted mb-0 fs-12">Manage discount campaigns, banners, and deals</p>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-primary btn-sm add-btn shadow-sm" onclick="openCreateModal()">
                            <i class="ri-add-line align-bottom me-1"></i> Create Offer Campaign
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0 data-table custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 60px;">ID</th>
                                    <th>Title</th>
                                    <th>Badge</th>
                                    <th>Promo Code</th>
                                    <th>Discount (%)</th>
                                    <th>Position</th>
                                    <th>Linked Products</th>
                                    <th>Expiry</th>
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

    <!-- Offer Modal -->
    <div class="modal fade" id="offerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold" id="modalTitle">Create Offer Campaign</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="offerForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="offer_id" name="id">
                    <div class="modal-body p-4">

                        <div class="mb-3">
                            <label for="products" class="form-label fw-semibold">
                                Products
                            </label>

                            <select
                                class="form-select"
                                id="products"
                                name="products[]"
                                multiple>

                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-image="{{ $product->main_image ? asset($product->main_image) : asset('assets/images/no-image.png') }}">
                                        {{ $product->name }} (${{ number_format($product->price,2) }})
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Up to 40% off Whey Protein" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="badge" class="form-label">Badge Text</label>
                                <input type="text" class="form-control" id="badge" name="badge" placeholder="e.g. Mega Sale, Bundle Deal">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sub_title" class="form-label">Sub-title</label>
                            <textarea class="form-control" id="sub_title" name="sub_title" rows="2" placeholder="e.g. Premium isolates and concentrates from top brands."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="promo_code" class="form-label">Promo Code / Coupon</label>
                                <input type="text" class="form-control" id="promo_code" name="promo_code" placeholder="e.g. WHEY40">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="discount_percent" class="form-label">Discount Percentage (%)</label>
                                <input type="number" class="form-control" id="discount_percent" name="discount_percent" placeholder="e.g. 40" step="0.01" min="0" max="100">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bg_color" class="form-label">Background HEX Color <span class="text-danger">*</span></label>
                                <div class="d-flex gap-2">
                                    <input type="color" class="form-control form-control-color" id="bg_color_picker" value="#10b981" style="width: 50px; height: 38px;">
                                    <input type="text" class="form-control" id="bg_color" name="bg_color" value="#10b981" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Display Position <span class="text-danger">*</span></label>
                                <select class="form-select" id="position" name="position" required>
                                    <option value="normal_deal">Normal Deal (Deals you'll love)</option>
                                    <option value="top_banner">Top Banner (Featured Offer Cards)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="expire_date" class="form-label">Expiry Date & Time</label>
                                <input type="datetime-local" class="form-control" id="expire_date" name="expire_date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="banner_image" class="form-label">Banner Image (Optional)</label>
                                <input type="file" class="form-control" id="banner_image" name="banner_image">
                                <div id="banner_image_preview" class="mt-2" style="display:none;">
                                    <img src="" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            </div>
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

        /* ===== Select2 Multiple - Modern Design Fix ===== */
        .select2-container {
            width: 100% !important;
            display: block !important;
        }

        .select2-container--default .select2-selection--multiple {
            width: 100%;
            min-height: 42px;
            max-height: 140px;
            overflow-y: auto;
            border: 1px solid #ced4da !important;
            border-radius: 8px !important;
            background: #fff !important;
            padding: 6px 8px !important;
            display: flex;
            align-items: flex-start;
            flex-wrap: wrap;
            box-shadow: none;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--open .select2-selection--multiple {
            border-color: #86b7fe !important;
            box-shadow: 0 0 0 .25rem rgba(13,110,253,.15) !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: flex !important;
            flex-wrap: wrap;
            gap: 6px;
            padding: 0 !important;
            margin: 0 !important;
            width: 100%;
        }

        /* Chip / tag design */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            display: flex;
            align-items: center;
            margin: 0 !important;
            padding: 5px 8px 5px 12px !important;
            background: linear-gradient(135deg, #0d6efd, #3b82f6) !important;
            color: #fff !important;
            border: none !important;
            border-radius: 20px !important;
            font-size: 12.5px;
            font-weight: 500;
            line-height: 1.3;
            box-shadow: 0 1px 3px rgba(13,110,253,.35);
            max-width: 100%;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
            padding: 0 4px 0 0;
            white-space: normal;
            word-break: break-word;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff !important;
            opacity: .85;
            margin-right: 6px;
            margin-left: 2px;
            order: -1;
            border-right: none !important;
            font-weight: 700;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #ffc107 !important;
            background: transparent !important;
            opacity: 1;
        }

        /* Search input inside the box */
        .select2-container--default .select2-search--inline {
            margin: 0 !important;
            flex: 1 1 auto;
        }

        .select2-container--default .select2-search--inline .select2-search__field {
            margin: 4px 0 0 0 !important;
            padding: 2px 4px !important;
            font-size: 13.5px;
            min-height: 26px;
        }

        /* Placeholder text when empty */
        .select2-container--default .select2-selection--multiple .select2-selection__placeholder {
            color: #98a2b3;
            font-size: 13.5px;
            padding: 4px 2px;
        }

        /* Dropdown panel */
        .select2-dropdown {
            border: 1px solid #dee2e6 !important;
            border-radius: 10px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,.12);
            overflow: hidden;
            margin-top: 4px;
        }

        .select2-search--dropdown {
            padding: 10px !important;
            background: #f8f9fa;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid #ced4da !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            font-size: 13.5px;
        }

        .select2-search--dropdown .select2-search__field:focus {
            outline: none;
            border-color: #86b7fe !important;
            box-shadow: 0 0 0 .2rem rgba(13,110,253,.15);
        }

        .select2-results__options {
            max-height: 260px;
            overflow-y: auto;
            padding: 6px;
        }

        .select2-results__option {
            padding: 9px 12px !important;
            border-radius: 6px;
            font-size: 13.5px;
            margin-bottom: 2px;
        }

        .select2-results__option--highlighted {
            background: #0d6efd !important;
            color: #fff !important;
        }

        .select2-results__option[aria-selected="true"] {
            background: #e7f1ff;
            color: #0d6efd;
            font-weight: 500;
        }

        /* Scrollbar styling for chip box + dropdown */
        .select2-container--default .select2-selection--multiple::-webkit-scrollbar,
        .select2-results__options::-webkit-scrollbar {
            width: 6px;
        }
        .select2-container--default .select2-selection--multiple::-webkit-scrollbar-thumb,
        .select2-results__options::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
@endpush

@push('scripts-bottom')
    <script>
        (function ($) {
            $(function () {
                function formatProduct(product) {
                    if (!product.id) {
                        return product.text;
                    }
                    let imageAttr = $(product.element).attr('data-image');
                    let $product = $(
                        '<span class="d-flex align-items-center"><img src="' + imageAttr + '" class="rounded me-2" style="width: 24px; height: 24px; object-fit: cover;" /> ' + product.text + '</span>'
                    );
                    return $product;
                }

                $('#offerModal').on('show.bs.modal', function () {
                    if (!$('#products').hasClass('select2-hidden-accessible')) {
                        $('#products').select2({
                            dropdownParent: $('#offerModal'),
                            width: '100%',
                            placeholder: 'Select Products',
                            allowClear: true,
                            closeOnSelect: false,
                            templateResult: formatProduct,
                            templateSelection: formatProduct
                        });
                    }
                });

                // Sync bg color picker and hex text input
                $('#bg_color_picker').on('input', function() {
                    $('#bg_color').val($(this).val());
                });
                $('#bg_color').on('input', function() {
                    $('#bg_color_picker').val($(this).val());
                });

                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('backend.offer.index') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'title', name: 'title', className: 'fw-bold text-primary' },
                        { data: 'badge', name: 'badge', render: function(data) { return data ? data : 'N/A'; } },
                        { data: 'promo_code', name: 'promo_code', render: function(data) { return data ? data : 'N/A'; } },
                        { data: 'discount_percent', name: 'discount_percent', render: function(data) { return data ? data + '%' : '0%'; } },
                        { data: 'position', name: 'position', render: function(data) { return data === 'top_banner' ? 'Top Banner' : 'Normal Deal'; } },
                        { data: 'products_count', name: 'products_count', className: 'text-center' },
                        { data: 'expire_date', name: 'expire_date', render: function(data) { 
                            if (!data) return '<span class="text-muted">No Expiry</span>';
                            let date = new Date(data);
                            return date.toLocaleString();
                        } },
                        { data: 'status', name: 'status', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ]
                });

                $('#offerForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#offer_id').val();
                    let url = id ? "{{ route('backend.offer.update', ':id') }}".replace(':id', id) : "{{ route('backend.offer.store') }}";
                    
                    let formData = new FormData(this);
                    if (id) {
                        formData.append('_method', 'PUT');
                    }

                    $.ajax({
                        url: url,
                        type: 'POST', // Use POST with _method=PUT to support multipart file upload in Laravel
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                $('#offerModal').modal('hide');
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
            $('#offer_id').val('');
            $('#offerForm')[0].reset();
            $('#bg_color_picker').val('#10b981');
            $('#bg_color').val('#10b981');
            $('#banner_image_preview').hide();
            $('#products').val([]).trigger('change');
            $('#modalTitle').text('Create Offer Campaign');
            $('#offerModal').modal('show');
        }

        function editOffer(id) {
            let url = "{{ route('backend.offer.show', ':id') }}".replace(':id', id);
            $.get(url, function(response) {
                if (response.success) {
                    let data = response.data;
                    $('#offer_id').val(data.id);
                    $('#title').val(data.title);
                    $('#badge').val(data.badge);
                    $('#sub_title').val(data.sub_title);
                    $('#promo_code').val(data.promo_code);
                    $('#discount_percent').val(data.discount_percent);
                    $('#bg_color').val(data.bg_color);
                    $('#bg_color_picker').val(data.bg_color);
                    $('#position').val(data.position);
                    
                    if (data.expire_date) {
                        let expiryDate = new Date(data.expire_date);
                        let formattedDate = expiryDate.getFullYear() + '-' + 
                            String(expiryDate.getMonth() + 1).padStart(2, '0') + '-' + 
                            String(expiryDate.getDate()).padStart(2, '0') + 'T' + 
                            String(expiryDate.getHours()).padStart(2, '0') + ':' + 
                            String(expiryDate.getMinutes()).padStart(2, '0');
                        $('#expire_date').val(formattedDate);
                    } else {
                        $('#expire_date').val('');
                    }

                    if (data.banner_image) {
                        $('#banner_image_preview img').attr('src', '/' + data.banner_image);
                        $('#banner_image_preview').show();
                    } else {
                        $('#banner_image_preview').hide();
                    }

                    // Populate select2 multiple
                    let productIds = data.products.map(p => p.id);
                    $('#products').val(productIds).trigger('change');

                    $('#modalTitle').text('Edit Offer Campaign');
                    $('#offerModal').modal('show');
                }
            });
        }

        function changeStatus(id) {
            let url = "{{ route('backend.offer.status', ':id') }}".replace(':id', id);
            $.post(url, { _token: "{{ csrf_token() }}" }, function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('.data-table').DataTable().ajax.reload();
                }
            });
        }

        function deleteData(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this offer campaign?",
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
                        success: function(response) {
                            if (response.success) {
                                $('.data-table').DataTable().ajax.reload();
                                toastr.success(response.message);
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush