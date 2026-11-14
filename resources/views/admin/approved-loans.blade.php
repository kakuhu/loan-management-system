@extends('layouts.app')

@section('title', 'Approved Loans')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Approved Loans</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to dashboard</a>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
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
                            <td>K{{ number_format($applicant->loan_amount, 2) }}</td>
                            <td>{{ $applicant->loan_duration }}</td>
                            <td>{{ $applicant->loan_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No approved loans found.</td>
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
