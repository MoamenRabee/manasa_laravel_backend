<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'type' => 'admin',
            'password'=> bcrypt('password'),
        ]);

        

        Setting::create([
            'android_build_number' => 1,
            'ios_build_number' => 1,
            'android_link' => 'https://example.com/android',
            'ios_link' => 'https://example.com/ios',
            'is_closed' => 0,
            'closed_message' => null,
            'whatsapp' => 'https://wa.me/1234567890',
            'facebook' => 'https://facebook.com/example',
            'telegram' => 'https://t.me/example',
            'website' => 'https://example.com',
            'phone_number' => '01273308123',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
