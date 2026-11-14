

<?php $__env->startSection('title', 'Recent Applicants'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Recent Applicants</h2>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">Back to dashboard</a>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contacts</th>
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
                            <td><?php echo e($applicant->contacts); ?></td>
                            <td>K<?php echo e(number_format($applicant->loan_amount, 2)); ?></td>
                            <td><?php echo e($applicant->loan_duration); ?></td>
                            <td>
                                <div><strong>Status:</strong> <?php echo e($applicant->loan_status); ?></div>
                                <div class="mt-2"><strong>Recommendation:</strong> <?php echo e($applicant->credit_recommendation ?? 'Not yet reviewed'); ?></div>
                                <div class="mt-2"><strong>Comments:</strong> <?php echo e($applicant->review_notes ?? 'No comments yet'); ?></div>

                                <?php echo $__env->make('partials.applicant-documents', ['applicant' => $applicant], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                <form method="POST" action="<?php echo e(route('admin.application.decision', $applicant)); ?>" class="mt-3">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-2">
                                        <label class="form-label small mb-1">Decision notes</label>
                                        <textarea name="review_notes" class="form-control form-control-sm" rows="2" placeholder="Add reason for rejection or approval notes"></textarea>
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <select name="decision" class="form-select" required>
                                            <option value="APPROVED">Approve</option>
                                            <option value="REJECTED">Reject</option>
                                        </select>
                                        <button type="submit" class="btn btn-dark">Save</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6">No applicants found.</td>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-app\resources\views/admin/recent-applicants.blade.php ENDPATH**/ ?>