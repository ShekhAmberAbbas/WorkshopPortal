<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@quaeretech.com');
        $password = env('ADMIN_PASSWORD', 'Admin@12345');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Quaere Admin'),
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );
    }
}
