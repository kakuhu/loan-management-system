<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\LoanFormDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LoanApplicationController extends Controller
{
    private function storeUploadedDocument(Request $request, string $fieldName): ?string
    {
        if (! $request->hasFile($fieldName)) {
            return null;
        }

        $upload = $request->file($fieldName);
        $directory = public_path('file');

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $upload->getClientOriginalName());
        $upload->move($directory, $filename);

        return 'file/' . $filename;
    }

    public function create()
    {
        $collateralFormDocument = LoanFormDocument::query()->first();

        return view('loan.apply', [
            'collateralFormDocument' => $collateralFormDocument,
        ]);
    }

    public function store(Request $request)
    {
        $collateralFormDocument = LoanFormDocument::query()->first();
        $hasCollateralForm = $collateralFormDocument !== null;

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'nrc' => ['required', 'string', 'max:255'],
            'contacts' => ['required', 'string', 'max:255'],
            'residential_address' => ['required', 'string', 'max:255'],
            'residential_address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:100'],
            'next_of_kin_first_name' => ['required', 'string', 'max:255'],
            'next_of_kin_last_name' => ['required', 'string', 'max:255'],
            'next_of_kin_phone' => ['required', 'string', 'max:255'],
            'next_of_kin_relationship' => ['required', 'string', 'max:100'],
            'employer_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'employee_number' => ['required', 'string', 'max:100'],
            'monthly_net_salary' => ['required', 'numeric', 'min:0'],
            'loanamount' => ['required', 'numeric', 'min:1'],
            'collateral_description' => ['required', 'string', 'max:2000'],
            'nrc_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'collateral_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'recent_payslip' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'proof_of_address' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'bank_statement' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'signed_form' => [$hasCollateralForm ? 'required' : 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:5120'],
            'declaration' => ['accepted'],
        ]);

        $documentPaths = [];
        foreach (['nrc_document', 'collateral_document', 'recent_payslip', 'proof_of_address', 'bank_statement'] as $field) {
            $documentPaths[$field] = $request->file($field)->store('applicant-documents', 'local');

            if (! $documentPaths[$field]) {
                throw new \RuntimeException("The {$field} file could not be saved.");
            }
        }

        $signedFormPath = null;
        if ($request->hasFile('signed_form')) {
            $signedFormPath = $request->file('signed_form')->store('applicant-documents', 'local');

            if (! $signedFormPath) {
                throw new \RuntimeException('The completed collateral form could not be saved.');
            }
        }

        $publicDetails = $request->only([
            'first_name',
            'last_name',
            'residential_address',
            'residential_address_line_2',
            'city',
            'state',
            'zip_code',
            'country',
            'next_of_kin_first_name',
            'next_of_kin_last_name',
            'next_of_kin_phone',
            'next_of_kin_relationship',
            'employer_name',
            'job_title',
            'employee_number',
            'monthly_net_salary',
            'collateral_description',
        ]);
        $publicDetails['declaration_accepted'] = true;
        $loanAmount = (float) $validated['loanamount'];
        $interestAmount = round($loanAmount * 0.30, 2);
        $processingFee = 100;

        Applicant::create([
            'name' => trim($validated['first_name'] . ' ' . $validated['last_name']),
            'email' => $validated['email'],
            'nrc' => $validated['nrc'],
            'contacts' => $validated['contacts'],
            'loan_amount' => $validated['loanamount'],
            'interest_rate' => 30,
            'interest_amount' => $interestAmount,
            'loan_processing_fee' => $processingFee,
            'total_amount_repayable' => round($loanAmount + $interestAmount + $processingFee, 2),
            'loan_duration' => 1,
            'loan_status' => 'PENDING_REVIEW',
            'attachment' => null,
            'signed_form' => $signedFormPath,
            'public_application_details' => $publicDetails,
            'nrc_document' => $documentPaths['nrc_document'],
            'collateral_document' => $documentPaths['collateral_document'],
            'recent_payslip' => $documentPaths['recent_payslip'],
            'proof_of_address' => $documentPaths['proof_of_address'],
            'bank_statement' => $documentPaths['bank_statement'],
            'declaration_accepted_at' => now(),
            'source' => 'web',
            'reg_date' => now()->toDateString(),
        ]);

        return redirect()->route('landing')->with('success', 'Loan application submitted successfully.');
    }

    public function createStaffApplication()
    {
        if (! session('staff_id') || ! in_array(session('staff_role'), ['loan_officer', 'field_agent'])) {
            return redirect()->route('field.agent.login');
        }

        return view('staff.create-application');
    }

    public function storeStaffApplication(Request $request)
    {
        if (! session('staff_id') || ! in_array(session('staff_role'), ['loan_officer', 'field_agent'])) {
            return redirect()->route('field.agent.login');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'nrc' => ['required', 'string', 'max:255'],
            'contacts' => ['required', 'string', 'max:255'],
            'gps_coordinates' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'marital_status' => ['nullable', 'string', 'max:100'],
            'spouse_name' => ['nullable', 'string', 'max:255'],
            'cell_number' => ['nullable', 'string', 'max:255'],
            'business_type' => ['nullable', 'string', 'max:255'],
            'business_experience_years' => ['nullable', 'numeric', 'min:0'],
            'stock_of_item_sold' => ['nullable', 'string', 'max:255'],
            'average_daily_sales' => ['nullable', 'numeric', 'min:0'],
            'business_address' => ['nullable', 'string', 'max:255'],
            'loanamount' => ['required', 'numeric', 'min:0'],
            'interest_rate' => ['nullable', 'numeric', 'min:0'],
            'interest_amount' => ['nullable', 'numeric', 'min:0'],
            'loan_processing_fee' => ['nullable', 'numeric', 'min:0'],
            'total_amount_repayable' => ['nullable', 'numeric', 'min:0'],
            'loanduration' => ['required', 'numeric', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'signed_form' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'source' => ['nullable', 'in:loan_officer,field_agent'],
        ]);

        $imagePath = null;
        $attachmentPath = null;
        $signedFormPath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->storeUploadedDocument($request, 'image');
        }

        if ($request->hasFile('attachment')) {
            $attachmentPath = $this->storeUploadedDocument($request, 'attachment');
        }

        if ($request->hasFile('signed_form')) {
            $signedFormPath = $this->storeUploadedDocument($request, 'signed_form');
        }

        $source = $validated['source'] ?? (session('staff_role') === 'field_agent' ? 'field_agent' : 'loan_officer');

        Applicant::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'nrc' => $validated['nrc'],
            'contacts' => $validated['contacts'],
            'gps_coordinates' => $validated['gps_coordinates'] ?? null,
            'location' => $validated['location'] ?? null,
            'marital_status' => $validated['marital_status'] ?? null,
            'spouse_name' => $validated['spouse_name'] ?? null,
            'cell_number' => $validated['cell_number'] ?? null,
            'business_type' => $validated['business_type'] ?? null,
            'business_experience_years' => $validated['business_experience_years'] ?? null,
            'stock_of_item_sold' => $validated['stock_of_item_sold'] ?? null,
            'average_daily_sales' => $validated['average_daily_sales'] ?? null,
            'business_address' => $validated['business_address'] ?? null,
            'loan_amount' => $validated['loanamount'],
            'interest_rate' => $validated['interest_rate'] ?? null,
            'interest_amount' => $validated['interest_amount'] ?? null,
            'loan_processing_fee' => $validated['loan_processing_fee'] ?? null,
            'total_amount_repayable' => $validated['total_amount_repayable'] ?? null,
            'loan_duration' => $validated['loanduration'],
            'loan_status' => 'PENDING_REVIEW',
            'image' => $imagePath,
            'attachment' => $attachmentPath,
            'signed_form' => $signedFormPath,
            'source' => $source,
            'created_by' => session('staff_id'),
            'reg_date' => now()->toDateString(),
        ]);

        return redirect()->route(session('staff_role') === 'field_agent' ? 'field.agent.dashboard' : 'loan.officer.dashboard')
            ->with('success', 'Client application submitted to the credit officer for review.');
    }
}
