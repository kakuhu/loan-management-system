@extends('layouts.app')

@section('title', 'Field Agent Dashboard')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Loan Officer Dashboard</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Logout</button>
        </form>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('field.agent.applications.create') }}" class="btn btn-primary">Create client application</a>
    </div>

    <div class="card p-4 mb-4">
        <h4>Applications submitted by loan officers</h4>
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
                    @forelse($applicants as $applicant)
                        <tr>
                            <td>{{ $applicant->name }}</td>
                            <td>{{ $applicant->email }}</td>
                            <td>K{{ number_format($applicant->loan_amount, 2) }}</td>
                            <td>{{ $applicant->loan_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No field applications yet.</td>
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
                                @if($applicant->review_notes)
                                    {{ $applicant->review_notes }}
                                @else
                                    <span class="text-muted">No notes</span>
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
