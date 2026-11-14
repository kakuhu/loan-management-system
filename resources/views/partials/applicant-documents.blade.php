@php
    $applicationDocuments = [
        'nrc_document' => 'NRC',
        'collateral_document' => 'Collateral document',
        'recent_payslip' => 'Recent payslip',
        'proof_of_address' => 'Proof of address',
        'bank_statement' => 'Bank statement',
        'signed_form' => 'Completed collateral form',
    ];
@endphp

@foreach($applicationDocuments as $field => $label)
    @if($applicant->{$field} && ($field !== 'signed_form' || \Illuminate\Support\Str::startsWith($applicant->signed_form, 'applicant-documents/')))
        <div class="mt-2">
            <a href="{{ route('applicant.document.download', [$applicant, $field]) }}" class="btn btn-sm btn-outline-primary">{{ $label }}</a>
        </div>
    @endif
@endforeach

@php
    $legacyFiles = array_filter([
        $applicant->attachment ?? null,
        $applicant->image ?? null,
        $applicant->signed_form && !\Illuminate\Support\Str::startsWith($applicant->signed_form, 'applicant-documents/')
            ? $applicant->signed_form
            : null,
    ]);
@endphp
@foreach($legacyFiles as $file)
    @php
        $documentPath = ltrim($file, '/');
        $documentUrl = file_exists(public_path($documentPath))
            ? asset($documentPath)
            : \Illuminate\Support\Facades\Storage::disk('public')->url($file);
    @endphp
    <div class="mt-2">
        <a href="{{ $documentUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">View file</a>
    </div>
@endforeach

@if(!$applicant->attachment && !$applicant->image && !$applicant->nrc_document && !$applicant->collateral_document && !$applicant->recent_payslip && !$applicant->proof_of_address && !$applicant->bank_statement && !$applicant->signed_form)
    <div class="mt-2 text-muted small">No file uploaded</div>
@endif

@if($applicant->public_application_details)
    <details class="mt-3">
        <summary class="small fw-semibold">View applicant, employment and collateral details</summary>
        <dl class="row small mt-2 mb-0">
            @foreach([
                'residential_address' => 'Street address',
                'residential_address_line_2' => 'Address line 2',
                'city' => 'City',
                'state' => 'State / Province',
                'zip_code' => 'Zip code',
                'country' => 'Country',
                'next_of_kin_first_name' => 'Next of kin first name',
                'next_of_kin_last_name' => 'Next of kin last name',
                'next_of_kin_phone' => 'Next of kin phone',
                'next_of_kin_relationship' => 'Next of kin relationship',
                'employer_name' => 'Employer',
                'job_title' => 'Job title',
                'employee_number' => 'Employee number',
                'monthly_net_salary' => 'Monthly net salary (K)',
                'collateral_description' => 'Collateral description',
            ] as $field => $label)
                @if(!empty($applicant->public_application_details[$field]))
                    <dt class="col-sm-5">{{ $label }}</dt>
                    <dd class="col-sm-7">{{ $applicant->public_application_details[$field] }}</dd>
                @endif
            @endforeach
        </dl>
    </details>
@endif
