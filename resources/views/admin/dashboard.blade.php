@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Admin Dashboard</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Logout</button>
        </form>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <h5>Total Applicants</h5>
                <p class="display-6 mb-0">{{ $totalApplicants }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <h5>Approved Loans</h5>
                <p class="display-6 mb-0">{{ $approvedLoans }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100">
                <h5>Disbursed</h5>
                <p class="display-6 mb-0">K{{ number_format($totalDisbursed, 2) }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 h-100 text-center">
                <a href="{{ route('admin.applicants') }}" class="btn btn-primary w-100 mb-2">Recent Applicants</a>
                <a href="{{ route('admin.approved_loans') }}" class="btn btn-success w-100 mb-2">Approved Loans</a>
                <a href="{{ route('admin.monthly_report') }}" class="btn btn-warning w-100">Monthly Report</a>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h4>Public Collateral Form</h4>
        <p class="text-muted">Upload a PDF or Word document for applicants to download, complete, and upload with their application. Uploading another file replaces the current form.</p>
        @if($collateralFormDocument)
            <p class="mb-3">
                Current form:
                <a href="{{ route('loan.collateral-form.download') }}">{{ $collateralFormDocument->original_name }}</a>
            </p>
        @else
            <p class="mb-3">No collateral form has been uploaded yet.</p>
        @endif
        <form method="POST" action="{{ route('admin.collateral_form.update') }}" enctype="multipart/form-data">
            @csrf
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
                <p class="display-6 mb-0">{{ $loanOfficers }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h5>Credit Officers</h5>
                <p class="display-6 mb-0">{{ $creditOfficers }}</p>
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
                    @forelse($recentApplicants as $applicant)
                        <tr>
                            <td>{{ $applicant->name }}</td>
                            <td>{{ $applicant->email }}</td>
                            <td>K{{ number_format($applicant->loan_amount, 2) }}</td>
                            <td>{{ $applicant->loan_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No recent applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
