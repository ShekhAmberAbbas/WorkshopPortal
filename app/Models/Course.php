<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'status'];

    protected $casts = ['status' => 'boolean'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Course $course) {
            $course->uuid = $course->uuid ?? (string) Str::uuid();
        });
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
