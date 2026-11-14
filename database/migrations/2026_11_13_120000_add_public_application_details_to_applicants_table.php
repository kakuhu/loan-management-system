<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->json('public_application_details')->nullable();
            $table->string('nrc_document')->nullable();
            $table->string('collateral_document')->nullable();
            $table->string('recent_payslip')->nullable();
            $table->string('proof_of_address')->nullable();
            $table->string('bank_statement')->nullable();
            $table->timestamp('declaration_accepted_at')->nullable();
        });

        Schema::create('loan_form_documents', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('original_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_form_documents');

        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'public_application_details',
                'nrc_document',
                'collateral_document',
                'recent_payslip',
                'proof_of_address',
                'bank_statement',
                'declaration_accepted_at',
            ]);
        });
    }
};
