<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TCPDF_FONTS;

class RegisterCertificateFont extends Command
{
    protected $signature = 'certificates:register-font {path : Absolute path to the .ttf file}';

    protected $description = 'Converts a .ttf font file into a TCPDF-compatible font so it can be used on certificate templates.';

    public function handle(): int
    {
        $path = $this->argument('path');

        if (! file_exists($path)) {
            $this->error("File not found: {$path}");

            return self::FAILURE;
        }

        // 'TrueTypeUnicode' embeds and subsets the font so only the glyphs
        // actually used end up in each generated certificate PDF — keeps
        // file size sane even with a full Unicode font file.
        $fontName = TCPDF_FONTS::addTTFfont($path, 'TrueTypeUnicode', '', 96);

        if (! $fontName) {
            $this->error('Font conversion failed — check the file is a valid, non-corrupt .ttf.');

            return self::FAILURE;
        }

        $this->info("Registered successfully as: {$fontName}");
        $this->line('');
        $this->line('Add this to the $fontOptions array in:');
        $this->line('resources/views/admin/certificate-templates/_form.blade.php');
        $this->line("    '{$fontName}' => 'Your label here',");

        return self::SUCCESS;
    }
}
