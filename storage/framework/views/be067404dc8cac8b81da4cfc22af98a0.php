<?php
    $applicationDocuments = [
        'nrc_document' => 'NRC',
        'collateral_document' => 'Collateral document',
        'recent_payslip' => 'Recent payslip',
        'proof_of_address' => 'Proof of address',
        'bank_statement' => 'Bank statement',
        'signed_form' => 'Completed collateral form',
    ];
?>

<?php $__currentLoopData = $applicationDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($applicant->{$field} && ($field !== 'signed_form' || \Illuminate\Support\Str::startsWith($applicant->signed_form, 'applicant-documents/'))): ?>
        <div class="mt-2">
            <a href="<?php echo e(route('applicant.document.download', [$applicant, $field])); ?>" class="btn btn-sm btn-outline-primary"><?php echo e($label); ?></a>
        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php
    $legacyFiles = array_filter([
        $applicant->attachment ?? null,
        $applicant->image ?? null,
        $applicant->signed_form && !\Illuminate\Support\Str::startsWith($applicant->signed_form, 'applicant-documents/')
            ? $applicant->signed_form
            : null,
    ]);
?>
<?php $__currentLoopData = $legacyFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $documentPath = ltrim($file, '/');
        $documentUrl = file_exists(public_path($documentPath))
            ? asset($documentPath)
            : \Illuminate\Support\Facades\Storage::disk('public')->url($file);
    ?>
    <div class="mt-2">
        <a href="<?php echo e($documentUrl); ?>" target="_blank" class="btn btn-sm btn-outline-primary">View file</a>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if(!$applicant->attachment && !$applicant->image && !$applicant->nrc_document && !$applicant->collateral_document && !$applicant->recent_payslip && !$applicant->proof_of_address && !$applicant->bank_statement && !$applicant->signed_form): ?>
    <div class="mt-2 text-muted small">No file uploaded</div>
<?php endif; ?>

<?php if($applicant->public_application_details): ?>
    <details class="mt-3">
        <summary class="small fw-semibold">View applicant, employment and collateral details</summary>
        <dl class="row small mt-2 mb-0">
            <?php $__currentLoopData = [
                'residential_address' => 'Street address',
                'residential_address_line_2' => 'Address line 2',
                'city' => 'City',
                'state' => 'State / Province',
                'zip_code' => 'Zip code',
                'country' => 'Country',
                'next_of_kin_first_name' => 'Next of kin first name',
                'next_of_kin_last_name' => 'Next of kin last name',
                'next_of_kin_phone' => 'Next of kin phone',
                'next_of_kin_relationship' => 'Next of kin relationship',
                'employer_name' => 'Employer',
                'job_title' => 'Job title',
                'employee_number' => 'Employee number',
                'monthly_net_salary' => 'Monthly net salary (K)',
                'collateral_description' => 'Collateral description',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!empty($applicant->public_application_details[$field])): ?>
                    <dt class="col-sm-5"><?php echo e($label); ?></dt>
                    <dd class="col-sm-7"><?php echo e($applicant->public_application_details[$field]); ?></dd>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </dl>
    </details>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\laravel-app\resources\views/partials/applicant-documents.blade.php ENDPATH**/ ?>