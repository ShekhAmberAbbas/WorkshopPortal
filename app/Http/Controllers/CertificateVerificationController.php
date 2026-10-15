<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Support\Facades\Storage;

class CertificateVerificationController extends Controller
{
    /**
     * The source of truth. Even if a downloaded PDF is edited externally,
     * scanning its QR always lands here and shows the ORIGINAL database
     * record — this page, not the PDF, is what makes the certificate
     * tamper-evident (spec #16/#17).
     */
    public function show(Certificate $certificate)
    {
        abort_unless(in_array($certificate->status, ['generated', 'revoked']), 404);

        $certificate->load(['participant', 'workshop']);

        return view('public.certificate.verify', compact('certificate'));
    }

    public function download(Certificate $certificate)
    {
        abort_unless($certificate->status === 'generated', 404, 'This certificate is unavailable for download.');
        abort_unless(
            $certificate->workshop?->certificatesAvailableForDownload(),
            403,
            'Certificate downloads are available starting on the workshop date.'
        );

        return Storage::disk('local')->download(
            $certificate->file_path,
            $certificate->downloadFilename()
        );
    }
}
