
<?php if(session('success')): ?>
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '<?php echo e(session('success')); ?>',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>
<?php endif; ?>

<?php if(session('error')): ?>
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: '<?php echo e(session('error')); ?>',
        showConfirmButton: false,
        timer: 3000
    });
</script>
<?php endif; ?>

<?php if(@$errors->any()): ?>
<script>
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: '<?php echo e($error); ?>',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</script>
<?php endif; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let html = document.documentElement; // <html>
        // Load saved mode
        let savedMode = localStorage.getItem("layout-mode");
        if (savedMode) {
            html.setAttribute("data-layout-mode", savedMode);
        }

        // Toggle on click
        let toggleBtn = document.querySelector(".light-dark-mode");
        if (toggleBtn) {
            toggleBtn.addEventListener("click", function () {

                let current = html.getAttribute("data-layout-mode");
                console.log(current)
                let newMode = current === "dark" ? "dark" : "light";
                console.log(current)
                html.setAttribute("data-layout-mode", newMode);
                localStorage.setItem("layout-mode", newMode);
            });
        }
    });
</script><?php /**PATH C:\Users\Sandip\Herd\Sn_Nutrition_nasserlee\resources\views/backend/partials/notifications.blade.php ENDPATH**/ ?>