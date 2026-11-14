@extends('layouts.app')

@section('title', 'Credit Officer Dashboard')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Credit Officer Dashboard</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Logout</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-4 mb-4">
        <h4>Applications awaiting review</h4>
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Amount</th>
                        <th>Attachment</th>
                        <th>Recommendation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applicants as $applicant)
                        <tr>
                            <td>{{ $applicant->name }}</td>
                            <td>{{ $applicant->email }}</td>
                            <td>K{{ number_format($applicant->loan_amount, 2) }}</td>
                            <td>
                                @include('partials.applicant-documents', ['applicant' => $applicant])

                                @if($applicant->credit_recommendation || $applicant->review_notes)
                                    <div class="small mt-2">
                                        @if($applicant->credit_recommendation)
                                            <div><strong>Recommendation:</strong> {{ $applicant->credit_recommendation }}</div>
                                        @endif
                                        @if($applicant->review_notes)
                                            <div><strong>Comments:</strong> {{ $applicant->review_notes }}</div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if(in_array($applicant->loan_status, ['PENDING_REVIEW', 'RECOMMENDED']))
                                    <form method="POST" action="{{ route('credit.officer.recommend', $applicant) }}">
                                        @csrf
                                        <div class="mb-2">
                                            <select name="decision" class="form-select form-select-sm" required>
                                                <option value="RECOMMEND">Recommend</option>
                                                <option value="REJECT">Reject</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <textarea name="review_notes" class="form-control form-control-sm" rows="2" placeholder="Notes"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                    </form>
                                @elseif($applicant->loan_status === 'APPROVED')
                                    <form method="POST" action="{{ route('credit.officer.close', $applicant) }}">
                                        @csrf
                                        <div class="mb-2">
                                            <textarea name="review_notes" class="form-control form-control-sm" rows="2" placeholder="Closing note"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-warning">Mark fully repaid</button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary">{{ $applicant->loan_status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No applications to review.</td>
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
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Decision Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviewedApplicants as $applicant)
                        <tr>
                            <td>{{ $applicant->name }}</td>
                            <td>{{ $applicant->email }}</td>
                            <td>K{{ number_format($applicant->loan_amount, 2) }}</td>
                            <td>
                                <span class="badge {{ $applicant->loan_status === 'FULLY_REPAID' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $applicant->loan_status }}
                                </span>
                            </td>
                            <td>
                                @if($applicant->credit_recommendation)
                                    {{ $applicant->credit_recommendation }}
                                @endif
                                @if($applicant->review_notes)
                                    <div class="small text-muted mt-1">{{ $applicant->review_notes }}</div>
                                @endif
                            </td>
                            <td>
                                @if($applicant->loan_status === 'APPROVED')
                                    <form method="POST" action="{{ route('credit.officer.close', $applicant) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning">Close loan</button>
                                    </form>
                                @elseif($applicant->loan_status === 'FULLY_REPAID')
                                    <span class="text-success small fw-bold">Closed</span>
                                @else
                                    <span class="text-muted small">-</span>
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
