<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admins')) {
            Schema::table('admins', function (Blueprint $table) {
                if (! Schema::hasColumn('admins', 'username')) {
                    $table->string('username')->unique()->nullable();
                }

                if (! Schema::hasColumn('admins', 'password')) {
                    $table->string('password')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('admins', 'username')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropColumn(['username', 'password']);
            });
        }
    }
};