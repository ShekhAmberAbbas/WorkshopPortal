<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Workshop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'college_name', 'college_address',
        'start_date', 'end_date', 'start_time', 'end_time',
        'trainer_name', 'trainer_designation', 'max_participants',
        'status', 'certificate_enabled', 'created_by',
        'enrollment_prefix', 'auto_generate_certificate', 'certificate_template_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'certificate_enabled' => 'boolean',
        'auto_generate_certificate' => 'boolean',
    ];

    protected $appends = ['computed_end_date', 'duration_label'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Workshop $workshop) {
            $workshop->uuid = $workshop->uuid ?? (string) Str::uuid();
            $workshop->workshop_code = $workshop->workshop_code ?? static::generateUniqueCode();
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = 'WS-' . now()->year . '-' . strtoupper(Str::random(6));
        } while (static::where('workshop_code', $code)->exists());

        return $code;
    }

    public function getRouteKeyName(): string
    {
        return 'workshop_code';
    }

    public function getComputedEndDateAttribute()
    {
        return $this->end_date ?? $this->start_date;
    }

    /** True only before the workshop's start calendar date. */
    public function isBeforeStartDate(): bool
    {
        return $this->start_date !== null
            && $this->start_date->toDateString() > today()->toDateString();
    }

    /**
     * Describes why registration is unavailable. Workshop status remains the
     * primary admin-controlled switch; date gating only blocks early forms.
     */
    public function registrationUnavailableMessage(): ?string
    {
        if ($this->status !== 'active') {
            return 'Registration is currently closed for this workshop.';
        }

        if ($this->isBeforeStartDate()) {
            $date = $this->start_date->format('d M Y');
            return "This workshop will be held on {$date}. The registration form will open on {$date}. Please revisit this link on or after that date.";
        }

        $template = $this->certificateTemplate;
        if (! $template || ! $template->status) {
            return 'Registration is temporarily unavailable because the workshop certificate template is not configured. Please contact the workshop organizer.';
        }

        return null;
    }

    /**
     * A registered participant can download the certificate on the workshop's
     * start date, throughout the workshop, and afterward. There is no end-date
     * delay; only pre-workshop downloads are blocked.
     */
    public function certificatesAvailableForDownload(): bool
    {
        return $this->start_date !== null
            && today()->toDateString() >= $this->start_date->toDateString();
    }

    public function getDurationLabelAttribute(): string
    {
        if ($this->start_date && $this->end_date) {
            $days = $this->start_date->diffInDays($this->end_date) + 1;
            return $days . ' ' . Str::plural('Day', $days);
        }

        return '—';
    }

    public function participants() { return $this->hasMany(Participant::class); }
    public function certificates() { return $this->hasMany(Certificate::class); }
    public function qrCode() { return $this->hasOne(WorkshopQrCode::class); }
    public function certificateTemplate() { return $this->belongsTo(CertificateTemplate::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }

    public function registrationUrl(): string
    {
        return route('workshop.register.show', $this->workshop_code);
    }

    public function nextEnrollmentId(): ?string
    {
        if (! $this->enrollment_prefix) return null;

        $seq = $this->participants()->whereNotNull('enrollment_id')->count() + 1;
        return $this->enrollment_prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }
}
