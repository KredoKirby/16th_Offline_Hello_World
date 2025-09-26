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
        // ===== php Basic コース =====
        $it = Course::create([
            'title'       => 'Basic PHP',
            'description' => 'PHPを中心にした基礎的なITスキルを学ぶコースです。',
            'image'   => 'phpbasic.jpg',
            'language'    => 'it',   // ← タブ判定用
            'level'       => 'basic',
        ]);

        $itSection = Section::create([
            'course_id' => $it->id,
            'title'     => 'PHP入門',
        ]);

        Lesson::insert([
            [
                'section_id' => $itSection->id,
                'title'      => 'PHPとは？',
                'content'    => 'PHPの概要と基本文法を学びます。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $itSection->id,
                'title'      => '開発環境構築',
                'content'    => 'PHPを動かす環境を整えます。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ===== English Basic コース =====
        $english = Course::create([
            'title'       => 'English Basic',
            'description' => '日常英会話の基礎を学ぶコースです。',
            'image'   => 'englishbasic.jpg',
            'language'    => 'english',  // ← タブ判定用
            'level'       => 'basic',
        ]);

        $englishSection = Section::create([
            'course_id' => $english->id,
            'title'     => 'Greetings',
        ]);

        Lesson::insert([
            [
                'section_id' => $englishSection->id,
                'title'      => '挨拶を学ぶ',
                'content'    => 'Hello, Good morning など日常的な挨拶を学びます。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $englishSection->id,
                'title'      => '自己紹介を学ぶ',
                'content'    => '英語で自己紹介する方法を学びます。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
