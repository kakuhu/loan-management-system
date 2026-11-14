@extends('layouts.app')

@section('title', 'Monthly Report')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Monthly Report</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to dashboard</a>
    </div>

    <div class="card p-4 mb-4">
        <form method="POST" action="{{ route('admin.monthly_report.filter') }}">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">From</label>
                    <input type="date" name="from" class="form-control" value="{{ $from ?? '' }}">
                </div>
                <div class="col-md-5">
                    <label class="form-label">To</label>
                    <input type="date" name="to" class="form-control" value="{{ $to ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Get Data</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <h4 class="mb-3">Disbursed Amounts</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($disbursedLoans as $loan)
                    <tr>
                        <td>{{ $loan->name }}</td>
                        <td>K{{ number_format($loan->loan_amount, 2) }}</td>
                        <td>{{ $loan->reg_date }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No disbursed amounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h4 class="mt-5 mb-3">Payments</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paymentRecords as $payment)
                    <tr>
                        <td>{{ $payment->customer_name ?? 'Customer' }}</td>
                        <td>K{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->date }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No payment records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
