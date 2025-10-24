<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id')->toArray();
        $courseIds = DB::table('courses')->pluck('id')->toArray();

        if (empty($userIds) || empty($courseIds)) {
            $this->command->warn('No users or courses found. Please seed them first.');
            return;
        }

        $records = [];

        // Each user will be enrolled in 3 random courses
        for ($i = 0; $i < count($userIds); $i++) {
            $userId = $userIds[$i];

            // Randomly pick 3 different courses for each user
            $selectedCourses = array_rand($courseIds, min(3, count($courseIds)));
            if (!is_array($selectedCourses)) $selectedCourses = [$selectedCourses];

            foreach ($selectedCourses as $index) {
                $records[] = [
                    'user_id'         => $userId,
                    'course_id'       => $courseIds[$index],
                    'status'          => fake()->randomElement(['active', 'completed']),
                    'progress'        => fake()->numberBetween(0, 100),
                    'enrollment_date' => Carbon::now()->subDays(rand(10, 120)),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }
        }

        DB::table('enrollments')->insert($records);

        $this->command->info('Enrollments table seeded successfully.');
    }
}
