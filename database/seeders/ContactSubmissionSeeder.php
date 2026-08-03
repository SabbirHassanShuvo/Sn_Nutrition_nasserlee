<?php

namespace Database\Seeders;

use App\Models\ContactSubmission;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ContactSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            ContactSubmission::create([
                'name'    => $faker->name,
                'email'   => $faker->safeEmail,
                'subject' => $faker->sentence(4),
                'message' => $faker->paragraph(3),
                'is_read' => $faker->boolean(40), // 40% chance of being read (60% unread)
            ]);
        }
    }
}
