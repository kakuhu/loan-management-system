<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('email');
            $table->string('gps_coordinates')->nullable()->after('contacts');
            $table->string('location')->nullable()->after('gps_coordinates');
            $table->string('marital_status')->nullable()->after('location');
            $table->string('spouse_name')->nullable()->after('marital_status');
            $table->string('cell_number')->nullable()->after('spouse_name');
            $table->string('business_type')->nullable()->after('cell_number');
            $table->unsignedInteger('business_experience_years')->nullable()->after('business_type');
            $table->decimal('stock_of_item_sold', 12, 2)->nullable()->after('business_experience_years');
            $table->decimal('average_daily_sales', 12, 2)->nullable()->after('stock_of_item_sold');
            $table->string('business_address')->nullable()->after('average_daily_sales');
            $table->decimal('interest_rate', 8, 2)->nullable()->after('loan_amount');
            $table->decimal('interest_amount', 12, 2)->nullable()->after('interest_rate');
            $table->decimal('loan_processing_fee', 12, 2)->nullable()->after('interest_amount');
            $table->decimal('total_amount_repayable', 12, 2)->nullable()->after('loan_processing_fee');
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gps_coordinates',
                'location',
                'marital_status',
                'spouse_name',
                'cell_number',
                'business_type',
                'business_experience_years',
                'stock_of_item_sold',
                'average_daily_sales',
                'business_address',
                'interest_rate',
                'interest_amount',
                'loan_processing_fee',
                'total_amount_repayable',
            ]);
        });
    }
};
