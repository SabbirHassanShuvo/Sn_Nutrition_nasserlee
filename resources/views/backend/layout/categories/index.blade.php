@extends('backend.master')

@section('title', 'Category Management')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" id="categoryList">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Categories</h5>
                        <p class="text-muted mb-0 fs-12">Manage product classifications</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm shadow-sm d-none align-items-center" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> Bulk Delete
                        </button>
                        <button type="button" class="btn btn-primary btn-sm add-btn shadow-sm d-flex align-items-center" onclick="openCreateModal()">
                            <i class="ri-add-line align-bottom me-1"></i> Add New Category
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0 data-table custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px; text-align: center;"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                    <th style="width: 80px;">Image</th>
                                    <th style="width: 60px;">Color</th>
                                    <th class="text-start">Category Name</th>
                                    <th>Slug</th>
                                    <th class="text-center" style="width: 120px;">Status</th>
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

    <!-- Category Modal -->
    <div class="modal fade text-start" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="categoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="category_id" id="category_id" value="{{ old('category_id') }}">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="categoryModalLabel">Create Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="category_name">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="category_name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter category name" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="category_color_text">Background Color</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 p-1 position-relative" style="overflow: hidden;">
                                    <div id="category_color_preview" style="width: 28px; height: 28px; background-color: #FF8000; border-radius: 6px; border: 1px solid rgba(0,0,0,0.15); transition: background-color 0.2s ease;"></div>
                                    <input type="color" id="category_color" value="#FF8000" class="position-absolute top-0 start-0 opacity-0 w-100 h-100" style="cursor: pointer; z-index: 2;" title="Click to pick color">
                                </span>
                                <input type="text" name="color" id="category_color_text" class="form-control border-start-0 border-end-0 shadow-none @error('color') is-invalid @enderror" value="#FF8000" placeholder="#FF8000" maxlength="7" style="font-family: monospace; font-size: 15px; letter-spacing: 0.5px;">
                                <button type="button" class="btn btn-outline-secondary border-start-0" id="copyColorBtn" title="Copy Color Code">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1 fs-12">Click swatch to pick or paste hex code (e.g. #FF8000)</small>
                            @error('color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Category Thumbnail</label>
                            <div id="dropify-wrapper-container">
                                <input type="file" name="image" id="category_image" class="dropify" data-height="200" accept="image/*" />
                            </div>
                            @error('image')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Create Category</button>
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
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            background-color: #f3f6f9;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e9ebec;
            border-radius: 6px;
            padding: 0.3rem 1.5rem 0.3rem 0.7rem;
        }
        .btn-soft-info { background-color: rgba(41, 156, 219, 0.1); color: #299cdb; border: none; }
        .btn-soft-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; border: none; }
        .btn-soft-info:hover { background-color: #299cdb; color: #fff; }
        .btn-soft-danger:hover { background-color: #f06548; color: #fff; }
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
                    ajax: "{{ route('backend.category.index') }}",
                    columns: [
                        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'image', name: 'image', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'color', name: 'color', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'name', name: 'name', className: 'text-start fw-medium' },
                        { data: 'slug', name: 'slug' },
                        { data: 'status', name: 'status', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ]
                });

                initBulkDelete("{{ route('backend.category.bulk-destroy') }}");
            });
        })(jQuery);

        function showStatusChangeAlert(id) {
            let url = "{{ route('backend.category.status', ':id') }}";
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

        function resetDropify(defaultUrl = '') {
            $('#dropify-wrapper-container').html('<input type="file" name="image" id="category_image" class="dropify" data-height="200" accept="image/*" />');
            if (defaultUrl) {
                $('#category_image').attr('data-default-file', defaultUrl);
            }
            $('.dropify').dropify();
        }

        function updateColorPreview(hex) {
            if (hex) {
                $('#category_color_preview').css('background-color', hex);
            }
        }

        function openCreateModal() {
            $('#categoryForm').trigger("reset");
            $('#categoryForm').attr('action', "{{ route('backend.category.store') }}");
            $('#formMethod').val('POST');
            $('#category_id').val('');
            let defaultColor = '#FF8000';
            $('#category_color').val(defaultColor);
            $('#category_color_text').val(defaultColor);
            updateColorPreview(defaultColor);
            $('#categoryModalLabel').text('Create Category');
            $('#saveBtn').text('Create Category');
            resetDropify();
            $('#categoryModal').modal('show');
        }

        // Sync color picker input and text input
        $(document).on('input change', '#category_color', function() {
            let hex = $(this).val().toUpperCase();
            $('#category_color_text').val(hex);
            updateColorPreview(hex);
        });

        $(document).on('input paste keyup', '#category_color_text', function() {
            let val = $(this).val().trim();
            if (val && !val.startsWith('#')) {
                val = '#' + val;
            }
            val = val.toUpperCase();
            if (/^#[0-9A-F]{6}$/i.test(val)) {
                $('#category_color').val(val);
                updateColorPreview(val);
            }
        });

        $(document).on('blur', '#category_color_text', function() {
            let val = $(this).val().trim();
            if (val && !val.startsWith('#')) {
                val = '#' + val;
                $(this).val(val.toUpperCase());
            }
            let current = $(this).val();
            if (/^#[0-9A-F]{6}$/i.test(current)) {
                $('#category_color').val(current);
                updateColorPreview(current);
            }
        });

        // Copy color button
        $(document).on('click', '#copyColorBtn', function() {
            let colorCode = $('#category_color_text').val();
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(colorCode).then(() => {
                    toastr.success('Color code ' + colorCode + ' copied!');
                });
            } else {
                let tempInput = $('<input>');
                $('body').append(tempInput);
                tempInput.val(colorCode).select();
                document.execCommand('copy');
                tempInput.remove();
                toastr.success('Color code ' + colorCode + ' copied!');
            }
        });

        // Intercept Edit Button
        $(document).on('click', '.data-table .btn-soft-info', function(e) {
            e.preventDefault();
            let editUrl = $(this).attr('href');
            
            $.ajax({
                type: "GET",
                url: editUrl,
                success: function(response) {
                    if (response.success) {
                        let category = response.data;
                        let updateUrl = "{{ route('backend.category.update', ':id') }}".replace(':id', category.id);
                        
                        $('#categoryForm').attr('action', updateUrl);
                        $('#formMethod').val('PATCH');
                        $('#category_id').val(category.id);
                        $('#category_name').val(category.name);
                        let col = category.color || '#FF8000';
                        $('#category_color').val(col);
                        $('#category_color_text').val(col);
                        updateColorPreview(col);
                        $('#categoryModalLabel').text('Edit Category');
                        $('#saveBtn').text('Update Category');
                        
                        let imgUrl = category.image ? "{{ asset('') }}" + category.image : '';
                        resetDropify(imgUrl);
                        
                        $('#categoryModal').modal('show');
                    } else {
                        toastr.error("Failed to load category data.");
                    }
                },
                error: function() {
                    toastr.error("An error occurred while fetching category details.");
                }
            });
        });

        // Auto-reopen modal if validation errors exist (Laravel Redirect Back)
        @if ($errors->any())
            $(document).ready(function() {
                $('#category_name').val("{{ old('name') }}");
                let oldColor = "{{ old('color', '#FF8000') }}";
                $('#category_color').val(oldColor);
                $('#category_color_text').val(oldColor);
                updateColorPreview(oldColor);
                
                let oldMethod = "{{ old('_method') }}";
                let oldId = "{{ old('category_id') }}";
                if (oldMethod === 'PATCH' && oldId) {
                    let updateUrl = "{{ route('backend.category.update', ':id') }}".replace(':id', oldId);
                    $('#categoryForm').attr('action', updateUrl);
                    $('#formMethod').val('PATCH');
                    $('#category_id').val(oldId);
                    $('#categoryModalLabel').text('Edit Category');
                    $('#saveBtn').text('Update Category');
                } else {
                    $('#categoryForm').attr('action', "{{ route('backend.category.store') }}");
                    $('#formMethod').val('POST');
                    $('#category_id').val('');
                    $('#categoryModalLabel').text('Create Category');
                    $('#saveBtn').text('Create Category');
                }
                
                $('#categoryModal').modal('show');
            });
        @endif
    </script>
@endpush
