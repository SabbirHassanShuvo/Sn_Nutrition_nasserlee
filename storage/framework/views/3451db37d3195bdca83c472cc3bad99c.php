<?php $__env->startSection('title', 'Dashboard | Velzon - Admin & Dashboard Template'); ?>

<?php $__env->startSection('content'); ?>
<!-- Begin page -->

        

     

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Dashboard</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-16 mb-1">Good Morning, <?php echo e(Auth::user()->name); ?>!</h4>
                                <p class="text-muted mb-0">Here's what's happening with your store today.</p>
                            </div>
                            <div class="mt-3 mt-lg-0">
                                <form action="javascript:void(0);">
                                    <div class="row g-3 mb-0 align-items-center">
                                        <!-- <div class="col-sm-auto">
                                            <div class="input-group">
                                                <input type="text" class="form-control border-0 dash-filter-picker shadow" data-provider="flatpickr" data-range-date="true" data-date-format="d M, Y" data-deafult-date="01 Jan 2022 to 31 Jan 2022">
                                                <div class="input-group-text bg-primary border-primary text-white">
                                                    <i class="ri-calendar-2-line"></i>
                                                </div>
                                            </div>
                                        </div> -->
                                        <!--end col-->
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-soft-success"><i class="ri-add-circle-line align-middle me-1"></i> Add Product</button>
                                        </div>
                                        <!--end col-->
                                        <!-- <div class="col-auto">
                                            <button type="button" class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn"><i class="ri-pulse-line"></i></button>
                                        </div> -->
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <!-- stat 1 -->
                <?php echo $__env->make('backend.partials.stat-top', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <!-- end row-->

                <!-- chart 2 : best & top sellers-->
                
                <!-- end row-->

            </div>
            <!-- end .h-100-->

        </div>
        <!-- end col -->

    </div>





    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Sandip\Herd\Sn_Nutrition_nasserlee\resources\views/backend/index.blade.php ENDPATH**/ ?>