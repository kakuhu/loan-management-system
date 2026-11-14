@extends('layouts.app')

@php $showPublicNavbar = true; @endphp

@section('title', 'Apply for a Loan')

@section('content')
<style>
    .application-card { border: 0; border-radius: 1.25rem; box-shadow: 0 1rem 3rem rgba(15, 23, 42, .12); }
    .application-section { margin-top: 2rem; }
    .application-section-title { background: #0d3b66; color: #fff; padding: .85rem 1rem; text-align: center; border-radius: .25rem; font-size: 1.1rem; }
    .application-card .form-control, .application-card .form-select { min-height: 48px; }
    .upload-box { display: block; padding: 1.25rem; border: 1px dashed #8290a1; border-radius: .5rem; background: #f8fafc; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card application-card p-4 p-md-5">
                <div class="mb-4">
                    <h1 class="h2 mb-2">Loan Application</h1>
                    <p class="text-muted mb-0">Complete each section and upload the supporting documents to submit your application.</p>
                </div>

                @if($collateralFormDocument)
                    <div class="alert alert-info">
                        <strong>Before you apply:</strong>
                        <a class="fw-semibold" href="{{ route('loan.collateral-form.download') }}">Download the collateral consent form</a>,
                        complete and sign it, then upload it with your supporting documents below.
                    </div>
                @endif

                <div class="alert alert-warning small">
                    <strong>Please note:</strong> Loans are for one month and are payable at month end. The interest rate is 30% per month and the processing fee is K100.
                </div>

                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <strong>Please correct the following:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('loan.store') }}" enctype="multipart/form-data">
                    @csrf

                    <section class="application-section mt-0">
                        <h2 class="application-section-title">01. Applicant Details</h2>
                        <div class="row g-3 mt-1">
                            <div class="col-12"><span class="form-label">Applicant Name <span class="text-danger">*</span></span></div>
                            <div class="col-md-6">
                                <label class="form-label" for="first_name">First Name</label>
                                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="email">Email Address <span class="text-danger">*</span></label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="contacts">Phone Number <span class="text-danger">*</span></label>
                                <input id="contacts" type="tel" name="contacts" value="{{ old('contacts') }}" class="form-control" placeholder="+260 ..." required>
                            </div>
                            <div class="col-12"><span class="form-label">Residential Address <span class="text-danger">*</span></span></div>
                            <div class="col-12">
                                <label class="form-label" for="residential_address">Street Address</label>
                                <input id="residential_address" type="text" name="residential_address" value="{{ old('residential_address') }}" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="residential_address_line_2">Street Address Line 2</label>
                                <input id="residential_address_line_2" type="text" name="residential_address_line_2" value="{{ old('residential_address_line_2') }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="city">City <span class="text-danger">*</span></label>
                                <input id="city" type="text" name="city" value="{{ old('city') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="state">State / Province <span class="text-danger">*</span></label>
                                <input id="state" type="text" name="state" value="{{ old('state') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="zip_code">Zip Code</label>
                                <input id="zip_code" type="text" name="zip_code" value="{{ old('zip_code') }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="country">Country <span class="text-danger">*</span></label>
                                <input id="country" type="text" name="country" value="{{ old('country', 'Zambia') }}" class="form-control" required>
                            </div>
                            <div class="col-12"><span class="form-label">Next of Kin <span class="text-danger">*</span></span></div>
                            <div class="col-md-6">
                                <label class="form-label" for="next_of_kin_first_name">First Name</label>
                                <input id="next_of_kin_first_name" type="text" name="next_of_kin_first_name" value="{{ old('next_of_kin_first_name') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="next_of_kin_last_name">Last Name</label>
                                <input id="next_of_kin_last_name" type="text" name="next_of_kin_last_name" value="{{ old('next_of_kin_last_name') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="next_of_kin_phone">Phone Number</label>
                                <input id="next_of_kin_phone" type="tel" name="next_of_kin_phone" value="{{ old('next_of_kin_phone') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="next_of_kin_relationship">Relationship</label>
                                <select id="next_of_kin_relationship" name="next_of_kin_relationship" class="form-select" required>
                                    <option value="">Please select</option>
                                    @foreach(['Spouse', 'Parent', 'Sibling', 'Child', 'Relative', 'Friend', 'Other'] as $relationship)
                                        <option value="{{ $relationship }}" @selected(old('next_of_kin_relationship') === $relationship)>{{ $relationship }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </section>

                    <section class="application-section">
                        <h2 class="application-section-title">02. Employment Details</h2>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label" for="employer_name">Employer Name <span class="text-danger">*</span></label>
                                <input id="employer_name" type="text" name="employer_name" value="{{ old('employer_name') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="job_title">Job Title <span class="text-danger">*</span></label>
                                <input id="job_title" type="text" name="job_title" value="{{ old('job_title') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="employee_number">Employee Number <span class="text-danger">*</span></label>
                                <input id="employee_number" type="text" name="employee_number" value="{{ old('employee_number') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="monthly_net_salary">Monthly Net Salary (K) <span class="text-danger">*</span></label>
                                <input id="monthly_net_salary" type="number" step="0.01" min="0" name="monthly_net_salary" value="{{ old('monthly_net_salary') }}" class="form-control" required>
                            </div>
                        </div>
                    </section>

                    <section class="application-section">
                        <h2 class="application-section-title">03. Loan Details</h2>
                        <div class="row g-3 mt-1">
                            <div class="col-md-8">
                                <label class="form-label" for="loanamount">Requested Advance Amount (K) <span class="text-danger">*</span></label>
                                <input id="loanamount" type="number" step="0.01" min="1" name="loanamount" value="{{ old('loanamount') }}" class="form-control" required>
                                <div class="form-text">30% interest rate per month. Loan duration is one month.</div>
                            </div>
                        </div>
                    </section>

                    <section class="application-section">
                        <h2 class="application-section-title">04. Supporting Documents &amp; Collateral</h2>
                        <div class="row g-3 mt-1">
                            <div class="col-12">
                                <label class="form-label" for="nrc">NRC Number <span class="text-danger">*</span></label>
                                <input id="nrc" type="text" name="nrc" value="{{ old('nrc') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="nrc_document">NRC (front and back) <span class="text-danger">*</span></label>
                                <input id="nrc_document" type="file" name="nrc_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                                <div class="form-text">Upload a clear image or PDF, up to 5 MB.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="collateral_document">Collateral Document / Photo <span class="text-danger">*</span></label>
                                <input id="collateral_document" type="file" name="collateral_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                                <div class="form-text">Upload supporting proof of the collateral.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="collateral_description">Collateral Description <span class="text-danger">*</span></label>
                                <textarea id="collateral_description" name="collateral_description" rows="2" class="form-control" placeholder="e.g. Item and current market value" required>{{ old('collateral_description') }}</textarea>
                            </div>
                            @if($collateralFormDocument)
                                <div class="col-12">
                                    <div class="alert alert-info mb-0">
                                        <strong>Collateral form:</strong>
                                        Download the form above, fill it in, then upload the completed copy below.
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="signed_form">Completed Collateral Form <span class="text-danger">*</span></label>
                                    <input id="signed_form" type="file" name="signed_form" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" required>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <label class="form-label" for="recent_payslip">Recent Payslip <span class="text-danger">*</span></label>
                                <input id="recent_payslip" type="file" name="recent_payslip" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="proof_of_address">Proof of Residential Address <span class="text-danger">*</span></label>
                                <input id="proof_of_address" type="file" name="proof_of_address" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="bank_statement">Bank Statement <span class="text-danger">*</span></label>
                                <input id="bank_statement" type="file" name="bank_statement" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>
                        </div>
                    </section>

                    <section class="application-section">
                        <h2 class="application-section-title">05. Declaration</h2>
                        <div class="form-check mt-4">
                            <input id="declaration" type="checkbox" name="declaration" value="1" class="form-check-input" required>
                            <label for="declaration" class="form-check-label">
                                I confirm that the information provided is true and complete. I authorise employment and salary verification with my employer, credit checks, and salary deductions where applicable. I also acknowledge and agree to pay a K100 processing fee. <span class="text-danger">*</span>
                            </label>
                        </div>
                    </section>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success btn-lg px-5">Submit Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
