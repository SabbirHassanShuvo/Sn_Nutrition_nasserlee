<?php $__env->startSection('title', 'System Administrators'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">System Admins</h5>
                        <p class="text-muted mb-0 fs-12">Manage administrative staff and their access levels</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm shadow-sm d-none align-items-center" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> Bulk Delete
                        </button>
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
                                    <th style="width: 40px; text-align: center;"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                    <th class="ps-3" style="width: 60px;">ID</th>
                                    <th class="text-start">Name</th>
                                    <th class="text-start">Email</th>
                                    <th class="text-start">Roles</th>
                                    <th class="text-center" style="width: 120px;">Status</th>
                                    <th class="text-center" style="width: 180px;">Actions</th>
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
                    <?php echo csrf_field(); ?>
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
                    </div>
                    <div class="modal-footer bg-light border-0 py-2">
                        <button type="button" class="btn btn-ghost-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 shadow" id="saveBtn">Save Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Manage Access & Overrides Modal -->
    <div class="modal fade" id="manageAccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold"><i class="ri-shield-keyhole-line me-1"></i> Manage Staff Access & Overrides</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="manageAccessForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="accessUserId" name="userId">
                    <div class="modal-body p-4">
                        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title rounded-circle bg-soft-primary text-primary fs-18 text-uppercase fw-bold" id="accessUserLetter">
                                    A
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" id="accessUserName">Staff Name</h5>
                                <span class="text-muted fs-12" id="accessUserEmail">staff@email.com</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="accessUserRole" class="form-label fw-bold">Assigned Primary Role</label>
                            <select class="form-select" id="accessUserRole" name="roles[]">
                                <!-- Populated dynamically -->
                            </select>
                            <small class="text-muted fs-11 mt-1 d-block">Selecting a role sets default permissions. You can customize overrides below.</small>
                        </div>

                        <div>
                            <label class="form-label fw-bold mb-3 d-block">Granular Permissions & Direct Overrides</label>
                            <div id="permissionsAccessGrid" style="max-height: 350px; overflow-y: auto; padding-right: 5px;">
                                <!-- Rendered dynamically -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-2">
                        <button type="button" class="btn btn-ghost-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 shadow">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles-top'); ?>
    <style>
        .custom-table thead th { font-size: 11px; text-transform: uppercase; font-weight: 700; padding: 12px 15px; letter-spacing: 0.5px; }
        .custom-table tbody td { padding: 12px 15px; font-size: 13.5px; }
        .dataTables_wrapper .dataTables_filter input { border: 1px solid #e9ebec; padding: 0.4rem 0.8rem; border-radius: 6px; background-color: #f3f6f9; }
        .btn-soft-info { background-color: rgba(41, 156, 219, 0.1); color: #299cdb; border: none; }
        .btn-soft-success { background-color: rgba(10, 179, 156, 0.1); color: #0ab39c; border: none; }
        .btn-soft-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; border: none; }
        .btn-soft-info:hover { background-color: #299cdb; color: #fff; }
        .btn-soft-success:hover { background-color: #0ab39c; color: #fff; }
        .btn-soft-danger:hover { background-color: #f06548; color: #fff; }
        .bg-soft-primary { background-color: rgba(64, 81, 137, 0.1); }
        .bg-soft-info { background-color: rgba(41, 156, 219, 0.1); }
        .bg-soft-warning { background-color: rgba(240, 173, 78, 0.1); }
        .badge-status-placeholder { vertical-align: middle; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
    <script>
        let rolePermissionsMap = {};
        let loadedDirectPermissions = [];

        (function ($) {
            $(function () {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: { details: true },
                    dom: '<"row mb-3 px-3 mt-3"<"col-md-6"l><"col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3 px-3 pb-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                    ajax: "<?php echo e(route('backend.system-user.index')); ?>",
                    columns: [
                        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center' },
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
                    let url = id ? "<?php echo e(route('backend.system-user.update', ':id')); ?>".replace(':id', id) : "<?php echo e(route('backend.system-user.store')); ?>";
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

                // Role selection change triggers checkbox changes
                $('#accessUserRole').on('change', function() {
                    updatePermissionStates();
                });

                // Manage Access Form Submit
                $('#manageAccessForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#accessUserId').val();
                    let roles = [$('#accessUserRole').val()];
                    let permissions = [];
                    
                    $('.permission-access-cb:checked').each(function() {
                        permissions.push($(this).val());
                    });

                    $.ajax({
                        url: "<?php echo e(route('backend.system-user.permissions.sync', ':id')); ?>".replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>",
                            roles: roles,
                            permissions: permissions
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#manageAccessModal').modal('hide');
                                $('.data-table').DataTable().ajax.reload();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to update permissions.');
                        }
                    });
                });
                
                initBulkDelete("<?php echo e(route('backend.system-user.bulk-destroy')); ?>");
            });
        })(jQuery);

        function openAddModal() {
            $('#modalTitle').text('Add New Admin');
            $('#adminUserForm')[0].reset();
            $('#userId').val('');
            $('#passwordHelp').hide();
            $('#adminUserModal').modal('show');
        }

        function editSystemUser(id) {
            let url = "<?php echo e(route('backend.system-user.edit', ':id')); ?>";
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
                        $('#adminUserModal').modal('show');
                    }
                }
            });
        }

        function manageAccess(id) {
            let url = "<?php echo e(route('backend.system-user.permissions', ':id')); ?>";
            $.ajax({
                type: "GET",
                url: url.replace(':id', id),
                success: function (response) {
                    if (response.success) {
                        let user = response.user;
                        $('#accessUserId').val(user.id);
                        $('#accessUserName').text(user.name);
                        $('#accessUserEmail').text(user.email);
                        $('#accessUserLetter').text(user.name.charAt(0).toUpperCase());
                        
                        rolePermissionsMap = response.role_permissions_map;
                        
                        // Clear direct permissions list
                        loadedDirectPermissions = [];
                        
                        // Populate Roles select option
                        let optionsHtml = '';
                        response.roles.forEach(role => {
                            let selected = user.roles.includes(role) ? 'selected' : '';
                            optionsHtml += `<option value="${role}" ${selected}>${role.replace(/_/g, ' ').toUpperCase()}</option>`;
                        });
                        $('#accessUserRole').html(optionsHtml);
                        
                        // Group permissions by prefix
                        let grouped = {};
                        response.permissions.forEach(perm => {
                            let grp = perm.group;
                            if (!grouped[grp]) grouped[grp] = [];
                            grouped[grp].push(perm);
                            
                            if (perm.is_direct) {
                                loadedDirectPermissions.push(perm.name);
                            }
                        });
                        
                        // Build Checkbox grid grouped by category
                        let gridHtml = '<div class="row">';
                        for (let grp in grouped) {
                            gridHtml += `
                                <div class="col-md-6 mb-3">
                                    <div class="card border shadow-none bg-light h-100 mb-0">
                                        <div class="card-header bg-soft-primary border-bottom py-2">
                                            <h6 class="mb-0 fs-12 fw-bold text-uppercase text-primary">${grp.replace(/_/g, ' ')} Management</h6>
                                        </div>
                                        <div class="card-body py-3">
                            `;
                            
                            grouped[grp].forEach(perm => {
                                gridHtml += `
                                    <div class="form-check mb-2 d-flex align-items-center">
                                        <input class="form-check-input permission-access-cb me-2" 
                                               type="checkbox" 
                                               name="permissions[]" 
                                               value="${perm.name}" 
                                               id="access_perm_${perm.id}" 
                                               data-name="${perm.name}">
                                        <label class="form-check-label fs-13 mb-0" for="access_perm_${perm.id}">
                                            ${perm.name.replace(/_/g, ' ').replace(/(^\w|\s\w)/g, m => m.toUpperCase())}
                                        </label>
                                        <span class="badge-status-placeholder"></span>
                                    </div>
                                `;
                            });
                            
                            gridHtml += `
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                        gridHtml += '</div>';
                        $('#permissionsAccessGrid').html(gridHtml);
                        
                        // Set checkboxes based on selected role
                        updatePermissionStates();
                        
                        $('#manageAccessModal').modal('show');
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }

        function updatePermissionStates() {
            let selectedRole = $('#accessUserRole').val();
            let inherited = rolePermissionsMap[selectedRole] || [];
            
            $('.permission-access-cb').each(function() {
                let cb = $(this);
                let name = cb.data('name');
                let placeholder = cb.siblings('.badge-status-placeholder');
                
                if (inherited.includes(name)) {
                    cb.prop('checked', true);
                    cb.prop('disabled', true);
                    placeholder.html('<span class="badge bg-soft-info text-info ms-2 fs-10 text-uppercase">Inherited</span>');
                } else {
                    cb.prop('disabled', false);
                    // Check if it's currently a direct override
                    let isDirect = loadedDirectPermissions.includes(name);
                    cb.prop('checked', isDirect);
                    if (isDirect) {
                        placeholder.html('<span class="badge bg-soft-warning text-warning ms-2 fs-10 text-uppercase">Override</span>');
                    } else {
                        placeholder.html('');
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
                    let url = "<?php echo e(route('backend.system-user.status', ':id')); ?>";
                    $.ajax({
                        type: "POST",
                        url: url.replace(':id', id),
                        data: { _token: "<?php echo e(csrf_token()); ?>" },
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
                    let url = "<?php echo e(route('backend.system-user.destroy', ':id')); ?>";
                    $.ajax({
                        url: url.replace(':id', id),
                        type: 'DELETE',
                        data: { _token: "<?php echo e(csrf_token()); ?>" },
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Sandip\Herd\Sn_Nutrition_nasserlee\resources\views/backend/layout/users/system_users/index.blade.php ENDPATH**/ ?>