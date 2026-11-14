

<?php $__env->startSection('title', 'Loan Officer Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Loan Officer Dashboard</h2>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline-danger">Logout</button>
        </form>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-end mb-3">
        <a href="<?php echo e(route('loan.officer.applications.create')); ?>" class="btn btn-primary">Create client application</a>
    </div>

    <div class="card p-4 mb-4">
        <h4>Applications for field collection</h4>
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Loan Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $applicants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $applicant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($applicant->name); ?></td>
                            <td><?php echo e($applicant->email); ?></td>
                            <td>K<?php echo e(number_format($applicant->loan_amount, 2)); ?></td>
                            <td>
                                <div><?php echo e($applicant->loan_status); ?></div>
                                <?php if($applicant->credit_recommendation || $applicant->review_notes): ?>
                                    <small class="text-muted d-block mt-1">
                                        <?php if($applicant->credit_recommendation): ?>
                                            Recommendation: <?php echo e($applicant->credit_recommendation); ?>

                                        <?php endif; ?>
                                        <?php if($applicant->review_notes): ?>
                                            <br>Notes: <?php echo e($applicant->review_notes); ?>

                                        <?php endif; ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(in_array($applicant->loan_status, ['PENDING_REVIEW', 'SUBMITTED'])): ?>
                                    <form method="POST" action="<?php echo e(route('loan.officer.submit', $applicant)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-primary">Send to credit officer</button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark">Decision received</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center">No applications available.</td>
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

    <div class="card p-4">
        <h4>Reviewed Applications</h4>
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Loan Amount</th>
                        <th>Status</th>
                        <th>Recommendation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reviewedApplicants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $applicant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($applicant->name); ?></td>
                            <td><?php echo e($applicant->email); ?></td>
                            <td>K<?php echo e(number_format($applicant->loan_amount, 2)); ?></td>
                            <td><?php echo e($applicant->loan_status); ?></td>
                            <td>
                                <?php if($applicant->credit_recommendation): ?>
                                    <?php echo e($applicant->credit_recommendation); ?>

                                <?php else: ?>
                                    <span class="text-muted">No recommendation</span>
                                <?php endif; ?>
                                <?php if($applicant->review_notes): ?>
                                    <div class="small text-muted mt-1"><?php echo e($applicant->review_notes); ?></div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center">No reviewed applications yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($reviewedApplicants->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($reviewedApplicants->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-app\resources\views/staff/loan-officer-dashboard.blade.php ENDPATH**/ ?>