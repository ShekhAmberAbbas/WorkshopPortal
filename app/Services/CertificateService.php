<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\CertificateCounter;
use App\Models\Participant;
use App\Models\Workshop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use setasign\Fpdi\Tcpdf\Fpdi;
use Throwable;

class CertificateService
{
    public function __construct(protected QrCodeService $qrCodeService)
    {
    }

    /**
     * Create a certificate as part of registration. A participant is not
     * committed unless an active template assigned to their workshop can
     * produce the certificate; workshop opt-out flags no longer suppress it.
     */
    public function generateForRegistration(Participant $participant): Certificate
    {
        $existing = $participant->certificate()->first();
        if ($existing && $existing->status === 'generated') {
            return $existing;
        }

        $workshop = $participant->workshop;
        $template = $workshop?->certificateTemplate;

        if (! $template || ! $template->status) {
            throw new RuntimeException('An active certificate template must be assigned to this workshop before registration can be completed.');
        }

        return $this->generate($participant, $template);
    }

    /** Backwards-compatible alias for older internal callers. */
    public function autoGenerateIfEnabled(Participant $participant): ?Certificate
    {
        return $this->generateForRegistration($participant);
    }

    /**
     * Generate and persist a personalized certificate for a participant.
     * Throws if one already exists — the unique index on participant_id
     * is the hard backstop, this is just a friendlier failure earlier.
     * Used by auto-generation, the single "Generate" button, and bulk
     * generation alike — one code path, one PDF pipeline.
     */
    public function generate(Participant $participant, CertificateTemplate $template): Certificate
    {
        if ($participant->certificate()->exists()) {
            throw new RuntimeException('A certificate has already been generated for this participant.');
        }

        $workshop = $participant->workshop;

        // Reserved and COMMITTED here, deliberately BEFORE the transaction
        // below starts — CertificateCounter::nextNumberFor() runs its own
        // independent transaction. If PDF generation fails afterward and
        // the transaction below rolls back, this reservation is NOT undone
        // — the number is simply skipped, never reused. That's what stops
        // the same number being handed out on every retry.
        $certificateId = 'QTH' . str_pad(CertificateCounter::nextNumberFor('QTH'), 3, '0', STR_PAD_LEFT);

        return DB::transaction(function () use ($participant, $template, $certificateId) {
            $certificate = Certificate::create([
                'participant_id' => $participant->id,
                'workshop_id' => $participant->workshop_id,
                'certificate_template_id' => $template->id,
                'certificate_id' => $certificateId,
                'file_path' => 'pending', // replaced below once the PDF exists
                // Issue date = the day the STUDENT registered, not the day
                // this certificate happens to be generated — matters when
                // admin generates manually days/weeks after registration.
                'issue_date' => $participant->registered_at->toDateString(),
                'status' => 'pending',
            ]);

            $fieldValues = $this->fieldValuesFor($participant, $certificate);
            $verifyUrl = route('certificate.verify', $certificate);

            $pdfContent = $this->render($template, $fieldValues, $verifyUrl);

            $path = "certificates/{$participant->workshop_id}/{$certificate->uuid}.pdf";
            Storage::disk('local')->put($path, $pdfContent);

            $certificate->update(['file_path' => $path, 'status' => 'generated']);

            return $certificate->fresh();
        });
    }

    /**
     * Renders a sample certificate with placeholder data for the admin to
     * check field alignment WITHOUT touching the database. Used by the
     * template editor's "Preview" button.
     */
    public function previewPdf(CertificateTemplate $template): string
    {
        $fieldValues = [
            'student_name' => 'Jane Doe',
            'workshop_name' => 'Sample Workshop Title',
            'college_name' => 'Sample College of Engineering',
            'course' => 'B.Tech',
            'branch' => 'Computer Science',
            'year' => '3rd Year',
            'trainer_name' => 'Sample Trainer',
            'date' => now()->format('d M Y'),
            'issue_date' => now()->format('d M Y'),
            'duration' => '2 Days',
            'certificate_id' => 'CERT-' . now()->year . '-PREVIEW01',
            'enrollment_id' => 'PREFIX/001',
        ];

        return $this->render($template, $fieldValues, url('/certificate/verify/preview'));
    }

    protected function fieldValuesFor(Participant $participant, Certificate $certificate): array
    {
        $workshop = $participant->workshop;

        return [
            'student_name' => $participant->name,
            'workshop_name' => $workshop->title,
            'college_name' => $participant->college_name,
            'course' => $participant->course,
            'branch' => $participant->branch,
            'year' => $participant->year,
            'trainer_name' => $workshop->trainer_name,
            'date' => $workshop->start_date->format('d M Y'),
            'issue_date' => $certificate->issue_date->format('d M Y'),
            'duration' => $workshop->duration_label,
            'certificate_id' => $certificate->certificate_id,
            'enrollment_id' => $participant->enrollment_id,
        ];
    }

    /**
     * Imports the uploaded template's first page as a static background,
     * writes each configured field on top at its configured (x, y), and
     * drops in the verification QR. No interactive form fields are ever
     * created, so the output has nothing for a student to edit in place —
     * it's flattened by construction, not by a separate flattening step.
     */
    protected function render(CertificateTemplate $template, array $fieldValues, string $verifyUrl): string
    {
        $templatePath = Storage::disk('local')->path($template->file_path);

        // Unit MUST be 'pt' — every coordinate in a template's configuration
        // (and everywhere else in this app) is stored in points. TCPDF
        // defaults to millimeters, which silently misinterprets y=349 as
        // 349mm (taller than the whole page) and forces a spurious page
        // break before every single field write.
        $pdf = new Fpdi('P', 'pt');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0);

        $pdf->setSourceFile($templatePath);
        $templateId = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($templateId);

        $orientation = $size['width'] >= $size['height'] ? 'L' : 'P';
        $pdf->AddPage($orientation, [$size['width'], $size['height']]);
        $pdf->useTemplate($templateId);

        $config = $template->configuration ?? [];

        foreach ($fieldValues as $key => $value) {
            if ($value === null || ! isset($config[$key]['x'], $config[$key]['y'])) {
                continue;
            }

            [$r, $g, $b] = $this->hexToRgb($config[$key]['color'] ?? '#000000');
            $pdf->SetTextColor($r, $g, $b);

            $style = '';
            if (! empty($config[$key]['bold'])) $style .= 'B';
            if (! empty($config[$key]['italic'])) $style .= 'I';

            $pdf->SetFont($config[$key]['font_family'] ?? 'dejavusans', $style, $config[$key]['font_size'] ?? 16);
            $pdf->SetXY((float) $config[$key]['x'], (float) $config[$key]['y']);
            $pdf->Write(0, $value);
        }

        if (isset($config['verification_qr']['x'], $config['verification_qr']['y'])) {
            $qrSize = (float) ($config['verification_qr']['size'] ?? 70);
            $qrSvg = $this->qrCodeService->svgForUrl($verifyUrl, 300);

            // Embed the QR as vector SVG. This uses TCPDF's native SVG support,
            // so certificate generation does not require the missing pngFor()
            // method or the optional Imagick/GD raster-image extensions.
            $pdf->ImageSVG(
                '@' . $qrSvg,
                (float) $config['verification_qr']['x'],
                (float) $config['verification_qr']['y'],
                $qrSize,
                $qrSize
            );
        }

        return $pdf->Output('', 'S'); // 'S' = return as string, don't write to disk here
    }

    /**
     * "#005495" -> [0, 84, 149]. Falls back to black on anything malformed
     * rather than throwing — a bad color value should never break generation.
     */
    protected function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        if (! preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
            return [0, 0, 0];
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
