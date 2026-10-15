<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CertificateCounter extends Model
{
    protected $fillable = ['prefix', 'next_number'];

    public static function nextNumberFor(string $prefix = 'QTH'): int
    {
        // Certificates use one immutable global sequence across every workshop.
        $prefix = 'QTH';

        return DB::transaction(function () use ($prefix) {
            // insertOrIgnore makes first-time creation safe when two certificate
            // requests arrive at the same time.
            static::query()->insertOrIgnore([
                'prefix' => $prefix,
                'next_number' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $counter = static::query()
                ->where('prefix', $prefix)
                ->lockForUpdate()
                ->firstOrFail();

            // Reconcile with the highest certificate ever written. The counter
            // only moves forward, so deleting a certificate can never recycle
            // its number.
            $highest = Certificate::query()
                ->where('certificate_id', 'like', $prefix . '%')
                ->pluck('certificate_id')
                ->map(function ($id) use ($prefix) {
                    $suffix = substr((string) $id, strlen($prefix));
                    return ctype_digit($suffix) ? (int) $suffix : 0;
                })
                ->max() ?? 0;

            $number = max((int) $counter->next_number, $highest + 1);
            $counter->update(['next_number' => $number + 1]);

            return $number;
        });
    }
}
