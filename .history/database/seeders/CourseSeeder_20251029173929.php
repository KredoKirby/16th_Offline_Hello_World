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

        // ✅ ヘルパー関数をここに定義（$this->encodeImages() は下で定義）
        $encodeImages = fn(array $filenames) => $this->encodeImages($filenames);

    //    php
        $php = Course::create([
            'title'       => 'Basic PHP',
            'description' => 'PHPを中心に、プログラミングの基礎から実践までを学ぶコースです。',
            'image'       => $defaultBase64,  
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
            'images'      => $encodeImages(['1_PHP.png','2_PHP.png','3_PHP.png']),
            'pages'       => 3,
            'duration'    => 125,
        ]);
        Lesson::create([
            'course_id'   => $php->id,
            'section_id'  => $phpIntro->id,
            'title'       => '環境構築',
            'content'     => 'XAMPP/MAMPで環境を整える。',
            'images'      => $encodeImages(['4_PHP.png','5_PHP.png','6_PHP.png']),
            'pages'       => 3,
            'duration'    => 210,
        ]);

        $phpSyntax = Section::create(['course_id' => $php->id, 'title' => '基礎文法']);
        Lesson::create([
            'course_id'   => $php->id,
            'section_id'  => $phpSyntax->id,
            'title'       => '変数と定数',
            'content'     => '変数と定数の基本。',
            'images'      => $encodeImages(['7_PHP.png','8_PHP.png','9_PHP.png']),
            'pages'       => 3,
            'duration'    => 180,
        ]);
        Lesson::create([
            'course_id'   => $php->id,
            'section_id'  => $phpSyntax->id,
            'title'       => '条件分岐',
            'content'     => 'if, switch文。',
            'images'      => $encodeImages(['10_PHP.png','11_PHP.png','12_PHP.png']),
            'pages'       => 3,
            'duration'    => 240,
        ]);

        $phpPrac = Section::create(['course_id' => $php->id, 'title' => '実践']);
        Lesson::create([
            'course_id'   => $php->id,
            'section_id'  => $phpPrac->id,
            'title'       => 'フォーム処理',
            'content'     => 'ユーザー入力を処理する。',
            'images'      => $encodeImages(['13_PHP.png','14_PHP.png','15_PHP.png']),
            'pages'       => 3,
            'duration'    => 200,
        ]);
        Lesson::create([
            'course_id'   => $php->id,
            'section_id'  => $phpPrac->id,
            'title'       => '掲示板アプリ',
            'content'     => 'シンプルなアプリを作成。',
            'images'      => $encodeImages(['16_PHP.png','17_PHP.png','18_PHP.png']),
            'pages'       => 3,
            'duration'    => 300,
        ]);

        // -------------------------------------------------------------
        // ===== JavaScript Basic =====（画像もbase64、defaultなし）
        // -------------------------------------------------------------
        $js = Course::create([
            'title'       => 'Basic JavaScript',
            'description' => 'JavaScriptの基礎からDOM操作までを学ぶコースです。',
            'image'       => $this->encodeSingleImage('js-course.png'), // 🟢 thumbs内に画像があるならbase64化
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
            'images'      => $encodeImages(['1_JS.png','2_JS.png']),
            'pages'       => 2,
            'duration'    => 150,
        ]);

        // -------------------------------------------------------------
        // ===== English Basic =====（画像もbase64、defaultなし）
        // -------------------------------------------------------------
        $enBasic = Course::create([
            'title'       => 'English Basic',
            'description' => '日常会話の基礎を学ぶコースです。',
            'image'       => $this->encodeSingleImage('english-course.png'),
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
            'images'      => $encodeImages(['1E.png','2E.png','3E.png']),
            'pages'       => 3,
            'duration'    => 180,
        ]);
    }

    // -------------------------------------------------------------
    // 🔧 各レッスン画像（配列）をbase64に変換
    // -------------------------------------------------------------
    private function encodeImages(array $filenames): array
    {
        $base64Array = [];
        foreach ($filenames as $filename) {
            $path = public_path('lessons/thumbs/' . $filename);
            if (file_exists($path)) {
                $mime = mime_content_type($path);
                $base64Array[] = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            } else {
                $base64Array[] = null;
            }
        }
        return $base64Array;
    }

    // -------------------------------------------------------------
    // 🔧 単体画像（コース画像用）をbase64に変換
    // -------------------------------------------------------------
    private function encodeSingleImage(string $filename): ?string
    {
        $path = public_path('lessons/thumbs/' . $filename);
        if (file_exists($path)) {
            $mime = mime_content_type($path);
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }
        return null;
    }
}
