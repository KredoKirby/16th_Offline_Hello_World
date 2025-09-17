<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // サンプルコース作成
        $course = Course::create([
            'title' => 'Laravel 入門',
            'description' => 'Laravel の基礎を学ぶコースです。',
            'image_url' => 'https://via.placeholder.com/800x200'
        ]);

        // セクションを2つ追加
        $section1 = Section::create([
            'course_id' => $course->id,
            'title' => 'イントロダクション'
        ]);

        $section2 = Section::create([
            'course_id' => $course->id,
            'title' => 'ルーティングとコントローラ'
        ]);

        // 各セクションにレッスン追加
        Lesson::create(['section_id' => $section1->id, 'title' => 'Laravel とは？']);
        Lesson::create(['section_id' => $section1->id, 'title' => '環境構築']);

        Lesson::create(['section_id' => $section2->id, 'title' => 'ルーティング基礎']);
        Lesson::create(['section_id' => $section2->id, 'title' => 'コントローラの作成']);
    }
}
