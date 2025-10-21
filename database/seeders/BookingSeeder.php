<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Preload IDs to avoid N+1 queries
        $userIds   = DB::table('users')->pluck('id')->toArray();
        $courseIds = DB::table('courses')->pluck('id')->toArray();

        if (empty($userIds) || empty($courseIds)) {
            $this->command->warn('No users or courses found. Seed them first.');
            return;
        }

        // Helper map: course_id => [topic_id, ...]
        $topicsByCourse = DB::table('topics')
            ->select('id', 'course_id')
            ->get()
            ->groupBy('course_id')
            ->map(fn($rows) => $rows->pluck('id')->toArray())
            ->toArray();

        if (empty($topicsByCourse)) {
            $this->command->warn('No topics found. Seed topics first.');
            return;
        }

        $count = 40; // number of bookings to generate

        for ($i = 0; $i < $count; $i++) {
            // Pick distinct teacher and student
            $teacherId = $userIds[array_rand($userIds)];
            do {
                $studentId = $userIds[array_rand($userIds)];
            } while ($studentId === $teacherId);

            // Pick a course with at least 1 topic
            $courseId = null;
            $topicId  = null;

            // Try a few times to find a course that has topics
            for ($try = 0; $try < 5; $try++) {
                $tmpCourseId = $courseIds[array_rand($courseIds)];
                if (!empty($topicsByCourse[$tmpCourseId])) {
                    $courseId = $tmpCourseId;
                    $topicId  = $topicsByCourse[$courseId][array_rand($topicsByCourse[$courseId])];
                    break;
                }
            }

            if (!$courseId || !$topicId) {
                // Skip if no valid (course, topic) combination
                continue;
            }

            // Random future date within next 30 days
            $date = Carbon::today()->addDays(rand(1, 30))->toDateString();
            // Random hour (00:00 ~ 23:00)
            $time = sprintf('%02d:00:00', rand(0, 23));

            // Try insert; if unique constraint fails, skip or tweak time
            try {
                DB::table('bookings')->insert([
                    'teacher_id' => $teacherId,
                    'student_id' => $studentId,
                    'date'       => $date,
                    'time'       => $time,
                    'topic_id'   => $topicId,
                    'course_id'  => $courseId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {
                // If unique constraints (teacher/student + date/time) collide, try once with another time
                $time = sprintf('%02d:00:00', rand(0, 23));
                try {
                    DB::table('bookings')->insert([
                        'teacher_id' => $teacherId,
                        'student_id' => $studentId,
                        'date'       => $date,
                        'time'       => $time,
                        'topic_id'   => $topicId,
                        'course_id'  => $courseId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Throwable $e2) {
                    // Give up on this iteration to keep seeder simple
                    continue;
                }
            }
        }

        $this->command->info('Bookings table seeded successfully.');
    }
}