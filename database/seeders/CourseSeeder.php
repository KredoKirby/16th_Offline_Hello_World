<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // コースを1つ作成
        $course = Course::create([
            'title'       => 'Laravel入門コース',
            'description' => 'Laravelを基礎から学ぶためのコースです。',
            'image_url'   => 'https://via.placeholder.com/800x200',
            'language'    => 'en',
            'level'       => 'basic',
        ]);

        // ===== セクション1 =====
        $section1 = Section::create([
            'course_id' => $course->id,
            'title'     => 'イントロダクション',
        ]);

        Lesson::insert([
            [
                'section_id' => $section1->id,
                'title'      => 'Laravelとは？',
                'content'    => 'Laravelの概要と特徴を学びます。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $section1->id,
                'title'      => '開発環境を準備する',
                'content'    => 'Laravelのインストール方法を学びます。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $section1->id,
                'title'      => '最初のアプリケーション',
                'content'    => '簡単なHello Worldアプリを作ってみます。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ===== セクション2 =====
        $section2 = Section::create([
            'course_id' => $course->id,
            'title'     => 'ルーティングとコントローラ',
        ]);

        Lesson::insert([
            [
                'section_id' => $section2->id,
                'title'      => 'ルートの基本',
                'content'    => 'ルート定義の書き方を学びます。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $section2->id,
                'title'      => 'コントローラの作成',
                'content'    => 'コントローラを作成して処理をまとめます。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $section2->id,
                'title'      => 'ビューを返す',
                'content'    => 'Bladeを使ってビューを返す方法を学びます。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
