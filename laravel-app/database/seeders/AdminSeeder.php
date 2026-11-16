<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Credentials are never hardcoded here — they come from ADMIN_* env vars
 * (see .env.example) so nothing sensitive is committed to the repo.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $phone = env('ADMIN_PHONE');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $phone || ! $password) {
            $this->command?->warn(
                'Skipped AdminSeeder: set ADMIN_NAME, ADMIN_EMAIL, ADMIN_PHONE and ADMIN_PASSWORD in .env, then re-run php artisan db:seed.'
            );

            return;
        }

        Admin::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Abraham Montes'),
                'phone' => $phone,
                'password' => Hash::make($password),
            ]
        );
    }
}
