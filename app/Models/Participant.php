<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_id',
        'name',
        'email',
        'mobile',
        'whatsapp_number',
        'course_id',
        'branch_id',
        'course',
        'branch',
        'year',
        'college_name',
        'enrollment_number',
        'home_state',
        'home_district',
        'registered_at',
        'enrollment_id',
        'feedback_workshop_rating',
        'feedback_previous_quaere_training',
        'feedback_trainee_interest',
        'feedback_program_interest',
        'feedback_practical_oriented',
        'feedback_industry_relevant',
        'feedback_speaker',
        'feedback_speaker_rating',
        'feedback_speaker_answered_questions',
        'feedback_comments',
        'feedback_submitted_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'feedback_submitted_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Participant $participant) {
            $participant->uuid = $participant->uuid ?? (string) Str::uuid();
            $participant->access_token = $participant->access_token ?? Str::random(48);
            $participant->registered_at = $participant->registered_at ?? now();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'access_token';
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function courseRelation()
    {
        return $this->belongsTo(Course::class, 'course_id')->withTrashed();
    }

    public function branchRelation()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public function hasFeedback(): bool
    {
        return ! empty($this->feedback_submitted_at);
    }
}
