<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">


<!-- Mirrored from themesbrand.com/velzon/html/default/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 25 Aug 2022 17:02:18 GMT -->
<head>

    <meta charset="utf-8" />
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <?php echo $__env->yieldPushContent('styles-top'); ?>
    <?php echo $__env->make('backend.partials.style', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldPushContent('styles-bottom'); ?>

</head>

<body>
    <div id="layout-wrapper">
        <!-- topbar -->
        <?php echo $__env->make('backend.partials.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- ========== App Menu ========== -->
        <?php echo $__env->make('backend.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>
        <div class="main-content">
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
     
            <div class="page-content">
                <div class="container-fluid">
                    <?php echo $__env->yieldContent('content'); ?>
                    
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php echo $__env->make('backend.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <!-- end main content-->

    </div>
    <?php echo $__env->yieldPushContent('scripts-top'); ?>
    <!-- END layout-wrapper -->
    <?php echo $__env->make('backend.partials.script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldPushContent('scripts-bottom'); ?>
    <?php echo $__env->make('backend.partials.notifications', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</body>


<!-- Mirrored from themesbrand.com/velzon/html/default/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 25 Aug 2022 17:03:13 GMT -->
</html><?php /**PATH C:\Users\Sandip\Herd\Sn_Nutrition_nasserlee\resources\views/backend/master.blade.php ENDPATH**/ ?>