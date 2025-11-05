<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Topic;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
       
        // デフォルト画像（Base64）
        // 
        $defaultImagePath = public_path('images/default-course.jpg');
        $defaultBase64 = file_exists($defaultImagePath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($defaultImagePath))
            : null;

       
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

        $phpIntro = Topic::create(['course_id' => $php->id, 'title' => 'Introduction to PHP','name' => 'Introduction to PHP']);
        Lesson::create([
            'course_id'   => $php->id,
            'topic_id'  => $phpIntro->id,
            'title'       => 'PHPとは？',
            'content'     => 'PHPの歴史と特徴。',
            'images'      => $encodeImages(['1_PHP.png','2_PHP.png','3_PHP.png']),
            'pages'       => 3,
            'duration'    => 125,
        ]);
        Lesson::create([
            'course_id'   => $php->id,
            'topic_id'  => $phpIntro->id,
            'title'       => '環境構築',
            'content'     => 'XAMPP/MAMPで環境を整える。',
            'images'      => $encodeImages(['4_PHP.png','5_PHP.png','6_PHP.png']),
            'pages'       => 3,
            'duration'    => 210,
        ]);

        $phpSyntax = Topic::create(['course_id' => $php->id, 'title' => 'Basic Grammar','name' => 'Basic Grammar']);
        Lesson::create([
            'course_id'   => $php->id,
            'topic_id'  => $phpSyntax->id,
            'title'       => '変数と定数',
            'content'     => '変数と定数の基本。',
            'images'      => $encodeImages(['7_PHP.png','8_PHP.png','9_PHP.png']),
            'pages'       => 3,
            'duration'    => 180,
        ]);
        Lesson::create([
            'course_id'   => $php->id,
            'topic_id'  => $phpSyntax->id,
            'title'       => '条件分岐',
            'content'     => 'if, switch文。',
            'images'      => $encodeImages(['10_PHP.png','11_PHP.png','12_PHP.png']),
            'pages'       => 3,
            'duration'    => 240,
        ]);

        $phpPrac = Topic::create(['course_id' => $php->id, 'title' => 'Practice','name' => 'Practice']);
        Lesson::create([
            'course_id'   => $php->id,
            'topic_id'  => $phpPrac->id,
            'title'       => 'フォーム処理',
            'content'     => 'ユーザー入力を処理する。',
            'images'      => $encodeImages(['13_PHP.png','14_PHP.png','15_PHP.png']),
            'pages'       => 3,
            'duration'    => 200,
        ]);
        Lesson::create([
            'course_id'   => $php->id,
            'topic_id'  => $phpPrac->id,
            'title'       => '掲示板アプリ',
            'content'     => 'シンプルなアプリを作成。',
            'images'      => $encodeImages(['16_PHP.png','17_PHP.png','18_PHP.png']),
            'pages'       => 3,
            'duration'    => 300,
        ]);

       
        // ===== JavaScript Basic =====
        // 
        $js = Course::create([
            'title'       => 'Basic JavaScript',
            'description' => 'JavaScriptの基礎からDOM操作までを学ぶコースです。',
            'image'       =>$defaultBase64, 
            'language'    => 'it',
            'level'       => 'basic',
            'price'       => 7500.00,
        ]);

        $jsIntro = Topic::create(['course_id' => $js->id, 'title' => 'Introduction to Javascript','name' => 'Introduction to Javascript']);
        Lesson::create([
            'course_id'   => $js->id,
            'topic_id'  => $jsIntro->id,
            'title'       => 'JavaScriptとは？',
            'content'     => 'ブラウザで動く言語。',
            'images'      => $encodeImages(['1_JS.png','2_JS.png']),
            'pages'       => 2,
            'duration'    => 150,
        ]);
        Lesson::create([
                'course_id'   => $js->id,
                'topic_id'    => $jsIntro->id,
                'title'       => '環境準備',
                'content'     => 'ブラウザとエディタを準備。',
                'images'      => $encodeImages(['3_JS.png','4_JS.png','5_JS.png']),
                'pages'       => 3,
                'duration'    => 200,
            ]);

            $jsSyntax = Topic::create(['course_id' => $js->id, 'title' => 'Grammar Basics', 'name' => 'Grammar Basics']);
            Lesson::create([
                'course_id'   => $js->id,
                'topic_id'    => $jsSyntax->id,
                'title'       => '変数と型',
                'content'     => 'let, const, 型について。',
                'images'      => $encodeImages(['6_JS.png','7_JS.png','8_JS.png']),
                'pages'       => 3,
                'duration'    => 180,
            ]);
            Lesson::create([
                'course_id'   => $js->id,
                'topic_id'    => $jsSyntax->id,
                'title'       => '関数',
                'content'     => '関数の定義と呼び出し。',
                'images'      => $encodeImages(['9_JS.png','10_JS.png','11_JS.png']),
                'pages'       => 3,
                'duration'    => 240,
            ]);

            $jsDom = Topic::create(['course_id' => $js->id, 'title' => 'DOM manipulation', 'name' => 'DOM manipulation']);
            Lesson::create([
                'course_id'   => $js->id,
                'topic_id'    => $jsDom->id,
                'title'       => '要素の取得',
                'content'     => 'getElementById, querySelector。',
                'images'      => $encodeImages(['12_JS.png','13_JS.png','14_JS.png']),
                'pages'       => 3,
                'duration'    => 200,
            ]);
            Lesson::create([
                'course_id'   => $js->id,
                'topic_id'    => $jsDom->id,
                'title'       => 'イベント処理',
                'content'     => 'クリックイベントの設定。',
                'images'      => $encodeImages(['15_JS.png','16_JS.png','17_JS.png']),
                'pages'       => 3,
                'duration'    => 220,
            ]);

        // 
        // ===== English Basic =====
        // 
        $enBasic = Course::create([
            'title'       => 'English Basic',
            'description' => '日常会話の基礎を学ぶコースです。',
            'image'       => $defaultBase64, 
            'language'    => 'english',
            'level'       => 'basic',
            'price'       => 9000.00,
        ]);

        $enBasicSec1 = Topic::create(['course_id' => $enBasic->id, 'title' => 'Greetings','name' => 'Greetings']);
        Lesson::create([
            'course_id'   => $enBasic->id,
            'topic_id'  => $enBasicSec1->id,
            'title'       => '挨拶',
            'content'     => 'Hello, Good morning など。',
            'images'      => $encodeImages(['1E.png','2E.png','3E.png']),
            'pages'       => 3,
            'duration'    => 180,
        ]);
        Lesson::create([
                'course_id'   => $enBasic->id,
                'topic_id'    => $enBasicSec1->id,
                'title'       => '自己紹介',
                'content'     => '名前や出身を伝える。',
                'images'      => $encodeImages(['4E.png','5E.png','6E.png']),
                'pages'       => 3,
                'duration'    => 200,
            ]);

            $enBasicSec2 = Topic::create(['course_id' => $enBasic->id, 'title' => 'Shopping', 'name' => 'Shopping']);
            Lesson::create([
                'course_id'   => $enBasic->id,
                'topic_id'    => $enBasicSec2->id,
                'title'       => '買い物の会話',
                'content'     => 'お店で使える表現。',
                'images'      => $encodeImages(['7E.png','8E.png']),
                'pages'       => 2,
                'duration'    => 320,
            ]);
            Lesson::create([
                'course_id'   => $enBasic->id,
                'topic_id'    => $enBasicSec2->id,
                'title'       => '値段を尋ねる',
                'content'     => 'How much is this? の使い方。',
                'images'      => $encodeImages(['9E.png','10E.png']),
                'pages'       => 2,
                'duration'    => 180,
            ]);
                    


                // ===== Python Basic =====
        $py = Course::create([
            'title'       => 'Basic Python',
            'description' => 'Pythonを使ってプログラミングの基礎とデータ処理を学ぶコースです。',
            'image'       => $defaultBase64,
            'language'    => 'it',
            'level'       => 'basic',
            'price'       => 6000.00,
        ]);

        $pyIntro = Topic::create(['course_id' => $py->id, 'title' => 'Introduction to Python','name' => 'Introduction to Python']);
        Lesson::create([
            'course_id'   => $py->id,
            'topic_id'    => $pyIntro->id,
            'title'       => 'Pythonとは？',
            'content'     => 'AIやデータ分析に強い言語。',
            'images'      => $encodeImages(['1_Py.png','2_Py.png']),
            'pages'       => 2,
            'duration'    => 140,
        ]);
        Lesson::create([
            'course_id'   => $py->id,
            'topic_id'    => $pyIntro->id,
            'title'       => '環境構築',
            'content'     => 'PythonとIDEを準備。',
            'images'      => $encodeImages(['3_Py.png','4_Py.png','5_Py.png']),
            'pages'       => 3,
            'duration'    => 180,
        ]);

        $pySyntax = Topic::create(['course_id' => $py->id, 'title' => 'Basic Grammar', 'name' => 'Basic Grammar']);
        Lesson::create([
            'course_id'   => $py->id,
            'topic_id'    => $pySyntax->id,
            'title'       => '変数と型',
            'content'     => '動的型付けの仕組み。',
            'images'      => $encodeImages(['6_Py.png','7_Py.png','8_Py.png']),
            'pages'       => 3,
            'duration'    => 120,
        ]);
        Lesson::create([
            'course_id'   => $py->id,
            'topic_id'    => $pySyntax->id,
            'title'       => 'ループ',
            'content'     => 'for, whileの使い方。',
            'images'      => $encodeImages(['9_Py.png','10_Py.png','11_Py.png','12_Py.png']),
            'pages'       => 4,
            'duration'    => 220,
        ]);

        $pyData = Topic::create(['course_id' => $py->id, 'title' => 'Data Processing','name' => 'Data Processing']);
        Lesson::create([
            'course_id'   => $py->id,
            'topic_id'    => $pyData->id,
            'title'       => 'リストと辞書',
            'content'     => 'Pythonのデータ構造。',
            'images'      => $encodeImages(['13_Py.png']),
            'pages'       => 1,
            'duration'    => 170,
        ]);
        Lesson::create([
            'course_id'   => $py->id,
            'topic_id'    => $pyData->id,
            'title'       => 'ファイル操作',
            'content'     => 'ファイルの読み書き。',
            'images'      => $encodeImages(['14_Py.png','15_Py.png','16_Py.png']),
            'pages'       => 3,
            'duration'    => 140,
        ]);
    }

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

   
    private function encodeSingleImage(string $filename): ?string
    {
        $path = public_path('images/lessons/thumbs/' . $filename);
        if (file_exists($path)) {
            $mime = mime_content_type($path);
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }
        return null;
    }
}
