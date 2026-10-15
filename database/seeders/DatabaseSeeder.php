<?php

namespace Database\Seeders;

use App\Models\CertificateCounter;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            AcademicOptionSeeder::class,
        ]);

        CertificateCounter::firstOrCreate(
            ['prefix' => 'QTH'],
            ['next_number' => 1]
        );
    }
}
