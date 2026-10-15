<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['course_id', 'name', 'status'];

    protected $casts = ['status' => 'boolean'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Branch $branch) {
            $branch->uuid = $branch->uuid ?? (string) Str::uuid();
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
