@extends('backend.master')
@section('title', 'FAQ Categories')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" id="faqCategoryList">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">FAQ Categories</h5>
                        <p class="text-muted mb-0 fs-12">Manage FAQ section categories</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm d-none align-items-center" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> Bulk Delete
                        </button>
                        <button type="button" class="btn btn-primary btn-sm shadow-sm d-flex align-items-center" onclick="openCreateModal()">
                            <i class="ri-add-line align-bottom me-1"></i> Add Category
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
                                    <th class="text-start">Category Name</th>
                                    <th class="text-center" style="width: 100px;">Priority</th>
                                    <th class="text-center" style="width: 100px;">FAQs</th>
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

    {{-- Create / Edit Modal --}}
    <div class="modal fade" id="faqCategoryModal" tabindex="-1" aria-labelledby="faqCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="faqCategoryForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="faqCategoryModalLabel">Create FAQ Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="cat_name">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="cat_name" class="form-control" placeholder="e.g. Orders & Shipping" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" for="cat_priority">Priority <span class="text-danger">*</span></label>
                            <input type="number" name="priority" id="cat_priority" class="form-control" value="1" min="1" required>
                            <small class="text-muted">Lower number = shown first.</small>
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
        .custom-table tbody td { padding: 10px 15px; font-size: 13.5px; }
        .btn-soft-info  { background-color: rgba(41,156,219,.1); color: #299cdb; border: none; }
        .btn-soft-danger{ background-color: rgba(240,101,72,.1); color: #f06548; border: none; }
        .btn-soft-info:hover  { background-color: #299cdb; color: #fff; }
        .btn-soft-danger:hover{ background-color: #f06548; color: #fff; }
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
                    ajax: "{{ route('backend.faq-category.index') }}",
                    columns: [
                        { data: 'checkbox',   name: 'checkbox',   orderable: false, searchable: false, className: 'text-center' },
                        { data: 'DT_RowIndex',name: 'DT_RowIndex',orderable: false, searchable: false, className: 'ps-3 text-muted fw-medium' },
                        { data: 'name',       name: 'name',       className: 'text-start fw-medium' },
                        { data: 'priority',   name: 'priority',   className: 'text-center' },
                        { data: 'faq_count',  name: 'faq_count',  orderable: false, searchable: false, className: 'text-center' },
                        { data: 'status',     name: 'status',     orderable: false, searchable: false, className: 'text-center' },
                        { data: 'action',     name: 'action',     orderable: false, searchable: false, className: 'text-center' },
                    ]
                });

                initBulkDelete("{{ route('backend.faq-category.bulk-destroy') }}");
            });
        })(jQuery);

        function openCreateModal() {
            $('#faqCategoryForm').trigger('reset');
            $('#faqCategoryForm').attr('action', "{{ route('backend.faq-category.store') }}");
            $('#formMethod').val('POST');
            $('#faqCategoryModalLabel').text('Create FAQ Category');
            $('#saveBtn').text('Create Category');
            $('#cat_priority').val(1);
            $('#faqCategoryModal').modal('show');
        }

        function editFaqCategory(id, name, priority) {
            let url = "{{ route('backend.faq-category.update', ':id') }}".replace(':id', id);
            $('#faqCategoryForm').attr('action', url);
            $('#formMethod').val('PATCH');
            $('#cat_name').val(name);
            $('#cat_priority').val(priority);
            $('#faqCategoryModalLabel').text('Edit FAQ Category');
            $('#saveBtn').text('Update Category');
            $('#faqCategoryModal').modal('show');
        }

        function statusFaqCategory(id) {
            let url = "{{ route('backend.faq-category.status', ':id') }}".replace(':id', id);
            $.ajax({
                type: 'POST',
                url: url,
                data: { _token: "{{ csrf_token() }}" },
                success: function (res) {
                    if (res.success) {
                        $('.data-table').DataTable().ajax.reload(null, false);
                        toastr.success(res.message);
                    }
                }
            });
        }

        function deleteData(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the category. FAQs linked to it will become uncategorised.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor:  '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (res) {
                            if (res.success) {
                                $('.data-table').DataTable().ajax.reload(null, false);
                                toastr.success(res.message);
                            } else {
                                toastr.error(res.message);
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
