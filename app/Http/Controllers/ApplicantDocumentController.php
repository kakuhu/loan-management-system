<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\LoanFormDocument;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ApplicantDocumentController extends Controller
{
    public function downloadCollateralForm()
    {
        $document = LoanFormDocument::query()->first();

        abort_unless(
            $document
                && (Storage::disk('local')->exists($document->path) || Storage::disk('public')->exists($document->path)),
            404
        );

        $disk = Storage::disk('local')->exists($document->path) ? 'local' : 'public';

        return Storage::disk($disk)->download($document->path, $document->original_name);
    }

    public function downloadApplicantDocument(Applicant $applicant, string $document)
    {
        abort_unless(
            session('admin_id') || in_array(session('staff_role'), ['loan_officer', 'field_agent', 'credit_officer']),
            403
        );

        $allowedDocuments = [
            'nrc_document',
            'collateral_document',
            'recent_payslip',
            'proof_of_address',
            'bank_statement',
            'signed_form',
        ];

        abort_unless(in_array($document, $allowedDocuments, true), 404);

        $path = $applicant->{$document};

        abort_unless($path, 404);

        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->download($path);
        }

        abort_unless(
            Str::startsWith($path, 'file/')
                && basename($path) === substr($path, strlen('file/'))
                && file_exists(public_path($path)),
            404
        );

        return response()->download(public_path($path));
    }
}
