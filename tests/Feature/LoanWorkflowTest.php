<?php

namespace Tests\Feature;

use App\Mail\LoanApprovedMail;
use App\Models\Admin;
use App\Models\Applicant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LoanWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_loan_officer_can_submit_stock_of_item_sold_as_text(): void
    {
        $response = $this->withSession([
            'staff_id' => 1,
            'staff_role' => 'loan_officer',
        ])->post(route('loan.officer.applications.store'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'nrc' => '123456/78/9',
            'contacts' => '0977000000',
            'stock_of_item_sold' => '10 bags of maize',
            'loanamount' => '5000',
            'loanduration' => '12',
        ]);

        $response->assertRedirect(route('loan.officer.dashboard'));
        $this->assertSame(
            '10 bags of maize',
            Applicant::query()->where('email', 'jane@example.com')->value('stock_of_item_sold')
        );
    }

    public function test_admin_dashboard_lists_uploaded_documents(): void
    {
        Storage::fake('public');

        Applicant::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'nrc' => '123456/78/9',
            'contacts' => '0977000000',
            'loan_amount' => 5000,
            'loan_duration' => 12,
            'loan_status' => 'PENDING_REVIEW',
            'attachment' => 'applicants/pay-slip.pdf',
            'credit_recommendation' => 'RECOMMEND',
            'review_notes' => 'Client has a stable income and collateral.',
            'source' => 'web',
            'reg_date' => now()->toDateString(),
        ]);

        $response = $this->get('/admin/recent-applicants');

        $response->assertStatus(200)
            ->assertSee('View file')
            ->assertSee('/storage/applicants/pay-slip.pdf')
            ->assertSee('Recommendation')
            ->assertSee('Client has a stable income and collateral.');
    }

    public function test_admin_dashboard_uses_legacy_file_folder_preview_when_storage_link_is_missing(): void
    {
        $legacyFileDir = public_path('file');
        if (! is_dir($legacyFileDir)) {
            mkdir($legacyFileDir, 0777, true);
        }
        file_put_contents($legacyFileDir . DIRECTORY_SEPARATOR . 'legacy-pay-slip.pdf', 'legacy pdf content');

        Applicant::create([
            'name' => 'Legacy File Client',
            'email' => 'legacy@example.com',
            'nrc' => '987',
            'contacts' => '0999999999',
            'loan_amount' => 4500,
            'loan_duration' => 10,
            'loan_status' => 'PENDING_REVIEW',
            'attachment' => 'file/legacy-pay-slip.pdf',
            'source' => 'web',
            'reg_date' => now()->toDateString(),
        ]);

        $response = $this->get('/admin/recent-applicants');

        $response->assertStatus(200)
            ->assertSee('/file/legacy-pay-slip.pdf')
            ->assertSee('View file');
    }

    public function test_admin_dashboard_recent_applications_only_shows_active_submissions(): void
    {
        Admin::create([
            'username' => 'adminuser',
            'password' => Hash::make('adminpass'),
            'role' => 'admin',
        ]);

        Applicant::create([
            'name' => 'Active Pending Client',
            'email' => 'pending@example.com',
            'nrc' => '111',
            'contacts' => '111',
            'loan_amount' => 2000,
            'loan_duration' => 6,
            'loan_status' => 'PENDING_REVIEW',
            'source' => 'loan_officer',
            'reg_date' => now()->toDateString(),
        ]);

        Applicant::create([
            'name' => 'Active Recommended Client',
            'email' => 'recommended@example.com',
            'nrc' => '222',
            'contacts' => '222',
            'loan_amount' => 3000,
            'loan_duration' => 8,
            'loan_status' => 'RECOMMENDED',
            'source' => 'credit_officer',
            'reg_date' => now()->toDateString(),
        ]);

        Applicant::create([
            'name' => 'Old Approved Client',
            'email' => 'approved@example.com',
            'nrc' => '333',
            'contacts' => '333',
            'loan_amount' => 4000,
            'loan_duration' => 10,
            'loan_status' => 'APPROVED',
            'source' => 'loan_officer',
            'reg_date' => now()->toDateString(),
        ]);

        Applicant::create([
            'name' => 'Old Rejected Client',
            'email' => 'rejected@example.com',
            'nrc' => '444',
            'contacts' => '444',
            'loan_amount' => 5000,
            'loan_duration' => 12,
            'loan_status' => 'REJECTED',
            'source' => 'loan_officer',
            'reg_date' => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'admin_id' => 1,
            'admin_name' => 'adminuser',
        ])->get('/admin/dashboard');

        $response->assertStatus(200)
            ->assertSee('Active Pending Client')
            ->assertSee('Active Recommended Client')
            ->assertDontSee('Old Approved Client')
            ->assertDontSee('Old Rejected Client');
    }

    public function test_loan_officer_dashboard_keeps_decisioned_applications_visible(): void
    {
        $loanOfficer = Admin::create([
            'username' => 'loanofficer',
            'password' => Hash::make('password123'),
            'role' => 'loan_officer',
        ]);

        Applicant::create([
            'name' => 'Approved Client',
            'email' => 'approved@example.com',
            'nrc' => '333',
            'contacts' => '999',
            'loan_amount' => 4000,
            'loan_duration' => 10,
            'loan_status' => 'APPROVED',
            'source' => 'loan_officer',
            'created_by' => $loanOfficer->id,
            'reg_date' => now()->toDateString(),
        ]);

        Applicant::create([
            'name' => 'Rejected Client',
            'email' => 'rejected@example.com',
            'nrc' => '444',
            'contacts' => '777',
            'loan_amount' => 3500,
            'loan_duration' => 9,
            'loan_status' => 'REJECTED',
            'source' => 'loan_officer',
            'created_by' => $loanOfficer->id,
            'reg_date' => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'staff_id' => $loanOfficer->id,
            'staff_name' => 'loanofficer',
            'staff_role' => 'loan_officer',
        ])->get('/loan-officer/dashboard');

        $response->assertStatus(200)
            ->assertSee('Approved Client')
            ->assertSee('APPROVED')
            ->assertSee('Rejected Client')
            ->assertSee('REJECTED');
    }

    public function test_loan_officer_dashboard_has_dedicated_reviewed_applications_section(): void
    {
        $loanOfficer = Admin::create([
            'username' => 'loanofficer',
            'password' => Hash::make('password123'),
            'role' => 'loan_officer',
        ]);

        Applicant::create([
            'name' => 'Reviewed Client',
            'email' => 'reviewed@example.com',
            'nrc' => '555',
            'contacts' => '111',
            'loan_amount' => 8000,
            'loan_duration' => 18,
            'loan_status' => 'APPROVED',
            'source' => 'loan_officer',
            'created_by' => $loanOfficer->id,
            'credit_recommendation' => 'RECOMMEND',
            'review_notes' => 'Approved after review.',
            'reg_date' => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'staff_id' => $loanOfficer->id,
            'staff_name' => 'loanofficer',
            'staff_role' => 'loan_officer',
        ])->get('/loan-officer/dashboard');

        $response->assertStatus(200)
            ->assertSee('Reviewed Applications')
            ->assertSee('Reviewed Client')
            ->assertSee('APPROVED');
    }

    public function test_field_agent_dashboard_has_reviewed_applications_section(): void
    {
        $agent = Admin::create([
            'username' => 'fieldagent1',
            'password' => Hash::make('password123'),
            'role' => 'loan_officer',
        ]);

        Applicant::create([
            'name' => 'Reviewed Field Client',
            'email' => 'fieldreviewed@example.com',
            'nrc' => '666',
            'contacts' => '222',
            'loan_amount' => 9000,
            'loan_duration' => 20,
            'loan_status' => 'APPROVED',
            'source' => 'field_agent',
            'created_by' => $agent->id,
            'review_notes' => 'Approved by credit.',
            'reg_date' => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'staff_id' => $agent->id,
            'staff_name' => 'fieldagent1',
            'staff_role' => 'field_agent',
        ])->get('/field-agent/dashboard');

        $response->assertStatus(200)
            ->assertSee('Reviewed Applications')
            ->assertSee('Reviewed Field Client')
            ->assertSee('APPROVED');
    }

    public function test_credit_officer_dashboard_has_reviewed_applications_section(): void
    {
        $creditOfficer = Admin::create([
            'username' => 'creditofficer',
            'password' => Hash::make('password123'),
            'role' => 'credit_officer',
        ]);

        Applicant::create([
            'name' => 'Reviewed Credit Client',
            'email' => 'creditreviewed@example.com',
            'nrc' => '777',
            'contacts' => '333',
            'loan_amount' => 10000,
            'loan_duration' => 24,
            'loan_status' => 'REJECTED',
            'source' => 'loan_officer',
            'created_by' => 1,
            'credit_recommendation' => 'REJECT',
            'review_notes' => 'Rejected due to risk.',
            'reg_date' => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'staff_id' => $creditOfficer->id,
            'staff_name' => 'creditofficer',
            'staff_role' => 'credit_officer',
        ])->get('/credit-officer/dashboard');

        $response->assertStatus(200)
            ->assertSee('Reviewed Applications')
            ->assertSee('Reviewed Credit Client')
            ->assertSee('REJECTED');
    }

    public function test_staff_application_form_collects_business_and_personal_details_from_reference(): void
    {
        $loanOfficer = Admin::create([
            'username' => 'loanofficer',
            'password' => Hash::make('password123'),
            'role' => 'loan_officer',
        ]);

        $response = $this->withSession([
            'staff_id' => $loanOfficer->id,
            'staff_name' => 'loanofficer',
            'staff_role' => 'loan_officer',
        ])->get('/loan-officer/applications/create');

        $response->assertStatus(200)
            ->assertSee('Date of birth')
            ->assertSee('GPS coordinates & location')
            ->assertSee('Business information')
            ->assertSee('Amount Applied For ZMW')
            ->assertSee('Loan Tenure (Duration)');
    }

    public function test_admin_approval_sends_email_to_client(): void
    {
        Mail::fake();

        $admin = Admin::create([
            'username' => 'adminuser',
            'password' => Hash::make('adminpass'),
            'role' => 'admin',
        ]);

        $applicant = Applicant::create([
            'name' => 'Approved Client',
            'email' => 'client@example.com',
            'nrc' => '123',
            'contacts' => '0977000000',
            'loan_amount' => 5000,
            'loan_duration' => 12,
            'loan_status' => 'RECOMMENDED',
            'source' => 'loan_officer',
            'reg_date' => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'admin_id' => $admin->id,
            'admin_name' => 'adminuser',
        ])->post('/admin/applications/' . $applicant->id . '/decision', [
            'decision' => 'APPROVED',
            'review_notes' => 'Approved after review.',
        ]);

        $response->assertRedirect('/admin/recent-applicants');

        Mail::assertSent(LoanApprovedMail::class, function ($mail) use ($applicant) {
            return $mail->hasTo($applicant->email)
                && $mail->applicant->id === $applicant->id;
        });
    }

    public function test_admin_can_reject_application_with_comments_for_staff(): void
    {
        $admin = Admin::create([
            'username' => 'adminuser',
            'password' => Hash::make('adminpass'),
            'role' => 'admin',
        ]);

        $applicant = Applicant::create([
            'name' => 'Rejected Client',
            'email' => 'reject@example.com',
            'nrc' => '222',
            'contacts' => '0766000000',
            'loan_amount' => 3500,
            'loan_duration' => 8,
            'loan_status' => 'RECOMMENDED',
            'source' => 'loan_officer',
            'reg_date' => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'admin_id' => $admin->id,
            'admin_name' => 'adminuser',
        ])->post('/admin/applications/' . $applicant->id . '/decision', [
            'decision' => 'REJECTED',
            'review_notes' => 'Income documents were incomplete.',
        ]);

        $response->assertRedirect('/admin/recent-applicants');
        $this->assertDatabaseHas('applicants', [
            'id' => $applicant->id,
            'loan_status' => 'REJECTED',
            'review_notes' => 'Income documents were incomplete.',
        ]);
    }

    public function test_credit_officer_can_mark_loan_as_fully_repaid(): void
    {
        $creditOfficer = Admin::create([
            'username' => 'creditofficer',
            'password' => Hash::make('password123'),
            'role' => 'credit_officer',
        ]);

        $applicant = Applicant::create([
            'name' => 'Repaid Client',
            'email' => 'repaid@example.com',
            'nrc' => '998',
            'contacts' => '0777000000',
            'loan_amount' => 6500,
            'loan_duration' => 12,
            'loan_status' => 'APPROVED',
            'source' => 'loan_officer',
            'reg_date' => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'staff_id' => $creditOfficer->id,
            'staff_name' => 'creditofficer',
            'staff_role' => 'credit_officer',
        ])->post('/credit-officer/applications/' . $applicant->id . '/close', [
            'review_notes' => 'Customer has fully repaid the loan balance.',
        ]);

        $response->assertRedirect('/credit-officer/dashboard');
        $this->assertDatabaseHas('applicants', [
            'id' => $applicant->id,
            'loan_status' => 'FULLY_REPAID',
            'review_notes' => 'Customer has fully repaid the loan balance.',
        ]);
    }

    public function test_loan_officer_can_login_and_access_dashboard(): void
    {
        Admin::create([
            'username' => 'loanofficer',
            'password' => Hash::make('password123'),
            'role' => 'loan_officer',
        ]);

        $response = $this->post('/loan-officer/login', [
            'username' => 'loanofficer',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/loan-officer/dashboard');
    }

    public function test_field_agent_can_register_and_admin_dashboard_shows_role_summary(): void
    {
        Admin::create([
            'username' => 'adminuser',
            'password' => Hash::make('adminpass'),
            'role' => 'admin',
        ]);

        Admin::create([
            'username' => 'loanofficer',
            'password' => Hash::make('password123'),
            'role' => 'loan_officer',
        ]);

        Admin::create([
            'username' => 'creditofficer',
            'password' => Hash::make('password123'),
            'role' => 'credit_officer',
        ]);

        $registerResponse = $this->post('/field-agent/register', [
            'name' => 'Field Agent One',
            'username' => 'fieldagent1',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $registerResponse->assertRedirect('/field-agent/dashboard');
        $this->assertDatabaseHas('admins', [
            'username' => 'fieldagent1',
            'role' => 'loan_officer',
        ]);

        $dashboardResponse = $this->withSession(['admin_id' => 1, 'admin_name' => 'adminuser'])->get('/admin/dashboard');

        $dashboardResponse->assertStatus(200)
            ->assertSee('Loan Officers')
            ->assertSee('Credit Officers');
    }

    public function test_loan_officer_can_create_client_application_for_credit_review(): void
    {
        Admin::create([
            'username' => 'loanofficer',
            'password' => Hash::make('password123'),
            'role' => 'loan_officer',
        ]);

        $response = $this->withSession([
            'staff_id' => 1,
            'staff_name' => 'loanofficer',
            'staff_role' => 'loan_officer',
        ])->post('/loan-officer/applications', [
            'name' => 'Client One',
            'email' => 'client@example.com',
            'nrc' => '123456/78/9',
            'contacts' => '0977000000',
            'loanamount' => 5000,
            'loanduration' => 12,
            'source' => 'loan_officer',
        ]);

        $response->assertRedirect('/loan-officer/dashboard');
        $this->assertDatabaseHas('applicants', [
            'email' => 'client@example.com',
            'source' => 'loan_officer',
            'loan_status' => 'PENDING_REVIEW',
            'created_by' => 1,
        ]);
    }

    public function test_loan_officer_dashboard_only_shows_their_own_applications_and_web_apps_ignore_them(): void
    {
        $loanOfficer = Admin::create([
            'username' => 'loanofficer',
            'password' => Hash::make('password123'),
            'role' => 'loan_officer',
        ]);

        Applicant::create([
            'name' => 'My Client',
            'email' => 'mine@example.com',
            'nrc' => '111',
            'contacts' => '123',
            'loan_amount' => 2000,
            'loan_duration' => 6,
            'loan_status' => 'PENDING_REVIEW',
            'source' => 'loan_officer',
            'created_by' => $loanOfficer->id,
            'reg_date' => now()->toDateString(),
        ]);

        Applicant::create([
            'name' => 'Web Client',
            'email' => 'web@example.com',
            'nrc' => '222',
            'contacts' => '456',
            'loan_amount' => 3000,
            'loan_duration' => 8,
            'loan_status' => 'PENDING_REVIEW',
            'source' => 'web',
            'created_by' => null,
            'reg_date' => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'staff_id' => $loanOfficer->id,
            'staff_name' => 'loanofficer',
            'staff_role' => 'loan_officer',
        ])->get('/loan-officer/dashboard');

        $response->assertStatus(200)
            ->assertSee('mine@example.com')
            ->assertDontSee('web@example.com');
    }
}
