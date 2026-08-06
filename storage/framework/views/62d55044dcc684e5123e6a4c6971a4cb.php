<!-- JAVASCRIPT -->
<!-- jQuery (required) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Dropify JS -->
<script src="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/js/dropify.min.js"></script>

<!--Swiper sweet alert 2 js-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script src="<?php echo e(asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/libs/simplebar/simplebar.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/libs/node-waves/waves.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/libs/feather-icons/feather.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/pages/plugins/lord-icon-2.1.0.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/plugins.js')); ?>"></script>





<!-- apexcharts -->
<script src="<?php echo e(asset('assets/libs/apexcharts/apexcharts.min.js')); ?>"></script>

<!-- Vector map-->
<script src="<?php echo e(asset('assets/libs/jsvectormap/js/jsvectormap.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/libs/jsvectormap/maps/world-merc.js')); ?>"></script>

<!--Swiper slider js--> 
<script src="<?php echo e(asset('assets/libs/swiper/swiper-bundle.min.js')); ?>"></script>

<!-- Dashboard init -->
<script src="<?php echo e(asset('assets/js/pages/dashboard-ecommerce.init.js')); ?>"></script>


<!-- DataTables with Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<!-- App js -->
<script src="<?php echo e(asset('assets/js/app.js')); ?>"></script>


<script>
    $(document).ready(function(){
        // Initialize Dropify

        // Optional events
        let drEvent = $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop a file',
                'replace': 'Drag and drop or click to replace',
                'remove':  'Remove file',
                'error':   'Oops, something wrong happened.'
            }
        });

        drEvent.on('dropify.beforeClear', function(event, element){
            if (element.input.data('confirmed') === true) {
                element.input.data('confirmed', false);
                return true;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete \"" + element.file.name + "\"?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    element.input.data('confirmed', true);
                    element.clearElement();
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'File deleted successfully',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            });

            return false;
        });
    });
</script>

<script>
    $(document).ready(function(){
        const logoutBtn = document.getElementById('logout-button');
        logoutBtn.style.cursor = "pointer";

        $('#logout-button').on('click', function(){
            fetch("<?php echo e(route('auth.logout.post')); ?>",
            {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                    "Accept": 'application/json'
                }   
            }).then(response => {
                if(response.ok){
                    window.location.href = "<?php echo e(route('login')); ?>";
                }
            });
        });
    });

</script>

<script>
    function initBulkDelete(url, tableSelector = '.data-table') {
        // Handle "Select All" checkbox
        $(document).on('change', '#checkAll', function() {
            let isChecked = $(this).prop('checked');
            $(tableSelector + ' tbody .row-checkbox').prop('checked', isChecked);
            toggleBulkDeleteBtn();
        });

        // Handle individual row checkbox
        $(document).on('change', tableSelector + ' tbody .row-checkbox', function() {
            let allChecked = $(tableSelector + ' tbody .row-checkbox').length === $(tableSelector + ' tbody .row-checkbox:checked').length;
            $('#checkAll').prop('checked', allChecked);
            toggleBulkDeleteBtn();
        });

        // Toggle Bulk Delete Button visibility
        function toggleBulkDeleteBtn() {
            if ($('.row-checkbox:checked').length > 0) {
                $('#bulkDeleteBtn').removeClass('d-none');
            } else {
                $('#bulkDeleteBtn').addClass('d-none');
            }
        }

        // Handle Bulk Delete Button click
        $(document).off('click', '#bulkDeleteBtn').on('click', '#bulkDeleteBtn', function() {
            let selectedIds = [];
            $('.row-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete the selected items?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { 
                            ids: selectedIds, 
                            _token: "<?php echo e(csrf_token()); ?>" 
                        },
                        success: function (response) {
                            if (response.success) {
                                $(tableSelector).DataTable().ajax.reload(null, false);
                                $('#checkAll').prop('checked', false);
                                toggleBulkDeleteBtn();
                                Swal.fire({
                                    toast: true,
                                    position: "top-end",
                                    icon: "success",
                                    title: response.message || "Items deleted successfully",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });
                            } else {
                                Swal.fire({
                                    toast: true,
                                    position: "top-end",
                                    icon: "error",
                                    title: response.message || "Something went wrong",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });
                            }
                        }
                    });
                }
            });
        });
        
        // Reset state when datatable is redrawn
        $(tableSelector).on('draw.dt', function () {
            $('#checkAll').prop('checked', false);
            toggleBulkDeleteBtn();
        });
    }
</script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script><?php /**PATH C:\Users\Sandip\Herd\Sn_Nutrition_nasserlee\resources\views/backend/partials/script.blade.php ENDPATH**/ ?>