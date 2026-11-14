

<?php $__env->startSection('title', 'Credit Officer Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Credit Officer Dashboard</h2>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline-danger">Logout</button>
        </form>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card p-4 mb-4">
        <h4>Applications awaiting review</h4>
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Amount</th>
                        <th>Attachment</th>
                        <th>Recommendation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $applicants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $applicant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($applicant->name); ?></td>
                            <td><?php echo e($applicant->email); ?></td>
                            <td>K<?php echo e(number_format($applicant->loan_amount, 2)); ?></td>
                            <td>
                                <?php echo $__env->make('partials.applicant-documents', ['applicant' => $applicant], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                <?php if($applicant->credit_recommendation || $applicant->review_notes): ?>
                                    <div class="small mt-2">
                                        <?php if($applicant->credit_recommendation): ?>
                                            <div><strong>Recommendation:</strong> <?php echo e($applicant->credit_recommendation); ?></div>
                                        <?php endif; ?>
                                        <?php if($applicant->review_notes): ?>
                                            <div><strong>Comments:</strong> <?php echo e($applicant->review_notes); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(in_array($applicant->loan_status, ['PENDING_REVIEW', 'RECOMMENDED'])): ?>
                                    <form method="POST" action="<?php echo e(route('credit.officer.recommend', $applicant)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-2">
                                            <select name="decision" class="form-select form-select-sm" required>
                                                <option value="RECOMMEND">Recommend</option>
                                                <option value="REJECT">Reject</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <textarea name="review_notes" class="form-control form-control-sm" rows="2" placeholder="Notes"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                    </form>
                                <?php elseif($applicant->loan_status === 'APPROVED'): ?>
                                    <form method="POST" action="<?php echo e(route('credit.officer.close', $applicant)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-2">
                                            <textarea name="review_notes" class="form-control form-control-sm" rows="2" placeholder="Closing note"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-warning">Mark fully repaid</button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e($applicant->loan_status); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center">No applications to review.</td>
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
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Decision Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reviewedApplicants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $applicant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($applicant->name); ?></td>
                            <td><?php echo e($applicant->email); ?></td>
                            <td>K<?php echo e(number_format($applicant->loan_amount, 2)); ?></td>
                            <td>
                                <span class="badge <?php echo e($applicant->loan_status === 'FULLY_REPAID' ? 'bg-success' : 'bg-secondary'); ?>">
                                    <?php echo e($applicant->loan_status); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($applicant->credit_recommendation): ?>
                                    <?php echo e($applicant->credit_recommendation); ?>

                                <?php endif; ?>
                                <?php if($applicant->review_notes): ?>
                                    <div class="small text-muted mt-1"><?php echo e($applicant->review_notes); ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($applicant->loan_status === 'APPROVED'): ?>
                                    <form method="POST" action="<?php echo e(route('credit.officer.close', $applicant)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-warning">Close loan</button>
                                    </form>
                                <?php elseif($applicant->loan_status === 'FULLY_REPAID'): ?>
                                    <span class="text-success small fw-bold">Closed</span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-app\resources\views/staff/credit-officer-dashboard.blade.php ENDPATH**/ ?>