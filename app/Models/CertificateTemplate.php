<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'file_path', 'configuration', 'status'];

    protected $casts = [
        'configuration' => 'array',
        'status' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (CertificateTemplate $template) {
            $template->uuid = (string) Str::uuid();
        });
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function workshops()
    {
        return $this->hasMany(Workshop::class);
    }
}
