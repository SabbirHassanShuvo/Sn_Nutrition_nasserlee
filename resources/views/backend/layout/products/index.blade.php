@extends('backend.master')

@section('title', 'Product Management')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" id="productList">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Product Inventory</h5>
                        <p class="text-muted mb-0 fs-12">Manage your catalog, stock and pricing</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm shadow-sm d-none align-items-center" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> Bulk Delete
                        </button>
                        <a class="btn btn-primary btn-sm add-btn shadow-sm d-flex align-items-center" href="{{route('backend.product.create')}}">
                            <i class="ri-add-line align-bottom me-1"></i> Add New Product
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0 data-table custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px; text-align: center;"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                    <th style="width: 80px;" class="ps-3">Image</th>
                                    <th class="text-start">Product Details</th>
                                    <th class="text-start" style="width: 120px;">Category</th>
                                    <th class="text-center" style="width: 120px;">Price</th>
                                    <th class="text-center" style="width: 120px;">Stock</th>
                                    <th class="text-center" style="width: 100px;">Status</th>
                                    <th class="text-center" style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Modal -->
    <div class="modal fade" id="productViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold" id="modalProductName">Product Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-md-5 bg-light p-4 border-end">
                            <div class="text-center mb-4">
                                <img id="modalProductImage" src="" class="img-fluid rounded shadow-sm" alt="Product Image" style="max-height: 300px;">
                            </div>
                            <div id="modalProductGallery" class="row g-2">
                                <!-- Gallery images will be injected here -->
                            </div>
                        </div>
                        <div class="col-md-7 p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-soft-primary text-primary mb-2" id="modalProductCategory">Category</span>
                                    <h4 class="fw-bold mb-1" id="modalProductTitle">Name</h4>
                                    <p class="text-muted fs-13 mb-0" id="modalProductBrand">Brand Name</p>
                                </div>
                                <div class="text-end">
                                    <h4 class="text-primary fw-bold mb-0" id="modalProductPrice">0.00 MAD</h4>
                                    <del class="text-muted fs-12" id="modalProductOldPrice"></del>
                                </div>
                            </div>

                            <div class="row mb-4 bg-light rounded p-3 mx-0">
                                <div class="col-6">
                                    <p class="text-muted fs-11 text-uppercase mb-1">Stock Status</p>
                                    <span id="modalProductStock">In Stock</span>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted fs-11 text-uppercase mb-1">Serving Size</p>
                                    <span class="fw-medium" id="modalProductServings">-</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold fs-13 text-uppercase mb-2 border-bottom pb-1">Short Description</h6>
                                <p class="text-muted fs-13 lh-base" id="modalProductShortDesc"></p>
                            </div>

                            <div class="nav-tabs-custom mb-3">
                                <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active fs-12" data-bs-toggle="tab" href="#tab-features" role="tab">Features</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link fs-12" data-bs-toggle="tab" href="#tab-nutrition" role="tab">Nutrition</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link fs-12" data-bs-toggle="tab" href="#tab-ingredients" role="tab">Ingredients</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link fs-12" data-bs-toggle="tab" href="#tab-usages" role="tab">Usages</a>
                                    </li>
                                </ul>
                                <div class="tab-content p-3 bg-light rounded-bottom border border-top-0">
                                    <div class="tab-pane active" id="tab-features" role="tabpanel">
                                        <ul class="list-unstyled mb-0 fs-13 text-muted" id="modalProductFeatures"></ul>
                                    </div>
                                    <div class="tab-pane" id="tab-nutrition" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless mb-0 fs-12">
                                                <tbody id="modalProductNutrition"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-ingredients" role="tabpanel">
                                        <ul class="list-unstyled mb-0 fs-13 text-muted" id="modalProductIngredients"></ul>
                                    </div>
                                    <div class="tab-pane" id="tab-usages" role="tabpanel">
                                        <div class="fs-13 text-muted lh-base" id="modalProductUsages"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-2">
                    <button type="button" class="btn btn-ghost-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <a href="" id="modalEditBtn" class="btn btn-primary btn-sm px-3">Edit Product</a>
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
        .custom-table tbody td {
            padding: 10px 15px;
            font-size: 13.5px;
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e9ebec;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            background-color: #f3f6f9;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e9ebec;
            border-radius: 6px;
            padding: 0.3rem 1.5rem 0.3rem 0.7rem;
        }
        .btn-soft-primary { background-color: rgba(64, 81, 137, 0.1); color: #405189; border: none; }
        .btn-soft-info { background-color: rgba(41, 156, 219, 0.1); color: #299cdb; border: none; }
        .btn-soft-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; border: none; }
        .btn-soft-primary:hover { background-color: #405189; color: #fff; }
        .btn-soft-info:hover { background-color: #299cdb; color: #fff; }
        .btn-soft-danger:hover { background-color: #f06548; color: #fff; }
        
        .nav-tabs-custom .nav-link { padding: 8px 10px; font-weight: 600; }
        .modal-lg { max-width: 900px; }
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
                    dom: '<"row mb-3 px-3 mt-3"<"col-md-6"l><"col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3 px-3 pb-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                    ajax: "{{ route('backend.product.index') }}",
                    columns: [
                        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'image', name: 'image', orderable: false, searchable: false, className: 'ps-3 text-center' },
                        { data: 'name', name: 'name', className: 'text-start fw-medium' },
                        { data: 'category', name: 'category', className: 'text-start' },
                        { data: 'price', name: 'price', className: 'text-center' },
                        { data: 'stock', name: 'stock', className: 'text-center', orderable: false, searchable: false },
                        { data: 'status', name: 'status', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ]
                });
                
                initBulkDelete("{{ route('backend.product.bulk-destroy') }}");
            });
        })(jQuery);

        function viewProduct(id) {
            let url = "{{ route('backend.product.show', ':id') }}";
            $.ajax({
                type: "GET",
                url: url.replace(':id', id),
                success: function (response) {
                    if (response.success) {
                        let product = response.data;
                        $('#modalProductName').text(product.name);
                        $('#modalProductTitle').text(product.name);
                        $('#modalProductCategory').text(product.category ? product.category.name : 'Uncategorized');
                        $('#modalProductBrand').text(product.brand_data ? product.brand_data.name : 'No Brand');
                        $('#modalProductPrice').text(product.price + ' MAD');
                        $('#modalProductOldPrice').text(product.old_price ? product.old_price + ' MAD' : '');
                        $('#modalProductServings').text(product.servings || '-');
                        $('#modalProductShortDesc').text(product.short_description || 'No description available.');
                        
                        let img = product.main_image ? "{{ asset('') }}" + product.main_image : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(product.name);
                        $('#modalProductImage').attr('src', img);

                        // Gallery Images
                        let galleryHtml = '';
                        if (product.gallery_images && product.gallery_images.length > 0) {
                            product.gallery_images.forEach(gImg => {
                                let gUrl = "{{ asset('') }}" + gImg;
                                galleryHtml += `
                                    <div class="col-3">
                                        <a href="${gUrl}" target="_blank">
                                            <img src="${gUrl}" class="img-fluid rounded border p-1" style="height: 60px; width: 100%; object-fit: cover;">
                                        </a>
                                    </div>`;
                            });
                        }
                        $('#modalProductGallery').html(galleryHtml);

                        // Stock Badge
                        let stockHtml = product.in_stock ? 
                            '<span class="badge bg-soft-success text-success fs-12"><i class="ri-checkbox-circle-line me-1"></i> In Stock</span>' : 
                            '<span class="badge bg-soft-danger text-danger fs-12"><i class="ri-error-warning-line me-1"></i> Out of Stock</span>';
                        $('#modalProductStock').html(stockHtml);

                        // Features
                        let featuresHtml = '';
                        if (product.features && product.features.length > 0) {
                            product.features.forEach(f => {
                                featuresHtml += `<li class="mb-2 d-flex align-items-center"><i class="ri-checkbox-circle-fill text-success me-2 fs-15"></i> ${f.title}</li>`;
                            });
                        } else {
                            featuresHtml = '<li class="text-muted italic">No features listed</li>';
                        }
                        $('#modalProductFeatures').html(featuresHtml);

                        // Nutrition
                        let nutritionHtml = '';
                        if (product.nutrition && product.nutrition.length > 0) {
                            product.nutrition.forEach(n => {
                                nutritionHtml += `<tr><td class="fw-medium">${n.name}</td><td class="text-end text-primary fw-bold">${n.amount}</td></tr>`;
                            });
                        } else {
                            nutritionHtml = '<tr><td colspan="2" class="text-center text-muted">No nutrition data</td></tr>';
                        }
                        $('#modalProductNutrition').html(nutritionHtml);

                        // Ingredients
                        let ingredientsHtml = '';
                        if (product.ingredients && product.ingredients.length > 0) {
                            product.ingredients.forEach(i => {
                                ingredientsHtml += `<li class="mb-2 d-flex align-items-center"><i class="ri-flask-line text-info me-2 fs-15"></i> ${i.title}</li>`;
                            });
                        } else {
                            ingredientsHtml = '<li class="text-muted italic">No ingredients listed</li>';
                        }
                        $('#modalProductIngredients').html(ingredientsHtml);

                        // Usages
                        let usagesHtml = '';
                        if (product.usages && product.usages.length > 0) {
                            product.usages.forEach(u => {
                                usagesHtml += `<div class="mb-3 p-2 border-start border-primary border-3 bg-white shadow-sm rounded-end">${u.content}</div>`;
                            });
                        } else {
                            usagesHtml = '<p class="text-muted italic">No usage instructions provided</p>';
                        }
                        $('#modalProductUsages').html(usagesHtml);

                        // Edit Button
                        let editUrl = "{{ route('backend.product.edit', ':id') }}";
                        $('#modalEditBtn').attr('href', editUrl.replace(':id', product.id));

                        $('#productViewModal').modal('show');
                    }
                }
            });
        }

        function showStatusChangeAlert(id) {
            let url = "{{ route('backend.product.status', ':id') }}";
            $.ajax({
                type: "POST",
                url: url.replace(':id', id),
                data: { id: id, _token: "{{csrf_token()}}" },
                success: function (response) {
                    if (response.success) {
                        $('.data-table').DataTable().ajax.reload();
                        toastr.success(response.message || "Status updated successfully");
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
                                toastr.success(response.message || "Deleted successfully");
                            } else {
                                toastr.error(response.message || "Failed to delete");
                            }
                        }
                    });
                }
            })
        }
    </script>
@endpush
