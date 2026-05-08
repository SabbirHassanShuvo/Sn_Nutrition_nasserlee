@extends('backend.master')

@section('title', 'System Administrators')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">System Admins</h5>
                        <p class="text-muted mb-0 fs-12">Manage administrative staff and their roles</p>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-primary btn-sm shadow-sm" onclick="openAddModal()">
                            <i class="ri-add-line align-bottom me-1"></i> Add New Admin
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0 data-table custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 60px;">ID</th>
                                    <th class="text-start">Name</th>
                                    <th class="text-start">Email</th>
                                    <th class="text-start">Roles</th>
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

    <!-- Admin User Modal -->
    <div class="modal fade" id="adminUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold" id="modalTitle">Add New Admin</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="adminUserForm">
                    @csrf
                    <input type="hidden" id="userId" name="userId">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                            <small class="text-muted" id="passwordHelp">Leave blank to keep existing password when editing.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Assign Roles</label>
                            <div class="row g-2">
                                @foreach($roles as $role)
                                    <div class="col-6">
                                        <div class="form-check card-radio p-0">
                                            <input type="checkbox" name="role[]" value="{{ $role }}" id="role_{{ $role }}" class="form-check-input role-checkbox">
                                            <label class="form-check-label p-2 border rounded d-block" for="role_{{ $role }}">
                                                {{ ucwords(str_replace('_', ' ', $role)) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-2">
                        <button type="button" class="btn btn-ghost-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 shadow" id="saveBtn">Save Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles-top')
    <style>
        .custom-table thead th { font-size: 11px; text-transform: uppercase; font-weight: 700; padding: 12px 15px; letter-spacing: 0.5px; }
        .custom-table tbody td { padding: 12px 15px; font-size: 13.5px; }
        .dataTables_wrapper .dataTables_filter input { border: 1px solid #e9ebec; padding: 0.4rem 0.8rem; border-radius: 6px; background-color: #f3f6f9; }
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
                    ajax: "{{ route('backend.system-user.index') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-3 text-muted fw-medium' },
                        { data: 'name', name: 'name', className: 'text-start fw-bold' },
                        { data: 'email', name: 'email', className: 'text-start' },
                        { data: 'roles', name: 'roles', className: 'text-start' },
                        { data: 'status', name: 'status', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ]
                });

                $('#adminUserForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#userId').val();
                    let url = id ? "{{ route('backend.system-user.update', ':id') }}".replace(':id', id) : "{{ route('backend.system-user.store') }}";
                    let method = id ? 'PUT' : 'POST';

                    $.ajax({
                        url: url,
                        type: method,
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.success) {
                                $('#adminUserModal').modal('hide');
                                $('.data-table').DataTable().ajax.reload();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        }
                    });
                });
            });
        })(jQuery);

        function openAddModal() {
            $('#modalTitle').text('Add New Admin');
            $('#adminUserForm')[0].reset();
            $('#userId').val('');
            $('#passwordHelp').hide();
            $('.role-checkbox').prop('checked', false);
            $('#adminUserModal').modal('show');
        }

        function editSystemUser(id) {
            let url = "{{ route('backend.system-user.edit', ':id') }}";
            $.ajax({
                type: "GET",
                url: url.replace(':id', id),
                success: function (response) {
                    if (response.success) {
                        let user = response.data;
                        $('#modalTitle').text('Edit Admin: ' + user.name);
                        $('#userId').val(user.id);
                        $('#name').val(user.name);
                        $('#email').val(user.email);
                        $('#password').val('');
                        $('#passwordHelp').show();
                        
                        $('.role-checkbox').prop('checked', false);
                        if (response.roles) {
                            response.roles.forEach(role => {
                                $('#role_' + role).prop('checked', true);
                            });
                        }
                        $('#adminUserModal').modal('show');
                    }
                }
            });
        }

        function showStatusChangeAlert(id) {
            Swal.fire({
                title: 'Change status?',
                text: "Confirm status toggle for this administrator.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, toggle!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('backend.system-user.status', ':id') }}";
                    $.ajax({
                        type: "POST",
                        url: url.replace(':id', id),
                        data: { _token: "{{csrf_token()}}" },
                        success: function (response) {
                            $('.data-table').DataTable().ajax.reload();
                            toastr.success(response.message);
                        }
                    });
                }
            });
        }

        function showDeleteConfirm(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Admin will be permanently removed!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('backend.system-user.destroy', ':id') }}";
                    $.ajax({
                        url: url.replace(':id', id),
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (response) {
                            if (response.status === 'success') {
                                $('.data-table').DataTable().ajax.reload();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        }
                    });
                }
            })
        }
    </script>
@endpush
