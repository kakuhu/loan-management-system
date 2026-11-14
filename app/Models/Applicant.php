<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $table = 'applicants';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'date_of_birth',
        'nrc',
        'contacts',
        'gps_coordinates',
        'location',
        'marital_status',
        'spouse_name',
        'cell_number',
        'business_type',
        'business_experience_years',
        'stock_of_item_sold',
        'average_daily_sales',
        'business_address',
        'loan_amount',
        'interest_rate',
        'interest_amount',
        'loan_processing_fee',
        'total_amount_repayable',
        'loan_duration',
        'loan_status',
        'image',
        'attachment',
        'signed_form',
        'public_application_details',
        'nrc_document',
        'collateral_document',
        'recent_payslip',
        'proof_of_address',
        'bank_statement',
        'declaration_accepted_at',
        'admin_decision_at',
        'source',
        'created_by',
        'review_notes',
        'credit_recommendation',
        'reg_date',
    ];

    protected function casts(): array
    {
        return [
            'public_application_details' => 'array',
            'declaration_accepted_at' => 'datetime',
            'admin_decision_at' => 'datetime',
        ];
    }
}
