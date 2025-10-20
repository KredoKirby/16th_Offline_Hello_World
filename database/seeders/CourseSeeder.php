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

        // -------------------------------------------------------------
        // ===== PHP Basic =====
        // -------------------------------------------------------------
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
            'images'      => $this->encodeImages(['1_PHP.png','2_PHP.png','3_PHP.png']),
            'pages'       => 3,
            'duration'    => 125,
        ]);
        Lesson::create([
            'course_id'   => $php->id,
            'section_id'  => $phpIntro->id,
            'title'       => '環境構築',
            'content'     => 'XAMPP/MAMPで環境を整える。',
            'images'      => $this->encodeImages(['4_PHP.png','5_PHP.png','6_PHP.png']),
            'pages'       => 3,
            'duration'    => 210,
        ]);

        // -------------------------------------------------------------
        // ===== JavaScript Basic =====
        // -------------------------------------------------------------
        $js = Course::create([
            'title'       => 'Basic JavaScript',
            'description' => 'JavaScriptの基礎からDOM操作までを学ぶコースです。',
            'image'       => $defaultBase64,
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
            'images'      => $this->encodeImages(['1_JS.png','2_JS.png']),
            'pages'       => 2,
            'duration'    => 150,
        ]);
        Lesson::create([
            'course_id'   => $js->id,
            'section_id'  => $jsIntro->id,
            'title'       => '環境準備',
            'content'     => 'ブラウザとエディタを準備。',
            'images'      => $this->encodeImages(['3_JS.png','4_JS.png','5_JS.png']),
            'pages'       => 3,
            'duration'    => 200,
        ]);

        // -------------------------------------------------------------
        // ===== Python Basic =====
        // -------------------------------------------------------------
        $py = Course::create([
            'title'       => 'Basic Python',
            'description' => 'Pythonを使ってプログラミングの基礎とデータ処理を学ぶコースです。',
            'image'       => $defaultBase64,
            'language'    => 'it',
            'level'       => 'basic',
            'price'       => 6000.00,
        ]);

        $pyIntro = Section::create(['course_id' => $py->id, 'title' => 'Python入門']);
        Lesson::create([
            'course_id'   => $py->id,
            'section_id'  => $pyIntro->id,
            'title'       => 'Pythonとは？',
            'content'     => 'AIやデータ分析に強い言語。',
            'images'      => $this->encodeImages(['1_Py.png','2_Py.png']),
            'pages'       => 2,
            'duration'    => 140,
        ]);
        Lesson::create([
            'course_id'   => $py->id,
            'section_id'  => $pyIntro->id,
            'title'       => '環境構築',
            'content'     => 'PythonとIDEを準備。',
            'images'      => $this->encodeImages(['3_Py.png','4_Py.png','5_Py.png']),
            'pages'       => 3,
            'duration'    => 180,
        ]);

        // -------------------------------------------------------------
        // ===== English Basic =====
        // -------------------------------------------------------------
        $enBasic = Course::create([
            'title'       => 'English Basic',
            'description' => '日常会話の基礎を学ぶコースです。',
            'image'       => $defaultBase64,
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
            'images'      => $this->encodeImages(['1E.png','2E.png','3E.png']),
            'pages'       => 3,
            'duration'    => 180,
        ]);
        Lesson::create([
            'course_id'   => $enBasic->id,
            'section_id'  => $enBasicSec1->id,
            'title'       => '自己紹介',
            'content'     => '名前や出身を伝える。',
            'images'      => $this->encodeImages(['4E.png','5E.png','6E.png']),
            'pages'       => 3,
            'duration'    => 200,
        ]);
    }

    /**
     * 指定された画像ファイルをBase64に変換して配列で返す
     */
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
