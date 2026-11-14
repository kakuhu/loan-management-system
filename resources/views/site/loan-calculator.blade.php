@extends('layouts.app')

@php $showPublicNavbar = true; @endphp

@section('title', 'Loan Calculator')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm p-4 p-md-5">
                <h1 class="h2 mb-2">Loan Calculator</h1>
                <p class="text-muted mb-4">Estimate your payout and repayment based on your customer type and loan term.</p>

                <form id="loanCalculatorForm">
                    <div class="mb-4">
                        <fieldset>
                            <legend class="form-label fw-semibold">Customer type</legend>
                            <div class="d-flex flex-wrap gap-3">
                                <label class="border rounded p-3 flex-fill">
                                    <input class="form-check-input me-2" type="radio" name="customerType" value="marketeers" required>
                                    Marketeers
                                    <span class="d-block small text-muted ms-4">32% for 5 weeks</span>
                                </label>
                                <label class="border rounded p-3 flex-fill">
                                    <input class="form-check-input me-2" type="radio" name="customerType" value="civil">
                                    Civil servants
                                    <span class="d-block small text-muted ms-4">Rates depend on the selected term</span>
                                </label>
                            </div>
                        </fieldset>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="loanAmount" class="form-label">Loan amount (K)</label>
                            <input type="number" id="loanAmount" class="form-control" min="1" step="0.01" placeholder="Enter loan amount" required>
                        </div>
                        <div class="col-md-6" id="loanDurationGroup" hidden>
                            <label for="loanDuration" class="form-label">Loan term</label>
                            <select id="loanDuration" class="form-select" required disabled>
                                <option value="">Choose a term</option>
                            </select>
                        </div>
                    </div>

                    <p class="mt-3 mb-0"><strong>Interest rate:</strong> <span id="interestRate">Choose a customer type and term</span></p>
                    <button type="submit" class="btn btn-primary mt-3">Calculate</button>
                </form>

                <div id="loanResults" class="mt-4 border-top pt-4" style="display:none;" aria-live="polite">
                    <h2 class="h5 mb-3">Estimated loan summary</h2>
                    <div class="row g-3">
                        <div class="col-sm-6"><strong>Loan amount:</strong> <span id="principalAmount"></span></div>
                        <div class="col-sm-6"><strong>Interest:</strong> <span id="totalInterest"></span></div>
                        <div class="col-sm-6"><strong>Admin fee (2.5%):</strong> <span id="adminFee"></span></div>
                        <div class="col-sm-6"><strong>Payout after fee:</strong> <span id="netPayout"></span></div>
                        <div class="col-sm-6"><strong>Total repayment:</strong> <span id="totalRepayment"></span></div>
                        <div class="col-sm-6"><strong>Weekly repayment:</strong> <span id="weeklyRepayment"></span></div>
                    </div>
                    <p class="small text-muted mt-3 mb-0">Interest is calculated once on the loan amount for the selected term. The admin fee is deducted from the payout.</p>
                </div>

                <div class="mt-5">
                    <h2 class="h5">Rate guide</h2>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr><th>Customer type</th><th>Term</th><th>Interest rate</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Marketeers</td><td>5 weeks</td><td>32%</td></tr>
                                <tr><td>Civil servants</td><td>1 week</td><td>10%</td></tr>
                                <tr><td>Civil servants</td><td>2 weeks</td><td>15%</td></tr>
                                <tr><td>Civil servants</td><td>3 weeks</td><td>25%</td></tr>
                                <tr><td>Civil servants</td><td>4 weeks (1 month)</td><td>30%</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="small text-muted mt-2 mb-0">A 2.5% admin fee is deducted from the loan payout.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    const form = document.getElementById('loanCalculatorForm');
    const amountInput = document.getElementById('loanAmount');
    const durationGroup = document.getElementById('loanDurationGroup');
    const durationSelect = document.getElementById('loanDuration');
    const interestRate = document.getElementById('interestRate');
    const results = document.getElementById('loanResults');
    const terms = {
        marketeers: [{ weeks: 5, label: '5 weeks', rate: 32 }],
        civil: [
            { weeks: 1, label: '1 week', rate: 10 },
            { weeks: 2, label: '2 weeks', rate: 15 },
            { weeks: 3, label: '3 weeks', rate: 25 },
            { weeks: 4, label: '4 weeks (1 month)', rate: 30 }
        ]
    };

    const hideResults = () => {
        results.style.display = 'none';
    };

    const selectedType = () => form.querySelector('input[name="customerType"]:checked')?.value;

    const updateTerms = () => {
        const type = selectedType();
        durationSelect.replaceChildren(new Option('Choose a term', ''));
        hideResults();

        if (!type) {
            durationSelect.disabled = true;
            durationGroup.hidden = true;
            interestRate.textContent = 'Choose a customer type and term';
            return;
        }

        terms[type].forEach((term) => {
            const option = new Option(term.label, String(term.weeks));
            option.dataset.rate = String(term.rate);
            durationSelect.add(option);
        });

        durationGroup.hidden = false;
        durationSelect.disabled = false;
        durationSelect.value = String(terms[type][0].weeks);
        updateInterestRate();
    };

    const updateInterestRate = () => {
        const option = durationSelect.selectedOptions[0];
        interestRate.textContent = option?.dataset.rate
            ? `${option.dataset.rate}%`
            : 'Choose a customer type and term';
        hideResults();
    };

    const formatAmount = (amount) => `K${amount.toLocaleString('en', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })}`;

    form.querySelectorAll('input[name="customerType"]').forEach((input) => {
        input.addEventListener('change', updateTerms);
    });
    durationSelect.addEventListener('change', updateInterestRate);
    amountInput.addEventListener('input', hideResults);

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const amount = Number(amountInput.value);
        const selectedTerm = durationSelect.selectedOptions[0];
        const weeks = Number(durationSelect.value);
        const rate = Number(selectedTerm?.dataset.rate);

        if (!Number.isFinite(amount) || amount <= 0 || !Number.isFinite(rate) || weeks <= 0) {
            form.reportValidity();
            return;
        }

        const interest = amount * rate / 100;
        const fee = amount * 0.025;
        const repayment = amount + interest;

        document.getElementById('principalAmount').textContent = formatAmount(amount);
        document.getElementById('totalInterest').textContent = `${formatAmount(interest)} (${rate}%)`;
        document.getElementById('adminFee').textContent = formatAmount(fee);
        document.getElementById('netPayout').textContent = formatAmount(amount - fee);
        document.getElementById('totalRepayment').textContent = formatAmount(repayment);
        document.getElementById('weeklyRepayment').textContent = formatAmount(repayment / weeks);
        results.style.display = 'block';
    });
})();
</script>
@endsection
