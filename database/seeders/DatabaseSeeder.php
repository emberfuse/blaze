<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Thavarshan Thayananthajothy',
            'username' => 'Thavarshan',
            'email' => 'tjthavarshan@gmail.com',
            'password' => '$2y$04$uitMi8kxfAHXz.aAWujG/.SeRuXwkVMuLJP/xLpLeWgyhZ5OUDzf6', // alphaxion77
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'settings' => [
                'notifications_mobile' => 'everything',
                'notifications_email' => [],
            ],
        ]);
    }
}
