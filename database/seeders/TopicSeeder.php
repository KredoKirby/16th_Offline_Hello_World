<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // すでに存在する courses のID一覧を取得
        $courseIds = DB::table('courses')->pluck('id');

        foreach ($courseIds as $courseId) {
            for ($i = 1; $i <= 3; $i++) {
                DB::table('topics')->insert([
                    'name'       => "Course {$courseId} - Topic {$i}",
                    'course_id'  => $courseId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
