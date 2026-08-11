<?php $__env->startSection('title', 'Application Users'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" id="userList">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">All Users</h5>
                        <p class="text-muted mb-0 fs-12">Manage customers and health professionals</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <button type="button" class="btn btn-soft-danger btn-sm d-none" id="bulkDeleteBtn" onclick="bulkDelete()">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> Delete Selected
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0 data-table custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 40px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th class="text-start">User</th>
                                    <th class="text-start">Email</th>
                                    <th class="text-center" style="width: 150px;">Role</th>
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

    <!-- User Details Modal -->
    <div class="modal fade" id="userViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold">User Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <img id="userAvatar" src="" class="rounded-circle img-thumbnail shadow-sm mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <h4 class="fw-bold mb-1" id="userName">User Name</h4>
                    <p class="text-muted mb-3" id="userEmail">user@example.com</p>
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <p class="text-muted fs-11 text-uppercase mb-1">Account Type</p>
                                <span class="badge bg-soft-info text-info text-uppercase" id="userRole">ROLE</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <p class="text-muted fs-11 text-uppercase mb-1">Joined On</p>
                                <span class="fw-medium fs-13" id="userJoined">Date</span>
                            </div>
                        </div>
                    </div>

                    <div id="partnerSection" class="mt-4 text-start d-none">
                        <h6 class="fw-bold fs-13 text-uppercase mb-3 border-bottom pb-1 text-primary">Professional Profile</h6>
                        <div class="mb-2">
                            <label class="text-muted fs-11 text-uppercase d-block mb-0">Specialties</label>
                            <p id="userSpecialties" class="mb-0 fs-13 fw-medium">-</p>
                        </div>
                        <div>
                            <label class="text-muted fs-11 text-uppercase d-block mb-0">Bio</label>
                            <p id="userBio" class="mb-0 fs-13 text-muted">-</p>
                        </div>
                    </div>

                    <div id="suspensionInfo" class="mt-4 text-start d-none">
                        <h6 class="fw-bold fs-13 text-uppercase mb-2 text-danger">Suspension Note</h6>
                        <p id="suspensionReason" class="p-2 bg-soft-danger text-danger rounded fs-13 mb-0">-</p>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-2">
                    <button type="button" class="btn btn-ghost-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles-top'); ?>
    <style>
        .custom-table thead th { 
            font-size: 11px; 
            text-transform: uppercase; 
            font-weight: 700; 
            padding: 12px 15px; 
            letter-spacing: 0.5px; 
            vertical-align: middle;
            line-height: 1.5;
        }
        .custom-table tbody td { 
            padding: 12px 15px; 
            font-size: 14px; 
        }
        .dataTables_wrapper .dataTables_filter input { 
            border: 1px solid #e9ebec; 
            padding: 0.4rem 0.8rem; 
            border-radius: 6px; 
            background-color: #f3f6f9; 
        }
        .btn-soft-primary { background-color: rgba(64, 81, 137, 0.1); color: #405189; border: none; }
        .btn-soft-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; border: none; }
        .avatar-xs { width: 35px; height: 35px; object-fit: cover; }
        .user-info-box { min-width: 200px; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
    <script>
        (function ($) {
            $(function () {
                let table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: { details: true },
                    dom: '<"row mb-3 px-3 mt-3"<"col-md-6"l><"col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3 px-3 pb-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                    ajax: "<?php echo e(route('backend.app-user.index')); ?>",
                    columns: [
                        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'ps-3' },
                        { data: 'user_info', name: 'name', className: 'text-start' },
                        { data: 'email', name: 'email', className: 'text-start' },
                        { data: 'role_badge', name: 'role', className: 'text-center' },
                        { data: 'status', name: 'status', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ]
                });

                // Select All logic
                $('#selectAll').on('change', function() {
                    $('.user-checkbox').prop('checked', this.checked);
                    toggleBulkDeleteBtn();
                });

                $(document).on('change', '.user-checkbox', function() {
                    toggleBulkDeleteBtn();
                });

                function toggleBulkDeleteBtn() {
                    let checkedCount = $('.user-checkbox:checked').length;
                    if (checkedCount > 0) {
                        $('#bulkDeleteBtn').removeClass('d-none');
                    } else {
                        $('#bulkDeleteBtn').addClass('d-none');
                        $('#selectAll').prop('checked', false);
                    }
                }
            });
        })(jQuery);

        function viewUser(id) {
            let url = "<?php echo e(route('backend.app-user.show', ':id')); ?>";
            $.ajax({
                type: "GET",
                url: url.replace(':id', id),
                success: function (response) {
                    if (response.success) {
                        let user = response.data;
                        $('#userName').text(user.name);
                        $('#userEmail').text(user.email);
                        $('#userRole').text(user.role ? user.role.replace('_', ' ') : 'user');
                        $('#userJoined').text(new Date(user.created_at).toLocaleDateString());
                        
                        let avatar = (user.profile && user.profile.avatar) ? "<?php echo e(asset('')); ?>" + user.profile.avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name);
                        $('#userAvatar').attr('src', avatar);

                        let partnerProfile = user.partner_profile || user.partnerProfile;
                        if (user.role === 'health_professional' && partnerProfile) {
                            $('#partnerSection').removeClass('d-none');
                            $('#userBio').text(partnerProfile.bio || 'No bio provided');
                            let specialties = partnerProfile.specialties ? 
                                (Array.isArray(partnerProfile.specialties) ? partnerProfile.specialties.join(', ') : partnerProfile.specialties) : 
                                'None';
                            $('#userSpecialties').text(specialties);
                        } else {
                            $('#partnerSection').addClass('d-none');
                        }

                        if (!user.status && user.suspension_reason) {
                            $('#suspensionInfo').removeClass('d-none');
                            $('#suspensionReason').text(user.suspension_reason);
                        } else {
                            $('#suspensionInfo').addClass('d-none');
                        }

                        $('#userViewModal').modal('show');
                    }
                }
            });
        }

        function showStatusChangeAlert(id) {
            Swal.fire({
                title: 'Change Account Status?',
                text: "Please provide a reason for this status change. An email notification will be sent to the user.",
                input: 'textarea',
                inputPlaceholder: 'Type your reason here...',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to provide a reason!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "<?php echo e(route('backend.app-user.status', ':id')); ?>";
                    $.ajax({
                        type: "POST",
                        url: url.replace(':id', id),
                        data: { 
                            id: id, 
                            reason: result.value,
                            _token: "<?php echo e(csrf_token()); ?>" 
                        },
                        success: function (response) {
                            if (response.success) {
                                $('.data-table').DataTable().ajax.reload();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        }
                    });
                }
            });
        }

        function bulkDelete() {
            let ids = [];
            $('.user-checkbox:checked').each(function() {
                ids.push($(this).val());
            });

            Swal.fire({
                title: 'Delete selected users?',
                text: "This will permanently remove " + ids.length + " accounts!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?php echo e(route('backend.app-user.bulk-delete')); ?>",
                        type: 'POST',
                        data: { 
                            ids: ids,
                            _token: "<?php echo e(csrf_token()); ?>" 
                        },
                        success: function (response) {
                            if (response.success) {
                                $('.data-table').DataTable().ajax.reload();
                                $('#bulkDeleteBtn').addClass('d-none');
                                $('#selectAll').prop('checked', false);
                                toastr.success(response.message);
                            }
                        }
                    });
                }
            })
        }

        function deleteData(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { _token: "<?php echo e(csrf_token()); ?>" },
                        success: function (response) {
                            if (response.success) {
                                $('.data-table').DataTable().ajax.reload();
                                toastr.success(response.message);
                            }
                        }
                    });
                }
            })
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Sandip\Herd\Sn_Nutrition_nasserlee\resources\views/backend/layout/users/app_users/index.blade.php ENDPATH**/ ?>