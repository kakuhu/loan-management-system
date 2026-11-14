

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div><?php echo e($error); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Admin Dashboard</h2>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline-danger">Logout</button>
        </form>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <h5>Total Applicants</h5>
                <p class="display-6 mb-0"><?php echo e($totalApplicants); ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <h5>Approved Loans</h5>
                <p class="display-6 mb-0"><?php echo e($approvedLoans); ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <h5>Disbursed</h5>
                <p class="display-6 mb-0">K<?php echo e(number_format($totalDisbursed, 2)); ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100 text-center">
                <a href="<?php echo e(route('admin.applicants')); ?>" class="btn btn-primary w-100 mb-2">Recent Applicants</a>
                <a href="<?php echo e(route('admin.approved_loans')); ?>" class="btn btn-success w-100 mb-2">Approved Loans</a>
                <a href="<?php echo e(route('admin.monthly_report')); ?>" class="btn btn-warning w-100">Monthly Report</a>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h4>Public Collateral Form</h4>
        <p class="text-muted">Upload a PDF or Word document for applicants to download, complete, and upload with their application. Uploading another file replaces the current form.</p>
        <?php if($collateralFormDocument): ?>
            <p class="mb-3">
                Current form:
                <a href="<?php echo e(route('loan.collateral-form.download')); ?>"><?php echo e($collateralFormDocument->original_name); ?></a>
            </p>
        <?php else: ?>
            <p class="mb-3">No collateral form has been uploaded yet.</p>
        <?php endif; ?>
        <form method="POST" action="<?php echo e(route('admin.collateral_form.update')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label" for="collateral_form">Collateral form document</label>
                    <input id="collateral_form" type="file" name="collateral_form" class="form-control" accept=".pdf,.doc,.docx" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Upload / Replace Form</button>
                </div>
            </div>
        </form>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h5>Loan Officers</h5>
                <p class="display-6 mb-0"><?php echo e($loanOfficers); ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h5>Credit Officers</h5>
                <p class="display-6 mb-0"><?php echo e($creditOfficers); ?></p>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <h4>Recent Applications</h4>
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentApplicants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $applicant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($applicant->name); ?></td>
                            <td><?php echo e($applicant->email); ?></td>
                            <td>K<?php echo e(number_format($applicant->loan_amount, 2)); ?></td>
                            <td><?php echo e($applicant->loan_status); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4">No recent applications found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-app\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>