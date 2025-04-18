<?php

namespace Database\Seeders;

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
        $this->call([
            AdminSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,
            SubjectSeeder::class,
            AcademicCalendarSeeder::class,
            AnnouncementSeeder::class, // Add our new announcement seeder
            NotificationSeeder::class, // Add the NotificationSeeder
        ]);

        // User::factory(10)->create();

        // Replace create with firstOrCreate to prevent duplicate entries
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'), // Add a password
            ]
        );
    }
}
