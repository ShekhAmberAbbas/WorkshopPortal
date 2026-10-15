<?php

namespace App\Services;

use App\Models\Workshop;
use App\Models\WorkshopQrCode;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate and save the workshop registration QR code.
     */
    public function generateForWorkshop(Workshop $workshop): WorkshopQrCode
    {
        // Get the workshop registration URL
        $url = $workshop->registrationUrl();

        // Save QR as SVG instead of PNG
        $filename = 'qrcodes/' . $workshop->workshop_code . '.svg';

        // Generate SVG QR code
        $svg = QrCode::format('svg')
            ->size(400)
            ->margin(1)
            ->generate($url);

        // Save SVG to storage/app/public/qrcodes/
        Storage::disk('public')->put($filename, $svg);

        // Save/update QR information in database
        return WorkshopQrCode::updateOrCreate(
            [
                'workshop_id' => $workshop->id,
            ],
            [
                'file_path' => $filename,
                'registration_url' => $url,
            ]
        );
    }

    /**
     * Generate SVG QR code without storing it.
     */
    public function svgFor(Workshop $workshop): string
    {
        return QrCode::format('svg')
            ->size(400)
            ->margin(1)
            ->generate(
                $workshop->registrationUrl()
            );
    }

    /**
     * Generate SVG QR code for any URL.
     */
    public function svgForUrl(string $url, int $size = 300): string
    {
        return QrCode::format('svg')
            ->size($size)
            ->margin(1)
            ->generate($url);
    }
}