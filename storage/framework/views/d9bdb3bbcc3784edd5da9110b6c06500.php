<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Approval</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #1f2937; background: #f3f4f6; padding: 24px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 32px;">
        <h2 style="margin-top: 0; color: #166534;">Congratulations, <?php echo e($applicant->name); ?>!</h2>

        <p>We are pleased to inform you that your loan application has been approved.</p>

        <p><strong>Approved amount:</strong> ZMW <?php echo e(number_format($applicant->loan_amount ?? 0, 2)); ?></p>
        <p><strong>Loan duration:</strong> <?php echo e($applicant->loan_duration ?? 'N/A'); ?> months</p>

        <p>The next step is for our team to reach out with the disbursement and documentation details.</p>

        <p>Thank you for choosing us.</p>

        <p style="margin-bottom: 0;">Best regards,<br>
        Loan Support Team</p>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\laravel-app\resources\views/emails/loan-approved.blade.php ENDPATH**/ ?>