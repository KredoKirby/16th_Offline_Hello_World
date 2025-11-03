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
        // -------------------------------------------------------------
        // デフォルト画像（Base64）
        // -------------------------------------------------------------
        $defaultImagePath = public_path('images/default-course.jpg');
        $defaultBase64 = file_exists($defaultImagePath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($defaultImagePath))
            : null;

        // ===== PHP Basic =====
        $php = Course::create([
            'title'       => 'Basic PHP',
            'description' => 'PHPを中心に、プログラミングの基礎から実践までを学ぶコースです。',
            'image'       => 'phpbasic.jpg',
            'language'    => 'it',
            'level'       => 'basic',
            'price'       => 3900.00,
        ]);

        $phpIntro = Section::create(['course_id' => $php->id, 'title' => 'PHP入門']);
        Lesson::create([
            'course_id'   => $php->id,
            'section_id'  => $phpIntro->id,
            'title'       => 'PHPとは？',
            'content'     => 'PHPの歴史と特徴。',
            'images'      => [$defaultBase64Image],
            'pages'       => 3,
            'duration'    => 125,
        ]);
        Lesson::create([
            'course_id'   => $php->id,
            'section_id'  => $phpIntro->id,
            'title'       => '環境構築',
            'content'     => 'XAMPP/MAMPで環境を整える。',
            'images'      => [$defaultBase64Image],
            'pages'       => 3,
            'duration'    => 210,
        ]);

        $phpSyntax = Section::create(['course_id' => $php->id, 'title' => '基礎文法']);
        Lesson::create([
            'course_id'   => $php->id,
            'section_id'  => $phpSyntax->id,
            'title'       => '変数と定数',
            'content'     => '変数と定数の基本。',
            'images'      => [$defaultBase64Image],
            'pages'       => 3,
            'duration'    => 180,
        ]);
        Lesson::create([
            'course_id'   => $php->id,
            'section_id'  => $phpSyntax->id,
            'title'       => '条件分岐',
            'content'     => 'if, switch文。',
            'images'      => [$defaultBase64Image],
            'pages'       => 3,
            'duration'    => 240,
        ]);

        $phpPrac = Section::create(['course_id' => $php->id, 'title' => '実践']);
        Lesson::create([
            'course_id'   => $php->id,
            'section_id'  => $phpPrac->id,
            'title'       => 'フォーム処理',
            'content'     => 'ユーザー入力を処理する。',
            'images'      => [$defaultBase64Image],
            'pages'       => 3,
            'duration'    => 200,
        ]);
        Lesson::create([
            'course_id'   => $php->id,
            'section_id'  => $phpPrac->id,
            'title'       => '掲示板アプリ',
            'content'     => 'シンプルなアプリを作成。',
            'images'      => [$defaultBase64Image],
            'pages'       => 3,
            'duration'    => 300,
        ]);

        // ===== JavaScript Basic =====
        $js = Course::create([
            'title'       => 'Basic JavaScript',
            'description' => 'JavaScriptの基礎からDOM操作までを学ぶコースです。',
            'image'       => 'jsbasic.jpg',
            'language'    => 'it',
            'level'       => 'basic',
            'price'       => 7500.00,
        ]);

        $jsIntro = Section::create(['course_id' => $js->id, 'title' => 'JavaScript入門']);
        Lesson::create([
            'course_id'   => $js->id,
            'section_id'  => $jsIntro->id,
            'title'       => 'JavaScriptとは？',
            'content'     => 'ブラウザで動く言語。',
            'images'      => [$defaultBase64Image],
            'pages'       => 2,
            'duration'    => 150,
        ]);
        Lesson::create([
            'course_id'   => $js->id,
            'section_id'  => $jsIntro->id,
            'title'       => '環境準備',
            'content'     => 'ブラウザとエディタを準備。',
            'images'      => [$defaultBase64Image],
            'pages'       => 3,
            'duration'    => 200,
        ]);

        $jsSyntax = Section::create(['course_id' => $js->id, 'title' => '文法基礎']);
        Lesson::create([
            'course_id'   => $js->id,
            'section_id'  => $jsSyntax->id,
            'title'       => '変数と型',
            'content'     => 'let, const, 型について。',
            'images'      => [$defaultBase64Image],
            'pages'       => 3,
            'duration'    => 180,
        ]);
        Lesson::create([
            'course_id'   => $js->id,
            'section_id'  => $jsSyntax->id,
            'title'       => '関数',
            'content'     => '関数の定義と呼び出し。',
            'images'      => [$defaultBase64Image],
            'pages'       => 3,
            'duration'    => 240,
        ]);

        // ===== English Basic =====
        $enBasic = Course::create([
            'title'       => 'English Basic',
            'description' => '日常会話の基礎を学ぶコースです。',
            'image'       => 'englishbasic.jpg',
            'language'    => 'english',
            'level'       => 'basic',
            'price'       => 9000.00,
        ]);

        $enBasicSec1 = Section::create(['course_id' => $enBasic->id, 'title' => 'Greetings']);
        Lesson::create([
            'course_id'   => $enBasic->id,
            'section_id'  => $enBasicSec1->id,
            'title'       => '挨拶',
            'content'     => 'Hello, Good morning など。',
            'images'      => [$defaultBase64Image],
            'pages'       => 3,
            'duration'    => 180,
        ]);
        Lesson::create([
            'course_id'   => $enBasic->id,
            'section_id'  => $enBasicSec1->id,
            'title'       => '自己紹介',
            'content'     => '名前や出身を伝える。',
            'images'      => [$defaultBase64Image],
            'pages'       => 3,
            'duration'    => 200,
        ]);

    }
    private function encodeImages(array $filenames): array
    {
        $base64Array = [];

        foreach ($filenames as $filename) {
            $path = public_path('images/lessons/' . $filename);

            if (file_exists($path)) {
                $mime = mime_content_type($path);
                $base64Array[] = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            } else {
                $base64Array[] = null; 
            }
        }

        return $base64Array;
    }
}
