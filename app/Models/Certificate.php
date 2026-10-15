<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id', 'workshop_id', 'certificate_template_id',
        'certificate_id', 'verification_token', 'file_path',
        'issue_date', 'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    public function getRouteKeyName(): string
    {
        return 'verification_token';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Certificate $certificate) {
            $certificate->uuid = (string) Str::uuid();
            $certificate->certificate_id = $certificate->certificate_id ?? static::generateCertificateId();
            $certificate->verification_token = $certificate->verification_token ?? Str::random(64);
        });
    }

    public static function generateCertificateId(): string
    {
        // Fallback for any code path that creates a Certificate without
        // explicitly supplying an ID. Keep it on the same global sequence
        // used by CertificateService so duplicate numbering is impossible.
        $seq = CertificateCounter::nextNumberFor('QTH');

        return 'QTH' . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    /**
     * certificate_id often contains "/" (e.g. "QTH/001"), which is invalid
     * inside an HTTP download filename / Content-Disposition header — that
     * "/" is meant to be part of the printed certificate number, not a
     * filesystem path. Every controller that streams this file uses this
     * instead of building the filename from certificate_id directly.
     */
    public function downloadFilename(): string
    {
        return str_replace(['/', '\\'], '-', $this->certificate_id) . '.pdf';
    }
}
