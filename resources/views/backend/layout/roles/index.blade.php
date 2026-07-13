@extends('backend.master')

@section('title', 'Role & Permission Management')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-tabs-custom nav-primary mb-3 shadow-sm bg-white rounded-3 p-1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active fw-bold border-0" data-bs-toggle="tab" href="#roles-tab" role="tab">
                        <i class="ri-shield-user-line me-1 align-bottom"></i> User Roles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold border-0" data-bs-toggle="tab" href="#permissions-tab" role="tab">
                        <i class="ri-key-2-line me-1 align-bottom"></i> Permissions Manager
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content text-muted">
                <!-- Roles Tab -->
                <div class="tab-pane active" id="roles-tab" role="tabpanel">
                    <div class="card shadow-sm border-0">
                        <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="card-title mb-0 fw-bold text-primary">User Roles</h5>
                                <p class="text-muted mb-0 fs-12">Manage access levels and assign permissions to roles</p>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('backend.role.create') }}" class="btn btn-primary btn-sm add-btn shadow-sm d-flex align-items-center">
                                    <i class="ri-add-line align-bottom me-1"></i> Add New Role
                                </a>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover mb-0 data-table custom-table w-100">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3" style="width: 60px;">ID</th>
                                            <th class="text-start" style="width: 200px;">Role Name</th>
                                            <th class="text-start">Permissions</th>
                                            <th class="text-center" style="width: 150px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions Tab -->
                <div class="tab-pane" id="permissions-tab" role="tabpanel">
                    <div class="row">
                        <!-- Add Permission form column -->
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-white py-3 border-0">
                                    <h5 class="card-title mb-0 fw-bold text-primary">Create Permission</h5>
                                    <p class="text-muted mb-0 fs-12">Add a new action privilege to the system</p>
                                </div>
                                <div class="card-body pt-0">
                                    <form id="addPermissionForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="permission_name" class="form-label fw-bold">Permission Name</label>
                                            <input type="text" class="form-control" id="permission_name" name="name" placeholder="e.g. products_manage" required>
                                            <div class="text-muted fs-11 mt-1">Use lowercase and underscores (e.g., <code>product_create</code>). It will automatically group by its prefix.</div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm w-100 shadow-sm">
                                            <i class="ri-save-line me-1 align-bottom"></i> Save Permission
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions list column -->
                        <div class="col-md-8">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white py-3 border-0">
                                    <h5 class="card-title mb-0 fw-bold text-primary">All Permissions</h5>
                                    <p class="text-muted mb-0 fs-12">List of customizable action privileges</p>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap table-hover mb-0 permission-table custom-table w-100">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-3" style="width: 60px;">ID</th>
                                                    <th class="text-start">Permission Name</th>
                                                    <th class="text-start" style="width: 180px;">Group / Module</th>
                                                    <th class="text-center" style="width: 140px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Permission Modal -->
    <div class="modal fade" id="previewPermissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold"><i class="ri-eye-line me-1"></i> Permission Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted text-uppercase fs-11">Permission Identifier</label>
                        <h4 class="fw-bold text-dark mb-0" id="previewPermissionName">-</h4>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted text-uppercase fs-11">Assigned to Roles</label>
                        <div id="previewPermissionRoles" class="d-flex flex-wrap gap-1 mt-1">
                            <!-- Roles listed here -->
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-bold text-muted text-uppercase fs-11">Users with this Access</label>
                        <div id="previewPermissionUsers" class="mt-2" style="max-height: 200px; overflow-y: auto;">
                            <!-- Users listed here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-2">
                    <button type="button" class="btn btn-ghost-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Permission Modal -->
    <div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold"><i class="ri-pencil-line me-1"></i> Edit Permission</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPermissionForm">
                    @csrf
                    <input type="hidden" id="editPermissionId" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="edit_permission_name" class="form-label fw-bold">Permission Identifier</label>
                            <input type="text" class="form-control" id="edit_permission_name" name="name" required placeholder="e.g. products_manage">
                            <small class="text-muted">Rename the key identifier inside the database tables.</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-2">
                        <button type="button" class="btn btn-ghost-secondary btn-sm px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 shadow">Update Permission</button>
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
            padding: 12px 15px;
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
        .btn-soft-success { background-color: rgba(10, 179, 156, 0.1); color: #0ab39c; border: none; }
        .btn-soft-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; border: none; }
        .btn-soft-info:hover { background-color: #299cdb; color: #fff; }
        .btn-soft-success:hover { background-color: #0ab39c; color: #fff; }
        .btn-soft-danger:hover { background-color: #f06548; color: #fff; }
        .badge-soft-info { background-color: rgba(41, 156, 219, 0.1); color: #299cdb; }
    </style>
@endpush

@push('scripts-bottom')
    <script>
        (function ($) {
            $(function () {
                // Initialize Roles Datatable
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: { details: true },
                    dom: '<"row mb-3 px-3 mt-3"<"col-md-6"l><"col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3 px-3 pb-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                    ajax: "{{ route('backend.role.index') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-3 text-muted fw-medium' },
                        { data: 'name', name: 'name', className: 'text-start fw-bold text-dark' },
                        { data: 'permissions', name: 'permissions', className: 'text-start' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ]
                });

                // Initialize Permissions Datatable
                $('.permission-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: { details: true },
                    dom: '<"row mb-3 px-3 mt-3"<"col-md-6"l><"col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3 px-3 pb-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                    ajax: "{{ route('backend.permission.index') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-3 text-muted fw-medium' },
                        { data: 'name', name: 'name', className: 'text-start fw-bold text-dark' },
                        { data: 'group', name: 'group', className: 'text-start text-capitalize text-muted fw-semibold' },
                        { 
                            data: null, 
                            orderable: false, 
                            searchable: false, 
                            className: 'text-center',
                            render: function(data, type, row) {
                                return `
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button type="button" onclick="previewPermission(${row.id})" class="btn btn-soft-success btn-sm" data-bs-toggle="tooltip" title="Preview">
                                            <i class="ri-eye-line fs-14"></i>
                                        </button>
                                        <button type="button" onclick="editPermission(${row.id}, '${row.name}')" class="btn btn-soft-info btn-sm" data-bs-toggle="tooltip" title="Edit">
                                            <i class="ri-pencil-line fs-14"></i>
                                        </button>
                                        <button type="button" onclick="deletePermission('${"{{ route('backend.permission.destroy', ':id') }}".replace(':id', row.id)}')" class="btn btn-soft-danger btn-sm" data-bs-toggle="tooltip" title="Delete">
                                            <i class="ri-delete-bin-line fs-14"></i>
                                        </button>
                                    </div>
                                `;
                            }
                        }
                    ]
                });

                // Add Permission Submit Ajax
                $('#addPermissionForm').on('submit', function(e) {
                    e.preventDefault();
                    let name = $('#permission_name').val();
                    $.ajax({
                        url: "{{ route('backend.permission.store') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: name
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#permission_name').val('');
                                $('.permission-table').DataTable().ajax.reload();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                toastr.error(errors.name ? errors.name[0] : 'Validation failed');
                            } else {
                                toastr.error('Something went wrong!');
                            }
                        }
                    });
                });

                // Edit Permission Submit Ajax
                $('#editPermissionForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#editPermissionId').val();
                    let name = $('#edit_permission_name').val();
                    $.ajax({
                        url: "{{ route('backend.permission.update', ':id') }}".replace(':id', id),
                        type: "PUT",
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: name
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#editPermissionModal').modal('hide');
                                $('.permission-table').DataTable().ajax.reload();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                toastr.error(errors.name ? errors.name[0] : 'Validation failed');
                            } else {
                                toastr.error('Something went wrong!');
                            }
                        }
                    });
                });
            });
        })(jQuery);

        function deleteData(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "All users assigned to this role will lose their permissions!",
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

        function deletePermission(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Any roles containing this permission will lose it!",
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
                                $('.permission-table').DataTable().ajax.reload();
                                toastr.success(response.message || "Deleted successfully");
                            } else {
                                toastr.error(response.message || "Failed to delete");
                            }
                        }
                    });
                }
            })
        }

        function previewPermission(id) {
            $.ajax({
                url: "{{ route('backend.permission.show', ':id') }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        $('#previewPermissionName').text(data.name);
                        
                        // Render Roles
                        let rolesHtml = '';
                        if (data.roles && data.roles.length > 0) {
                            data.roles.forEach(role => {
                                rolesHtml += `<span class="badge bg-soft-primary text-primary me-1 fs-12 text-capitalize">${role.replace('_', ' ')}</span>`;
                            });
                        } else {
                            rolesHtml = '<span class="text-muted fs-12">Not assigned to any roles.</span>';
                        }
                        $('#previewPermissionRoles').html(rolesHtml);
                        
                        // Render Users
                        let usersHtml = '';
                        if (data.users && data.users.length > 0) {
                            data.users.forEach(user => {
                                usersHtml += `
                                    <div class="d-flex align-items-center mb-3 p-2 bg-light rounded-3">
                                        <div class="avatar-xs me-3">
                                            <div class="avatar-title rounded-circle bg-soft-success text-success fs-12 text-uppercase fw-bold">
                                                ${user.name.charAt(0)}
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fs-13 fw-bold">${user.name}</h6>
                                            <small class="text-muted fs-11">${user.email}</small>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            usersHtml = '<span class="text-muted fs-12">No users currently have this permission.</span>';
                        }
                        $('#previewPermissionUsers').html(usersHtml);
                        
                        $('#previewPermissionModal').modal('show');
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }

        function editPermission(id, currentName) {
            $('#editPermissionId').val(id);
            $('#edit_permission_name').val(currentName);
            $('#editPermissionModal').modal('show');
        }
    </script>
@endpush