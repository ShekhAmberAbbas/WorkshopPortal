<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopQrCode extends Model
{
    use HasFactory;

    protected $fillable = ['workshop_id', 'file_path', 'registration_url'];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}
