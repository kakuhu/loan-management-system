@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <h2>Welcome, {{ $userName }}</h2>
                <p class="lead">You have a loan amount of: K{{ number_format($loanAmount, 2) }}</p>

                <h4 class="mt-4">Loan repayment calculator</h4>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <input type="number" id="interest" class="form-control" placeholder="Interest %">
                    </div>
                    <div class="col-md-6">
                        <input type="number" id="amount" class="form-control" placeholder="Amount">
                    </div>
                </div>
                <button class="btn btn-primary mt-3" onclick="displayRepayment()">Display Amount</button>
                <div id="printResult" class="mt-3 fw-bold"></div>
            </div>
        </div>
    </div>
</div>

<script>
function displayRepayment() {
    const interest = parseFloat(document.getElementById('interest').value || 0);
    const amount = parseFloat(document.getElementById('amount').value || 0);
    const total = (interest / 100 * amount) + amount;
    document.getElementById('printResult').innerHTML = 'DEAR ESTEEMED CUSTOMER THANK YOU FOR LOANING WITH US! YOU ARE EXPECTED TO PAY BACK K' + total.toFixed(2);
}
</script>
@endsection
