<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('nrc');
            $table->string('contacts');
            $table->decimal('loan_amount', 12, 2);
            $table->unsignedInteger('loan_duration');
            $table->string('loan_status');
            $table->string('image')->nullable();
            $table->date('reg_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};