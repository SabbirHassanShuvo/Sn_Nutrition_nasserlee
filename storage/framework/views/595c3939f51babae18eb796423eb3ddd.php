<?php $__env->startSection('title', 'Brand Management'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" id="brandList">
                <div class="card-header border-0 bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold text-primary">Brands</h5>
                        <p class="text-muted mb-0 fs-12">Manage product brands</p>
                    </div>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm shadow-sm d-none align-items-center" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> Bulk Delete
                        </button>
                        <button type="button" class="btn btn-primary btn-sm add-btn shadow-sm d-flex align-items-center" onclick="openCreateModal()">
                            <i class="ri-add-line align-bottom me-1"></i> Add New Brand
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
                                    <th style="width: 80px;">Logo</th>
                                    <th class="text-start">Brand Name</th>
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

    <!-- Brand Modal -->
    <div class="modal fade text-start" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="brandForm" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="brand_id" id="brand_id" value="<?php echo e(old('brand_id')); ?>">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="brandModalLabel">Create Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="brand_name">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="brand_name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Enter brand name" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label fw-semibold" for="brand_specialty">Specialty / Tagline</label>
                                <input type="text" name="specialty" id="brand_specialty" class="form-control" placeholder="e.g. Performance">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label fw-semibold" for="brand_rating">Rating (0-5)</label>
                                <input type="number" step="0.1" min="0" max="5" name="rating" id="brand_rating" class="form-control" placeholder="e.g. 4.5">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Brand Logo</label>
                            <div id="dropify-wrapper-container">
                                <input type="file" name="image" id="brand_image" class="dropify" data-height="200" accept="image/*" />
                            </div>
                            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger mt-1 d-block"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Create Brand</button>
                    </div>
                </form>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
    <script>
        (function ($) {
            $(function () {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: { details: true },
                    dom: '<"row mb-3 px-3 mt-3"<"col-md-6"l><"col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3 px-3 pb-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                    ajax: "<?php echo e(route('backend.brand.index')); ?>",
                    columns: [
                        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-3 text-muted fw-medium' },
                        { data: 'image', name: 'image', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'name', name: 'name', className: 'text-start fw-medium' },
                        { data: 'slug', name: 'slug' },
                        { data: 'status', name: 'status', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ]
                });

                initBulkDelete("<?php echo e(route('backend.brand.bulk-destroy')); ?>");
            });
        })(jQuery);

        function showStatusChangeAlert(id) {
            let url = "<?php echo e(route('backend.brand.status', ':id')); ?>";
            $.ajax({
                type: "POST",
                url: url.replace(':id', id),
                data: { id: id, _token: "<?php echo e(csrf_token()); ?>" },
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

        function resetDropify(defaultUrl = '') {
            $('#dropify-wrapper-container').html('<input type="file" name="image" id="brand_image" class="dropify" data-height="200" accept="image/*" />');
            if (defaultUrl) {
                $('#brand_image').attr('data-default-file', defaultUrl);
            }
            $('.dropify').dropify();
        }

        function openCreateModal() {
            $('#brandForm').trigger("reset");
            $('#brandForm').attr('action', "<?php echo e(route('backend.brand.store')); ?>");
            $('#formMethod').val('POST');
            $('#brand_id').val('');
            $('#brandModalLabel').text('Create Brand');
            $('#saveBtn').text('Create Brand');
            resetDropify();
            $('#brandModal').modal('show');
        }

        // Intercept Edit Button
        $(document).on('click', '.data-table .btn-soft-info', function(e) {
            e.preventDefault();
            let editUrl = $(this).attr('href');
            
            $.ajax({
                type: "GET",
                url: editUrl,
                success: function(response) {
                    if (response.success) {
                        let brand = response.data;
                        let updateUrl = "<?php echo e(route('backend.brand.update', ':id')); ?>".replace(':id', brand.id);
                        
                        $('#brandForm').attr('action', updateUrl);
                        $('#formMethod').val('PATCH');
                        $('#brand_id').val(brand.id);
                        $('#brand_name').val(brand.name);
                        $('#brand_specialty').val(brand.specialty);
                        $('#brand_rating').val(brand.rating);
                        $('#brandModalLabel').text('Edit Brand');
                        $('#saveBtn').text('Update Brand');
                        
                        let imgUrl = brand.image ? "<?php echo e(asset('')); ?>" + brand.image : '';
                        resetDropify(imgUrl);
                        
                        $('#brandModal').modal('show');
                    } else {
                        toastr.error("Failed to load brand data.");
                    }
                },
                error: function() {
                    toastr.error("An error occurred while fetching brand details.");
                }
            });
        });

        // Auto-reopen modal if validation errors exist (Laravel Redirect Back)
        <?php if($errors->any()): ?>
            $(document).ready(function() {
                $('#brand_name').val("<?php echo e(old('name')); ?>");
                $('#brand_specialty').val("<?php echo e(old('specialty')); ?>");
                $('#brand_rating').val("<?php echo e(old('rating')); ?>");
                
                let oldMethod = "<?php echo e(old('_method')); ?>";
                let oldId = "<?php echo e(old('brand_id')); ?>";
                if (oldMethod === 'PATCH' && oldId) {
                    let updateUrl = "<?php echo e(route('backend.brand.update', ':id')); ?>".replace(':id', oldId);
                    $('#brandForm').attr('action', updateUrl);
                    $('#formMethod').val('PATCH');
                    $('#brand_id').val(oldId);
                    $('#brandModalLabel').text('Edit Brand');
                    $('#saveBtn').text('Update Brand');
                } else {
                    $('#brandForm').attr('action', "<?php echo e(route('backend.brand.store')); ?>");
                    $('#formMethod').val('POST');
                    $('#brand_id').val('');
                    $('#brandModalLabel').text('Create Brand');
                    $('#saveBtn').text('Create Brand');
                }
                
                $('#brandModal').modal('show');
            });
        <?php endif; ?>
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Sandip\Herd\Sn_Nutrition_nasserlee\resources\views/backend/layout/brands/index.blade.php ENDPATH**/ ?>