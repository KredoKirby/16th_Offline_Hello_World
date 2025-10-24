<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TeacherCourseSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('role_id', 2)->first(); // 先生ロール
        $courses = Course::take(3)->pluck('id')->all();
        if ($teacher && $courses) {
            $teacher->coursesTaught()->syncWithoutDetaching($courses);
        }
    }
}
