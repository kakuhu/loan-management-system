<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['username' => 'admin'],
            ['name' => 'System Admin', 'password' => Hash::make('admin123'), 'role' => 'admin']
        );

        Admin::updateOrCreate(
            ['username' => 'loanofficer'],
            ['name' => 'Loan Officer', 'password' => Hash::make('loan123'), 'role' => 'loan_officer']
        );

        Admin::updateOrCreate(
            ['username' => 'creditofficer'],
            ['name' => 'Credit Officer', 'password' => Hash::make('credit123'), 'role' => 'credit_officer']
        );

    }
}
