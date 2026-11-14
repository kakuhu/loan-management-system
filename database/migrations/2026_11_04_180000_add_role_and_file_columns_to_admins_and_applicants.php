<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (! Schema::hasColumn('admins', 'role')) {
                $table->string('role')->default('admin')->after('password');
            }
        });

        Schema::table('applicants', function (Blueprint $table) {
            if (! Schema::hasColumn('applicants', 'attachment')) {
                $table->string('attachment')->nullable()->after('image');
            }

            if (! Schema::hasColumn('applicants', 'source')) {
                $table->string('source')->default('web')->after('attachment');
            }

            if (! Schema::hasColumn('applicants', 'credit_recommendation')) {
                $table->string('credit_recommendation')->nullable()->after('source');
            }

            if (! Schema::hasColumn('applicants', 'review_notes')) {
                $table->text('review_notes')->nullable()->after('credit_recommendation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            if (Schema::hasColumn('applicants', 'attachment')) {
                $table->dropColumn('attachment');
            }

            if (Schema::hasColumn('applicants', 'source')) {
                $table->dropColumn('source');
            }

            if (Schema::hasColumn('applicants', 'credit_recommendation')) {
                $table->dropColumn('credit_recommendation');
            }

            if (Schema::hasColumn('applicants', 'review_notes')) {
                $table->dropColumn('review_notes');
            }
        });

        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
