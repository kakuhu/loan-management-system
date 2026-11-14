<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function userLoginForm()
    {
        return view('auth.user-login');
    }

    public function userLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = DB::table('registereduser')
            ->where('email', $credentials['email'])
            ->where('password', $credentials['password'])
            ->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        session([
            'user_id' => $user->id ?? $user->user_id ?? null,
            'user_name' => $user->name ?? $user->email,
            'loan_amount' => $user->loan_amount ?? 0,
        ]);

        return redirect()->route('user.dashboard');
    }

    public function adminLoginForm()
    {
        return view('admin.login');
    }

    public function adminLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $admin = Admin::where('username', $credentials['username'])->first();

        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            return back()
                ->withErrors(['username' => 'Invalid admin username or password.'])
                ->withInput();
        }

        session([
            'admin_id' => $admin->id,
            'admin_name' => $admin->username,
            'staff_id' => $admin->id,
            'staff_name' => $admin->username,
            'staff_role' => $admin->role,
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function fieldAgentRegisterForm()
    {
        return view('field-agent.register');
    }

    public function fieldAgentRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:admins,username'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (Schema::hasTable('admins')) {
            Schema::table('admins', function (Blueprint $table) {
                if (! Schema::hasColumn('admins', 'name')) {
                    $table->string('name')->nullable();
                }

                if (! Schema::hasColumn('admins', 'role')) {
                    $table->string('role')->default('loan_officer');
                }
            });
        }

        $agent = Admin::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'loan_officer',
        ]);

        session([
            'staff_id' => $agent->id,
            'staff_name' => $agent->username,
            'staff_role' => $agent->role,
        ]);

        return redirect()->route('field.agent.dashboard');
    }

    public function fieldAgentLoginForm()
    {
        return view('field-agent.login');
    }

    public function fieldAgentLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $agent = Admin::where('username', $credentials['username'])
            ->whereIn('role', ['loan_officer', 'field_agent'])
            ->first();

        if (! $agent || ! Hash::check($credentials['password'], $agent->password)) {
            return back()->withErrors(['username' => 'Invalid loan officer credentials.'])->withInput();
        }

        session([
            'staff_id' => $agent->id,
            'staff_name' => $agent->username,
            'staff_role' => $agent->role,
        ]);

        return redirect()->route('field.agent.dashboard');
    }

    public function loanOfficerLoginForm()
    {
        return view('staff.loan-officer-login');
    }

    public function loanOfficerLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $staff = Admin::where('username', $credentials['username'])
            ->where('role', 'loan_officer')
            ->first();

        if (! $staff || ! Hash::check($credentials['password'], $staff->password)) {
            return back()->withErrors(['username' => 'Invalid loan officer credentials.'])->withInput();
        }

        session([
            'staff_id' => $staff->id,
            'staff_name' => $staff->username,
            'staff_role' => $staff->role,
        ]);

        return redirect()->route('loan.officer.dashboard');
    }

    public function creditOfficerLoginForm()
    {
        return view('staff.credit-officer-login');
    }

    public function creditOfficerLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $staff = Admin::where('username', $credentials['username'])
            ->where('role', 'credit_officer')
            ->first();

        if (! $staff || ! Hash::check($credentials['password'], $staff->password)) {
            return back()->withErrors(['username' => 'Invalid credit officer credentials.'])->withInput();
        }

        session([
            'staff_id' => $staff->id,
            'staff_name' => $staff->username,
            'staff_role' => $staff->role,
        ]);

        return redirect()->route('credit.officer.dashboard');
    }

    public function logout(): RedirectResponse
    {
        session()->flush();

        return redirect()->route('landing');
    }
}
