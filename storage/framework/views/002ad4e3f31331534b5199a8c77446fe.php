

<?php $__env->startSection('title', 'Approved Loans'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Approved Loans</h2>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">Back to dashboard</a>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Loan Amount</th>
                        <th>Duration</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $applicants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $applicant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($applicant->name); ?></td>
                            <td><?php echo e($applicant->email); ?></td>
                            <td>K<?php echo e(number_format($applicant->loan_amount, 2)); ?></td>
                            <td><?php echo e($applicant->loan_duration); ?></td>
                            <td><?php echo e($applicant->loan_status); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5">No approved loans found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($applicants->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($applicants->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-app\resources\views/admin/approved-loans.blade.php ENDPATH**/ ?>