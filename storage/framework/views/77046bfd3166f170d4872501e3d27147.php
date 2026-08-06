<?php $__env->startSection('title', 'Sign In | admin'); ?>
<?php $__env->startSection('content'); ?>
    <form method="post" action="<?php echo e(route('auth.login.post')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="username" 
                name="email" value="<?php echo e(old('email')); ?>"
            placeholder="Enter email">
        </div>

        <div class="mb-3">
            <div class="float-end">
                <a href="<?php echo e(route('auth.reset.link.get')); ?>" class="text-muted">Forgot password?</a>
            </div>
            <label class="form-label" for="password-input">Password</label>
            <div class="position-relative auth-pass-inputgroup mb-3">
                <input type="password" class="form-control pe-5 password-input" id="password-input"
                    name="password"
                 placeholder="Enter password">
                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
            </div>
        </div>

        

        <div class="mt-4">
            <button class="btn btn-success w-100" type="submit">Sign In</button>
        </div>

        

    </form>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('srcipts-bottom'); ?>
    <!-- password-custom logi -->
    <script src="<?php echo e(asset('assets/js/raihan/password-toggle.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('backend.layout.auth.auth-app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Sandip\Herd\Sn_Nutrition_nasserlee\resources\views/backend/layout/auth/login.blade.php ENDPATH**/ ?>