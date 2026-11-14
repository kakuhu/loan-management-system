<?php

namespace App\Http\Controllers;

use App\Mail\LoanApprovedMail;
use App\Models\Applicant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LoanWorkflowController extends Controller
{
    public function submitToCredit(Applicant $applicant): RedirectResponse
    {
        if (! session('staff_id') || ! in_array(session('staff_role'), ['loan_officer', 'field_agent'])) {
            return redirect()->route('loan.officer.login');
        }

        $applicant->update([
            'loan_status' => 'PENDING_REVIEW',
            'source' => $applicant->source ?? 'loan_officer',
        ]);

        return redirect()->route('loan.officer.dashboard')->with('success', 'Application submitted to the credit officer for review.');
    }

    public function recommend(Request $request, Applicant $applicant): RedirectResponse
    {
        if (! session('staff_id') || session('staff_role') !== 'credit_officer') {
            return redirect()->route('credit.officer.login');
        }

        $validated = $request->validate([
            'decision' => ['required', 'in:RECOMMEND,REJECT'],
            'review_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $applicant->update([
            'loan_status' => 'RECOMMENDED',
            'credit_recommendation' => $validated['decision'] === 'RECOMMEND' ? 'RECOMMEND' : 'REJECT',
            'review_notes' => $validated['review_notes'] ?? null,
        ]);

        return redirect()->route('credit.officer.dashboard')->with('success', 'Recommendation submitted to the admin for final approval.');
    }

    public function finalDecision(Request $request, Applicant $applicant): RedirectResponse
    {
        if (! session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'decision' => ['required', 'in:APPROVED,REJECTED'],
            'review_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validated['decision'] === 'REJECTED' && blank($validated['review_notes'] ?? null)) {
            return redirect()->back()->withErrors([
                'review_notes' => 'A rejection reason is required before the loan can be rejected.',
            ])->withInput();
        }

        $applicant->update([
            'loan_status' => $validated['decision'],
            'review_notes' => $validated['review_notes'] ?? $applicant->review_notes,
            'admin_decision_at' => now(),
        ]);

        if ($validated['decision'] === 'APPROVED' && filled($applicant->email)) {
            Mail::to($applicant->email)->send(new LoanApprovedMail($applicant));
        }

        return redirect()->route('admin.applicants')->with('success', 'Final decision recorded successfully.');
    }

    public function closeLoan(Request $request, Applicant $applicant): RedirectResponse
    {
        if (! session('staff_id') || session('staff_role') !== 'credit_officer') {
            return redirect()->route('credit.officer.login');
        }

        $validated = $request->validate([
            'review_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $applicant->update([
            'loan_status' => 'FULLY_REPAID',
            'review_notes' => $validated['review_notes'] ?? 'Customer has fully repaid the loan balance.',
        ]);

        return redirect()->route('credit.officer.dashboard')->with('success', 'Loan marked as fully repaid.');
    }
}
