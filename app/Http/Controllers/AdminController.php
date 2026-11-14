<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\LoanFormDocument;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function recentApplicants()
    {
        $applicants = Applicant::where(function ($query) {
            $query->whereIn('loan_status', ['PENDING_REVIEW', 'SUBMITTED', 'RECOMMENDED'])
                ->orWhere(function ($query) {
                    $query->where('loan_status', 'REJECTED')
                        ->where('credit_recommendation', 'REJECT')
                        ->whereNull('admin_decision_at');
                });
        })
            ->orderByDesc('reg_date')
            ->paginate(10);

        return view('admin.recent-applicants', compact('applicants'));
    }

    public function approvedLoans()
    {
        $applicants = Applicant::where('loan_status', 'APPROVED')->orderByDesc('reg_date')->paginate(10);

        return view('admin.approved-loans', compact('applicants'));
    }

    public function monthlyReport(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $disbursed = Applicant::query();
        $payments = Payment::query();

        if ($from && $to) {
            $disbursed->whereBetween('reg_date', [$from, $to]);
            $payments->whereBetween('date', [$from, $to]);
        }

        $disbursedLoans = $disbursed->where('loan_status', 'APPROVED')->get();
        $paymentRecords = $payments->get();

        return view('admin.monthly-report', [
            'disbursedLoans' => $disbursedLoans,
            'paymentRecords' => $paymentRecords,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function updateCollateralForm(Request $request)
    {
        if (! session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'collateral_form' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $upload = $validated['collateral_form'];
        $path = $upload->store('loan-form-documents', 'local');

        if (! $path) {
            return back()->withErrors(['collateral_form' => 'The collateral form could not be saved. Please try again.']);
        }

        $document = LoanFormDocument::query()->first();
        $previousPath = $document?->path;

        if ($document) {
            $document->update([
                'path' => $path,
                'original_name' => $upload->getClientOriginalName(),
            ]);
        } else {
            LoanFormDocument::create([
                'path' => $path,
                'original_name' => $upload->getClientOriginalName(),
            ]);
        }

        if ($previousPath && $previousPath !== $path) {
            $previousDisk = Storage::disk('local')->exists($previousPath) ? 'local' : 'public';
            Storage::disk($previousDisk)->delete($previousPath);
        }

        return redirect()->route('admin.dashboard')->with('success', 'The downloadable collateral form has been updated.');
    }
}
