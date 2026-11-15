@extends('layouts.app')

@section('title', 'Create Client Application')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card border-0 shadow-lg p-4 p-md-5" style="border-radius: 1.5rem; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <div>
                        <p class="text-uppercase text-primary fw-bold mb-1" style="letter-spacing: 0.12em; font-size: 0.75rem;">Client onboarding</p>
                        <h2 class="mb-0 fw-bold">Loan Application Form</h2>
                    </div>
                    <a href="{{ session('staff_role') === 'field_agent' ? route('field.agent.dashboard') : route('loan.officer.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">Back</a>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger rounded-4">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ session('staff_role') === 'field_agent' ? route('field.agent.applications.store') : route('loan.officer.applications.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="source" value="{{ session('staff_role') === 'field_agent' ? 'field_agent' : 'loan_officer' }}">

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Personal Details</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Client Full Name</label>
                                <input type="text" name="name" class="form-control form-control-lg rounded-4" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date of birth</label>
                                <input type="date" name="date_of_birth" class="form-control form-control-lg rounded-4">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg rounded-4" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NRC</label>
                                <input type="text" name="nrc" class="form-control form-control-lg rounded-4" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <input type="text" name="contacts" class="form-control form-control-lg rounded-4" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cell Number</label>
                                <input type="text" name="cell_number" class="form-control form-control-lg rounded-4">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">GPS coordinates &amp; location</label>
                                <input type="text" name="gps_coordinates" class="form-control form-control-lg rounded-4" placeholder="e.g. -15.3875, 28.3228" aria-label="GPS coordinates">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Location</label>
                                <input type="text" name="location" class="form-control form-control-lg rounded-4" placeholder="Town/Area/Province">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Marital status</label>
                                <select name="marital_status" class="form-select form-select-lg rounded-4">
                                    <option value="">Select status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Spouse Name</label>
                                <input type="text" name="spouse_name" class="form-control form-control-lg rounded-4">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Business information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Type of Business</label>
                                <input type="text" name="business_type" class="form-control form-control-lg rounded-4">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Years of experience</label>
                                <input type="number" name="business_experience_years" class="form-control form-control-lg rounded-4" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stock of item sold</label>
                                <input type="text" name="stock_of_item_sold" class="form-control form-control-lg rounded-4">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Average daily sales</label>
                                <input type="number" step="0.01" name="average_daily_sales" class="form-control form-control-lg rounded-4">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Business Address</label>
                                <textarea name="business_address" rows="3" class="form-control rounded-4"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Loan Details</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Amount Applied For ZMW</label>
                                <input type="number" step="0.01" name="loanamount" class="form-control form-control-lg rounded-4" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Interest Rate</label>
                                <input type="number" step="0.01" name="interest_rate" class="form-control form-control-lg rounded-4">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Interest Amount</label>
                                <input type="number" step="0.01" name="interest_amount" class="form-control form-control-lg rounded-4">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Loan Processing Fee</label>
                                <input type="number" step="0.01" name="loan_processing_fee" class="form-control form-control-lg rounded-4">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Total Amount Repayable</label>
                                <input type="number" step="0.01" name="total_amount_repayable" class="form-control form-control-lg rounded-4">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Loan Tenure (Duration)</label>
                                <input type="number" name="loanduration" class="form-control form-control-lg rounded-4" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Attachments</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Profile Pic</label>
                                <input type="file" name="image" class="form-control form-control-lg rounded-4" accept=".jpg,.jpeg,.png">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Collateral Document</label>
                                <input type="file" name="attachment" class="form-control form-control-lg rounded-4" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Signed form</label>
                                <input type="file" name="signed_form" class="form-control form-control-lg rounded-4" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Submit to Credit Officer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
