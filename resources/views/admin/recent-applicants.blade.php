@extends('layouts.app')

@section('title', 'Recent Applicants')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Recent Applicants</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to dashboard</a>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contacts</th>
                        <th>Loan Amount</th>
                        <th>Duration</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applicants as $applicant)
                        <tr>
                            <td>{{ $applicant->name }}</td>
                            <td>{{ $applicant->email }}</td>
                            <td>{{ $applicant->contacts }}</td>
                            <td>K{{ number_format($applicant->loan_amount, 2) }}</td>
                            <td>{{ $applicant->loan_duration }}</td>
                            <td>
                                <div><strong>Status:</strong> {{ $applicant->loan_status }}</div>
                                <div class="mt-2"><strong>Recommendation:</strong> {{ $applicant->credit_recommendation ?? 'Not yet reviewed' }}</div>
                                <div class="mt-2"><strong>Comments:</strong> {{ $applicant->review_notes ?? 'No comments yet' }}</div>

                                @include('partials.applicant-documents', ['applicant' => $applicant])

                                <form method="POST" action="{{ route('admin.application.decision', $applicant) }}" class="mt-3">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label small mb-1">Decision notes</label>
                                        <textarea name="review_notes" class="form-control form-control-sm" rows="2" placeholder="Add reason for rejection or approval notes"></textarea>
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <select name="decision" class="form-select" required>
                                            <option value="APPROVED">Approve</option>
                                            <option value="REJECTED">Reject</option>
                                        </select>
                                        <button type="submit" class="btn btn-dark">Save</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No applicants found.</td>
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
</div>
@endsection
