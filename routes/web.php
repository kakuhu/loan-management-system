<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicantDocumentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LegacyPageController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\LoanWorkflowController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'landing'])->name('landing');
Route::get('/loan-calculator', [SiteController::class, 'loanCalculator'])->name('loan.calculator');

Route::get('/apply', [LoanApplicationController::class, 'create'])->name('loan.apply');
Route::post('/apply', [LoanApplicationController::class, 'store'])->name('loan.store');
Route::get('/loan-documents/collateral-form', [ApplicantDocumentController::class, 'downloadCollateralForm'])
    ->name('loan.collateral-form.download');
Route::get('/applications/{applicant}/documents/{document}', [ApplicantDocumentController::class, 'downloadApplicantDocument'])
    ->name('applicant.document.download');

Route::get('/login', [AuthController::class, 'userLoginForm'])->name('user.login');
Route::post('/login', [AuthController::class, 'userLogin'])->name('user.login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');

Route::get('/admin/login', [AuthController::class, 'adminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
Route::get('/admin/recent-applicants', [AdminController::class, 'recentApplicants'])->name('admin.applicants');
Route::get('/admin/approved-loans', [AdminController::class, 'approvedLoans'])->name('admin.approved_loans');
Route::get('/admin/monthly-report', [AdminController::class, 'monthlyReport'])->name('admin.monthly_report');
Route::post('/admin/monthly-report', [AdminController::class, 'monthlyReport'])->name('admin.monthly_report.filter');
Route::post('/admin/collateral-form', [AdminController::class, 'updateCollateralForm'])->name('admin.collateral_form.update');
Route::post('/admin/applications/{applicant}/decision', [LoanWorkflowController::class, 'finalDecision'])->name('admin.application.decision');

Route::get('/field-agent/register', [AuthController::class, 'fieldAgentRegisterForm'])->name('field.agent.register');
Route::post('/field-agent/register', [AuthController::class, 'fieldAgentRegister'])->name('field.agent.register.submit');
Route::get('/field-agent/login', [AuthController::class, 'fieldAgentLoginForm'])->name('field.agent.login');
Route::post('/field-agent/login', [AuthController::class, 'fieldAgentLogin'])->name('field.agent.login.submit');
Route::get('/field-agent/dashboard', [DashboardController::class, 'fieldAgentDashboard'])->name('field.agent.dashboard');
Route::get('/field-agent/applications/create', [LoanApplicationController::class, 'createStaffApplication'])->name('field.agent.applications.create');
Route::post('/field-agent/applications', [LoanApplicationController::class, 'storeStaffApplication'])->name('field.agent.applications.store');

Route::get('/loan-officer/login', [AuthController::class, 'loanOfficerLoginForm'])->name('loan.officer.login');
Route::post('/loan-officer/login', [AuthController::class, 'loanOfficerLogin'])->name('loan.officer.login.submit');
Route::get('/loan-officer/dashboard', [DashboardController::class, 'loanOfficerDashboard'])->name('loan.officer.dashboard');
Route::get('/loan-officer/applications/create', [LoanApplicationController::class, 'createStaffApplication'])->name('loan.officer.applications.create');
Route::post('/loan-officer/applications', [LoanApplicationController::class, 'storeStaffApplication'])->name('loan.officer.applications.store');
Route::post('/loan-officer/applications/{applicant}/submit', [LoanWorkflowController::class, 'submitToCredit'])->name('loan.officer.submit');

Route::get('/credit-officer/login', [AuthController::class, 'creditOfficerLoginForm'])->name('credit.officer.login');
Route::post('/credit-officer/login', [AuthController::class, 'creditOfficerLogin'])->name('credit.officer.login.submit');
Route::get('/credit-officer/dashboard', [DashboardController::class, 'creditOfficerDashboard'])->name('credit.officer.dashboard');
Route::post('/credit-officer/applications/{applicant}/recommend', [LoanWorkflowController::class, 'recommend'])->name('credit.officer.recommend');
Route::post('/credit-officer/applications/{applicant}/close', [LoanWorkflowController::class, 'closeLoan'])->name('credit.officer.close');

Route::get('/{page}', [LegacyPageController::class, 'show'])->where('page', '.*');
