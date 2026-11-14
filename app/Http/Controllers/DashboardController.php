<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\LoanFormDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        if (! session('user_id')) {
            return redirect()->route('user.login');
        }

        $userName = session('user_name', 'Customer');
        $loanAmount = session('loan_amount', 0);

        return view('dashboard.user', compact('userName', 'loanAmount'));
    }

    public function adminDashboard()
    {
        if (! session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $recentApplicants = Applicant::where(function ($query) {
            $query->whereIn('loan_status', ['PENDING_REVIEW', 'SUBMITTED', 'RECOMMENDED'])
                ->orWhere(function ($query) {
                    $query->where('loan_status', 'REJECTED')
                        ->where('credit_recommendation', 'REJECT')
                        ->whereNull('admin_decision_at');
                });
        })
            ->orderByDesc('reg_date')
            ->limit(5)
            ->get();
        $totalApplicants = Applicant::count();
        $approvedLoans = Applicant::where('loan_status', 'APPROVED')->count();
        $totalDisbursed = Applicant::where('loan_status', 'APPROVED')->sum('loan_amount');
        $loanOfficers = \App\Models\Admin::where('role', 'loan_officer')->count();
        $creditOfficers = \App\Models\Admin::where('role', 'credit_officer')->count();
        $collateralFormDocument = LoanFormDocument::query()->first();

        return view('admin.dashboard', compact(
            'recentApplicants',
            'totalApplicants',
            'approvedLoans',
            'totalDisbursed',
            'loanOfficers',
            'creditOfficers',
            'collateralFormDocument'
        ));
    }

    public function fieldAgentDashboard()
    {
        if (! session('staff_id') || ! in_array(session('staff_role'), ['loan_officer', 'field_agent'])) {
            return redirect()->route('field.agent.login');
        }

        $applicants = Applicant::where('created_by', session('staff_id'))
            ->whereIn('source', ['field_agent', 'loan_officer'])
            ->whereIn('loan_status', ['PENDING_REVIEW', 'SUBMITTED', 'RECOMMENDED'])
            ->orderByDesc('reg_date')
            ->paginate(10, ['*'], 'active_applicants_page');

        $reviewedApplicants = Applicant::where('created_by', session('staff_id'))
            ->whereIn('source', ['field_agent', 'loan_officer'])
            ->whereIn('loan_status', ['APPROVED', 'REJECTED', 'FULLY_REPAID'])
            ->orderByDesc('reg_date')
            ->paginate(10, ['*'], 'reviewed_applicants_page');

        return view('field-agent.dashboard', compact('applicants', 'reviewedApplicants'));
    }

    public function loanOfficerDashboard()
    {
        if (! session('staff_id') || ! in_array(session('staff_role'), ['loan_officer', 'field_agent'])) {
            return redirect()->route('loan.officer.login');
        }

        $applicants = Applicant::where('created_by', session('staff_id'))
            ->whereIn('source', ['loan_officer', 'field_agent'])
            ->whereIn('loan_status', ['PENDING_REVIEW', 'SUBMITTED', 'RECOMMENDED'])
            ->orderByDesc('reg_date')
            ->paginate(10, ['*'], 'active_applicants_page');

        $reviewedApplicants = Applicant::where('created_by', session('staff_id'))
            ->whereIn('source', ['loan_officer', 'field_agent'])
            ->whereIn('loan_status', ['APPROVED', 'REJECTED', 'FULLY_REPAID'])
            ->orderByDesc('reg_date')
            ->paginate(10, ['*'], 'reviewed_applicants_page');

        return view('staff.loan-officer-dashboard', compact('applicants', 'reviewedApplicants'));
    }

    public function creditOfficerDashboard()
    {
        if (! session('staff_id') || session('staff_role') !== 'credit_officer') {
            return redirect()->route('credit.officer.login');
        }

        $applicants = Applicant::whereIn('loan_status', ['PENDING_REVIEW', 'SUBMITTED', 'RECOMMENDED'])
            ->orderByDesc('reg_date')
            ->paginate(10, ['*'], 'active_applicants_page');

        $reviewedApplicants = Applicant::whereIn('loan_status', ['APPROVED', 'REJECTED', 'FULLY_REPAID'])
            ->orderByDesc('reg_date')
            ->paginate(10, ['*'], 'reviewed_applicants_page');

        return view('staff.credit-officer-dashboard', compact('applicants', 'reviewedApplicants'));
    }
}
