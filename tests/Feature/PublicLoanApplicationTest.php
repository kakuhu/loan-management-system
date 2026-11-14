<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\LoanFormDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicLoanApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_loan_calculator_shows_customer_type_rates_and_week_based_terms(): void
    {
        $this->get(route('loan.calculator'))
            ->assertOk()
            ->assertSee('name="customerType"', false)
            ->assertSee('value="marketeers"', false)
            ->assertSee('value="civil"', false)
            ->assertSee('5 weeks')
            ->assertSee('1 week')
            ->assertSee('2 weeks')
            ->assertSee('3 weeks')
            ->assertSee('4 weeks (1 month)')
            ->assertSee('32%')
            ->assertSee('10%')
            ->assertSee('15%')
            ->assertSee('25%')
            ->assertSee('30%')
            ->assertSee('Admin fee (2.5%)')
            ->assertSee('The admin fee is deducted from the payout.')
            ->assertSee('const interest = amount * rate / 100;', false);
    }

    public function test_admin_can_upload_a_collateral_form_for_public_download(): void
    {
        Storage::fake('local');

        $response = $this->withSession(['admin_id' => 1])->post(route('admin.collateral_form.update'), [
            'collateral_form' => UploadedFile::fake()->create('collateral-form.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $document = LoanFormDocument::query()->firstOrFail();
        Storage::disk('local')->assertExists($document->path);

        $this->get(route('loan.apply'))
            ->assertOk()
            ->assertSee(route('loan.collateral-form.download'))
            ->assertSee('Completed Collateral Form');

        $this->get(route('loan.collateral-form.download'))
            ->assertOk()
            ->assertDownload('collateral-form.pdf');
    }

    public function test_public_form_shows_and_downloads_a_collateral_form_from_public_storage(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        Storage::disk('public')->put('loan-form-documents/collateral-form.pdf', 'form contents');

        LoanFormDocument::create([
            'path' => 'loan-form-documents/collateral-form.pdf',
            'original_name' => 'collateral-form.pdf',
        ]);

        $this->get(route('loan.apply'))
            ->assertOk()
            ->assertSee(route('loan.collateral-form.download'))
            ->assertSee('Download the collateral consent form');

        $this->get(route('loan.collateral-form.download'))
            ->assertOk()
            ->assertDownload('collateral-form.pdf');
    }

    public function test_public_application_saves_details_and_private_supporting_documents(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('loan-form-documents/collateral-form.pdf', 'form contents');
        LoanFormDocument::create([
            'path' => 'loan-form-documents/collateral-form.pdf',
            'original_name' => 'collateral-form.pdf',
        ]);

        $response = $this->post(route('loan.store'), [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'nrc' => '123456/78/9',
            'contacts' => '+260977000000',
            'residential_address' => '1 Main Street',
            'city' => 'Lusaka',
            'state' => 'Lusaka Province',
            'country' => 'Zambia',
            'next_of_kin_first_name' => 'John',
            'next_of_kin_last_name' => 'Doe',
            'next_of_kin_phone' => '+260977000001',
            'next_of_kin_relationship' => 'Sibling',
            'employer_name' => 'Example Employer',
            'job_title' => 'Analyst',
            'employee_number' => 'EMP-12',
            'monthly_net_salary' => '10000',
            'loanamount' => '5000',
            'collateral_description' => 'Laptop, current value K8,000',
            'nrc_document' => UploadedFile::fake()->create('nrc.pdf', 100, 'application/pdf'),
            'collateral_document' => UploadedFile::fake()->create('collateral.pdf', 100, 'application/pdf'),
            'recent_payslip' => UploadedFile::fake()->create('payslip.pdf', 100, 'application/pdf'),
            'proof_of_address' => UploadedFile::fake()->create('address.pdf', 100, 'application/pdf'),
            'bank_statement' => UploadedFile::fake()->create('statement.pdf', 100, 'application/pdf'),
            'signed_form' => UploadedFile::fake()->create(
                'signed-collateral-form.docx',
                100,
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ),
            'declaration' => '1',
        ]);

        $response->assertRedirect(route('landing'));
        $applicant = Applicant::query()->where('email', 'jane@example.com')->firstOrFail();

        $this->assertSame('Jane Doe', $applicant->name);
        $this->assertSame('web', $applicant->source);
        $this->assertSame(1, $applicant->loan_duration);
        $this->assertSame(30.0, (float) $applicant->interest_rate);
        $this->assertSame(100.0, (float) $applicant->loan_processing_fee);
        $this->assertSame('Lusaka', $applicant->public_application_details['city']);
        $this->assertTrue($applicant->public_application_details['declaration_accepted']);

        foreach (['nrc_document', 'collateral_document', 'recent_payslip', 'proof_of_address', 'bank_statement', 'signed_form'] as $field) {
            Storage::disk('local')->assertExists($applicant->{$field});
        }

        $this->get(route('applicant.document.download', [$applicant, 'nrc_document']))
            ->assertForbidden();

        $this->withSession(['staff_role' => 'credit_officer'])
            ->get(route('applicant.document.download', [$applicant, 'nrc_document']))
            ->assertOk()
            ->assertDownload();
    }
}
