<?php $__env->startSection('title', 'Role & Permission Management'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <!-- Nav tabs -->
            <ul class="nav nav-pills nav-custom nav-primary mb-3 shadow-sm bg-white rounded-3 p-2" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active fw-bold border-0 px-4 py-2" data-bs-toggle="tab" href="#roles-tab" role="tab">
                        <i class="ri-shield-user-line me-2 align-bottom"></i> User Roles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold border-0 px-4 py-2" data-bs-toggle="tab" href="#permissions-tab" role="tab">
                        <i class="ri-key-2-line me-2 align-bottom"></i> Permissions Manager
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content text-muted">
                <!-- Roles Tab -->
                <div class="tab-pane active" id="roles-tab" role="tabpanel">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="card-title mb-0 fw-bold text-primary">User Roles</h5>
                                <p class="text-muted mb-0 fs-12">Manage access levels and assign permissions to roles</p>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="<?php echo e(route('backend.role.create')); ?>" class="btn btn-primary btn-sm add-btn shadow-sm d-flex align-items-center px-3 py-2">
                                    <i class="ri-add-line align-bottom me-1 fs-16"></i> Add New Role
                                </a>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-hover mb-0 data-table custom-table w-100">
                                    <thead class="table-light text-uppercase fs-11 fw-bold text-muted">
                                        <tr>
                                            <th class="ps-4 text-center" style="width: 70px;">ID</th>
                                            <th class="text-start" style="width: 220px;">Role Name</th>
                                            <th class="text-start">Permissions</th>
                                            <th class="text-center" style="width: 140px;">Actions</th>
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
                            <div class="card shadow-sm border-0 rounded-3 mb-4">
                                <div class="card-header bg-white py-3 border-0">
                                    <h5 class="card-title mb-0 fw-bold text-primary">Create Permission</h5>
                                    <p class="text-muted mb-0 fs-12">Add a new action privilege to the system</p>
                                </div>
                                <div class="card-body pt-0">
                                    <form id="addPermissionForm">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-3">
                                            <label for="permission_name" class="form-label fw-bold">Permission Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="ri-key-line text-muted"></i></span>
                                                <input type="text" class="form-control border-start-0" id="permission_name" name="name" placeholder="e.g. products_manage" required>
                                            </div>
                                            <div class="text-muted fs-11 mt-2">Use lowercase with underscores (e.g. <code>product_create</code>). Prefix is used as module group.</div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm w-100 shadow-sm py-2">
                                            <i class="ri-save-line me-1 align-bottom"></i> Save Permission
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions list column -->
                        <div class="col-md-8">
                            <div class="card shadow-sm border-0 rounded-3">
                                <div class="card-header bg-white py-3 border-0">
                                    <h5 class="card-title mb-0 fw-bold text-primary">All Permissions</h5>
                                    <p class="text-muted mb-0 fs-12">List of customizable action privileges</p>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-hover mb-0 permission-table custom-table w-100">
                                            <thead class="table-light text-uppercase fs-11 fw-bold text-muted">
                                                <tr>
                                                    <th class="ps-4 text-center" style="width: 70px;">ID</th>
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
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold"><i class="ri-eye-line me-2"></i> Permission Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <label class="form-label fw-bold text-muted text-uppercase fs-11 mb-1">Permission Identifier</label>
                        <h4 class="fw-bold text-dark mb-0 font-monospace" id="previewPermissionName">-</h4>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted text-uppercase fs-11 mb-2">Assigned to Roles</label>
                        <div id="previewPermissionRoles" class="d-flex flex-wrap gap-1">
                            <!-- Roles listed here -->
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-bold text-muted text-uppercase fs-11 mb-2">Users with this Access</label>
                        <div id="previewPermissionUsers" class="mt-1" style="max-height: 220px; overflow-y: auto;">
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
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold"><i class="ri-pencil-line me-2"></i> Edit Permission</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPermissionForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="editPermissionId" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="edit_permission_name" class="form-label fw-bold">Permission Identifier</label>
                            <input type="text" class="form-control" id="edit_permission_name" name="name" required placeholder="e.g. products_manage">
                            <small class="text-muted fs-11 mt-1 d-block">Rename the key identifier inside the permissions table.</small>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles-top'); ?>
    <style>
        .nav-pills.nav-custom .nav-link {
            color: #495057;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .nav-pills.nav-custom .nav-link.active {
            background-color: var(--vz-primary, #405189);
            color: #fff;
            box-shadow: 0 4px 10px rgba(64, 81, 137, 0.25);
        }
        .custom-table thead th {
            font-size: 11.5px;
            text-transform: uppercase;
            font-weight: 700;
            padding: 14px 16px;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #eff2f7;
        }
        .custom-table tbody td {
            padding: 14px 16px;
            font-size: 13.5px;
            vertical-align: middle;
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ced4da;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            background-color: #fff;
            outline: none;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 0.35rem 1.8rem 0.35rem 0.8rem;
            background-color: #fff;
        }
        .btn-soft-info { background-color: rgba(41, 156, 219, 0.1); color: #299cdb; border: none; }
        .btn-soft-success { background-color: rgba(10, 179, 156, 0.1); color: #0ab39c; border: none; }
        .btn-soft-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; border: none; }
        .btn-soft-info:hover { background-color: #299cdb; color: #fff; }
        .btn-soft-success:hover { background-color: #0ab39c; color: #fff; }
        .btn-soft-danger:hover { background-color: #f06548; color: #fff; }
        .bg-soft-info { background-color: rgba(41, 156, 219, 0.1); color: #299cdb; }
        .bg-soft-primary { background-color: rgba(64, 81, 137, 0.1); color: #405189; }
        .bg-soft-secondary { background-color: #f3f6f9; color: #878a99; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
    <script>
        (function ($) {
            $(function () {
                // Initialize Roles Datatable
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    autoWidth: false,
                    dom: '<"row mb-3 px-3 mt-3"<"col-md-6"l><"col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3 px-3 pb-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                    ajax: "<?php echo e(route('backend.role.index')); ?>",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-muted fw-bold' },
                        { data: 'name', name: 'name', className: 'text-start' },
                        { data: 'permissions', name: 'permissions', className: 'text-start' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ]
                });

                // Initialize Permissions Datatable
                $('.permission-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    autoWidth: false,
                    dom: '<"row mb-3 px-3 mt-3"<"col-md-6"l><"col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3 px-3 pb-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                    ajax: "<?php echo e(route('backend.permission.index')); ?>",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-muted fw-bold' },
                        { data: 'name', name: 'name', className: 'text-start' },
                        { data: 'group', name: 'group', className: 'text-start' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ]
                });

                // Add Permission Submit Ajax
                $('#addPermissionForm').on('submit', function(e) {
                    e.preventDefault();
                    let name = $('#permission_name').val();
                    $.ajax({
                        url: "<?php echo e(route('backend.permission.store')); ?>",
                        type: "POST",
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>",
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
                        url: "<?php echo e(route('backend.permission.update', ':id')); ?>".replace(':id', id),
                        type: "PUT",
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>",
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
                        data: { _token: "<?php echo e(csrf_token()); ?>" },
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
                        data: { _token: "<?php echo e(csrf_token()); ?>" },
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
                url: "<?php echo e(route('backend.permission.show', ':id')); ?>".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        $('#previewPermissionName').text(data.name);
                        
                        // Render Roles
                        let rolesHtml = '';
                        if (data.roles && data.roles.length > 0) {
                            data.roles.forEach(role => {
                                rolesHtml += `<span class="badge bg-soft-primary text-primary me-1 fs-12 text-capitalize px-2 py-1">${role.replace('_', ' ')}</span>`;
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
                                    <div class="d-flex align-items-center mb-2 p-2 bg-light rounded-3 border">
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Sandip\Herd\Sn_Nutrition_nasserlee\resources\views/backend/layout/roles/index.blade.php ENDPATH**/ ?>