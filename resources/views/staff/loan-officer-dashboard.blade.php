@extends('layouts.app')

@section('title', 'Loan Officer Dashboard')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Loan Officer Dashboard</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Logout</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('loan.officer.applications.create') }}" class="btn btn-primary">Create client application</a>
    </div>

    <div class="card p-4 mb-4">
        <h4>Applications for field collection</h4>
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Loan Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applicants as $applicant)
                        <tr>
                            <td>{{ $applicant->name }}</td>
                            <td>{{ $applicant->email }}</td>
                            <td>K{{ number_format($applicant->loan_amount, 2) }}</td>
                            <td>
                                <div>{{ $applicant->loan_status }}</div>
                                @if($applicant->credit_recommendation || $applicant->review_notes)
                                    <small class="text-muted d-block mt-1">
                                        @if($applicant->credit_recommendation)
                                            Recommendation: {{ $applicant->credit_recommendation }}
                                        @endif
                                        @if($applicant->review_notes)
                                            <br>Notes: {{ $applicant->review_notes }}
                                        @endif
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if(in_array($applicant->loan_status, ['PENDING_REVIEW', 'SUBMITTED']))
                                    <form method="POST" action="{{ route('loan.officer.submit', $applicant) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Send to credit officer</button>
                                    </form>
                                @else
                                    <span class="badge bg-info text-dark">Decision received</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No applications available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($applicants->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $applicants->links() }}
            </div>
        @endif
    </div>

    <div class="card p-4">
        <h4>Reviewed Applications</h4>
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Loan Amount</th>
                        <th>Status</th>
                        <th>Recommendation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviewedApplicants as $applicant)
                        <tr>
                            <td>{{ $applicant->name }}</td>
                            <td>{{ $applicant->email }}</td>
                            <td>K{{ number_format($applicant->loan_amount, 2) }}</td>
                            <td>{{ $applicant->loan_status }}</td>
                            <td>
                                @if($applicant->credit_recommendation)
                                    {{ $applicant->credit_recommendation }}
                                @else
                                    <span class="text-muted">No recommendation</span>
                                @endif
                                @if($applicant->review_notes)
                                    <div class="small text-muted mt-1">{{ $applicant->review_notes }}</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No reviewed applications yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviewedApplicants->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $reviewedApplicants->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
