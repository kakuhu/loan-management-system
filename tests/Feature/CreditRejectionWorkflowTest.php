<?php

namespace Tests\Feature;

use App\Models\Applicant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CreditRejectionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_credit_rejection_is_sent_to_admin_for_final_decision(): void
    {
        Mail::fake();

        $applicant = Applicant::create([
            'name' => 'Credit Rejected Client',
            'email' => 'client@example.com',
            'nrc' => '123456/78/9',
            'contacts' => '0977000000',
            'loan_amount' => 5000,
            'loan_duration' => 1,
            'loan_status' => 'PENDING_REVIEW',
            'source' => 'web',
            'reg_date' => now()->toDateString(),
        ]);

        $this->withSession([
            'staff_id' => 1,
            'staff_role' => 'credit_officer',
        ])->post(route('credit.officer.recommend', $applicant), [
            'decision' => 'REJECT',
            'review_notes' => 'Income assessment did not meet the criteria.',
        ])->assertRedirect(route('credit.officer.dashboard'));

        $this->assertDatabaseHas('applicants', [
            'id' => $applicant->id,
            'loan_status' => 'RECOMMENDED',
            'credit_recommendation' => 'REJECT',
            'admin_decision_at' => null,
        ]);

        $this->withSession(['admin_id' => 1])
            ->get(route('admin.applicants'))
            ->assertOk()
            ->assertSee('Credit Rejected Client')
            ->assertSee('REJECT');

        $this->withSession(['admin_id' => 1])
            ->post(route('admin.application.decision', $applicant), [
                'decision' => 'APPROVED',
                'review_notes' => 'Approved after final review.',
            ])
            ->assertRedirect(route('admin.applicants'));

        $this->assertDatabaseHas('applicants', [
            'id' => $applicant->id,
            'loan_status' => 'APPROVED',
        ]);
        $this->assertNotNull($applicant->fresh()->admin_decision_at);

        $this->withSession(['admin_id' => 1])
            ->get(route('admin.applicants'))
            ->assertDontSee('Credit Rejected Client');
    }

    public function test_legacy_credit_rejections_without_an_admin_decision_are_visible_to_admin(): void
    {
        $applicant = Applicant::create([
            'name' => 'Existing Credit Rejection',
            'email' => 'existing@example.com',
            'nrc' => '987654/32/1',
            'contacts' => '0977000001',
            'loan_amount' => 4000,
            'loan_duration' => 1,
            'loan_status' => 'REJECTED',
            'credit_recommendation' => 'REJECT',
            'source' => 'web',
            'reg_date' => now()->toDateString(),
        ]);

        $this->withSession(['admin_id' => 1])
            ->get(route('admin.applicants'))
            ->assertOk()
            ->assertSee('Existing Credit Rejection');
    }
}
