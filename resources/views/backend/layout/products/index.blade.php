@extends('backend.master')
@section('title', 'All Products')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-lg border-0" id="productList">
                <div class="card-header border-0 bg-soft-primary py-3">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1 fw-bold text-primary">Product Management</h5>
                        <div class="flex-shrink-0">
                            <a class="btn btn-primary add-btn shadow-sm" href="{{route('backend.product.create')}}">
                                <i class="ri-add-line align-bottom me-1"></i> Add Product
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive table-card mb-4">
                        <table class="table align-middle table-nowrap table-hover mb-0 data-table">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        <div class="form-check text-center">
                                            <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                        </div>
                                    </th>
                                    <th class="border-bottom-0">ID</th>
                                    <th class="border-bottom-0">Image</th>
                                    <th class="border-bottom-0 text-start">Name & Category</th>
                                    <th class="border-bottom-0">Price</th>
                                    <th class="border-bottom-0">Status</th>
                                    <th class="border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles-top')
    <style>
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ced4da;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            outline: none;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #ced4da;
            padding: 0.375rem 1.75rem 0.375rem 0.75rem;
            border-radius: 0.25rem;
        }
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
                    dom: '<"row mb-3"<"col-md-6"l><"col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                    ajax: "{{ route('backend.product.index') }}",
                    columns: [
                        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'image', name: 'image', orderable: false, searchable: false },
                        { data: 'name', name: 'name', className: 'text-start' },
                        { data: 'price', name: 'price' },
                        { data: 'status', name: 'status', orderable: false, searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });

                $('#checkAll').on('change', function() {
                    $('.form-check-input[name="checkAll"]').prop('checked', $(this).prop('checked'));
                });
            });
        })(jQuery);

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
