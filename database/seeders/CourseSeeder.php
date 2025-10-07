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
        /*
        |--------------------------------------------------------------------------
        | IT Courses
        |--------------------------------------------------------------------------
        */

        // ===== PHP Basic =====
        $php = Course::create([
            'title'       => 'Basic PHP',
            'description' => 'PHPを中心に、プログラミングの基礎から実践までを学ぶコースです。',
            'image'       => 'phpbasic.jpg',
            'language'    => 'it',
            'level'       => 'basic',
        ]);

        $phpIntro = Section::create(['course_id' => $php->id, 'title' => 'PHP入門']);
        Lesson::create([
            'section_id' => $phpIntro->id,
            'title'      => 'PHPとは？',
            'content'    => 'PHPの歴史と特徴。',
            'images'     => ['1_PHP.png','2_PHP.png','3_PHP.png'],
            'video' => 'LV1.mp4',
            'pages'      => 3,
        ]);
        Lesson::create([
            'section_id' => $phpIntro->id,
            'title'      => '環境構築',
            'content'    => 'XAMPP/MAMPで環境を整える。',
            'images'     => ['4_PHP.png','5_PHP.png','6_PHP.png'],
             'video' => 'LV2.mp4',
            'pages'      => 3,
        ]);

        $phpSyntax = Section::create(['course_id' => $php->id, 'title' => '基礎文法']);
        Lesson::create([
            'section_id' => $phpSyntax->id,
            'title'      => '変数と定数',
            'content'    => '変数と定数の基本。',
            'images'     => ['7_PHP.png','8_PHP.png','9_PHP.png'],
             'video' => 'LV3.mp4',
            'pages'      => 3,
        ]);
        Lesson::create([
            'section_id' => $phpSyntax->id,
            'title'      => '条件分岐',
            'content'    => 'if, switch文。',
            'images'     => ['10_PHP.png','11_PHP.png','12_PHP.png'],
             'video' => 'LV4.mp4',
            'pages'      => 3,
        ]);

        $phpPrac = Section::create(['course_id' => $php->id, 'title' => '実践']);
        Lesson::create([
            'section_id' => $phpPrac->id,
            'title'      => 'フォーム処理',
            'content'    => 'ユーザー入力を処理する。',
            'images'     => ['13_PHP.png','14_PHP.png','15_PHP.png'],
            'pages'      => 3,
        ]);
        Lesson::create([
            'section_id' => $phpPrac->id,
            'title'      => '掲示板アプリ',
            'content'    => 'シンプルなアプリを作成。',
            'images'     => ['16_PHP.png','17_PHP.png','18_PHP.png'],
            'pages'      => 3,
        ]);

        // ===== JavaScript Basic =====
        $js = Course::create([
            'title'       => 'Basic JavaScript',
            'description' => 'JavaScriptの基礎からDOM操作までを学ぶコースです。',
            'image'       => 'jsbasic.jpg',
            'language'    => 'it',
            'level'       => 'basic',
        ]);

        $jsIntro = Section::create(['course_id' => $js->id, 'title' => 'JavaScript入門']);
        Lesson::create([
            'section_id' => $jsIntro->id,
            'title'      => 'JavaScriptとは？',
            'content'    => 'ブラウザで動く言語。',
            'images'     => ['1_JS.png','2_JS.png'],
             'video' => 'LV1.mp4',
            'pages'      => 2,
        ]);
        Lesson::create([
            'section_id' => $jsIntro->id,
            'title'      => '環境準備',
            'content'    => 'ブラウザとエディタを準備。',
            'images'     => ['3_JS.png','4_JS.png','5_JS.png'],
             'video' => 'LV2.mp4',
            'pages'      => 3,
        ]);

        $jsSyntax = Section::create(['course_id' => $js->id, 'title' => '文法基礎']);
        Lesson::create([
            'section_id' => $jsSyntax->id,
            'title'      => '変数と型',
            'content'    => 'let, const, 型について。',
            'images'     => ['6_JS.png','7_JS.png','8_JS.png'],
             'video' => 'LV3.mp4',
            'pages'      => 3,
        ]);
        Lesson::create([
            'section_id' => $jsSyntax->id,
            'title'      => '関数',
            'content'    => '関数の定義と呼び出し。',
            'images'     => ['9_JS.png','10_JS.png','11_JS.png'],
             'video' => 'LV4.mp4',
            'pages'      => 3,
        ]);

        $jsDom = Section::create(['course_id' => $js->id, 'title' => 'DOM操作']);
        Lesson::create([
            'section_id' => $jsDom->id,
            'title'      => '要素の取得',
            'content'    => 'getElementById, querySelector。',
            'images'     => ['12_JS.png','13_JS.png','14_JS.png'],
            'pages'      => 3,
        ]);
        Lesson::create([
            'section_id' => $jsDom->id,
            'title'      => 'イベント処理',
            'content'    => 'クリックイベントの設定。',
            'images'     => ['15_JS.png','16_JS.png','17_JS.png'],
            'pages'      => 3,
        ]);

        // ===== Python Basic =====
        $py = Course::create([
            'title'       => 'Basic Python',
            'description' => 'Pythonを使ってプログラミングの基礎とデータ処理を学ぶコースです。',
            'image'       => 'pythonbasic.jpg',
            'language'    => 'it',
            'level'       => 'basic',
        ]);

        $pyIntro = Section::create(['course_id' => $py->id, 'title' => 'Python入門']);
        Lesson::create([
            'section_id' => $pyIntro->id,
            'title'      => 'Pythonとは？',
            'content'    => 'AIやデータ分析に強い言語。',
            'images'     => ['1_Py.png','2_Py.png'],
             'video' => 'LV1.mp4',
            'pages'      => 2,
        ]);
        Lesson::create([
            'section_id' => $pyIntro->id,
            'title'      => '環境構築',
            'content'    => 'PythonとIDEを準備。',
            'images'     => ['3_Py.png','4_Py.png','5_Py.png'],
             'video' => 'LV2.mp4',
            'pages'      => 3,
        ]);

        $pySyntax = Section::create(['course_id' => $py->id, 'title' => '基礎文法']);
        Lesson::create([
            'section_id' => $pySyntax->id,
            'title'      => '変数と型',
            'content'    => '動的型付けの仕組み。',
            'images'     => ['6_Py.png','7_Py.png','8_Py.png'],
             'video' => 'LV3.mp4',
            'pages'      => 3,
        ]);
        Lesson::create([
            'section_id' => $pySyntax->id,
            'title'      => 'ループ',
            'content'    => 'for, whileの使い方。',
            'images'     => ['9_Py.png','10_Py.png','11_Py.png','12_Py.png'],
             'video' => 'LV4.mp4',
            'pages'      => 4,
        ]);

        $pyData = Section::create(['course_id' => $py->id, 'title' => 'データ処理']);
        Lesson::create([
            'section_id' => $pyData->id,
            'title'      => 'リストと辞書',
            'content'    => 'Pythonのデータ構造。',
            'images'     => ['13_Py.png'],
            'pages'      => 1,
        ]);
        Lesson::create([
            'section_id' => $pyData->id,
            'title'      => 'ファイル操作',
            'content'    => 'ファイルの読み書き。',
            'images'     => ['14_Py.png','15_Py.png','16_Py.png'],
            'pages'      => 3,
        ]);

        // ===== English Courses =====
        $enBasic = Course::create([
            'title'       => 'English Basic',
            'description' => '日常会話の基礎を学ぶコースです。',
            'image'       => 'englishbasic.jpg',
            'language'    => 'english',
            'level'       => 'basic',
        ]);

        $enBasicSec1 = Section::create(['course_id' => $enBasic->id, 'title' => 'Greetings']);
        Lesson::create([
            'section_id' => $enBasicSec1->id,
            'title'      => '挨拶',
            'content'    => 'Hello, Good morning など。',
            'images'     => ['1E.png','2E.png','3E.png'],
             'video' => 'LV2.mp4',
            'pages'      => 3,
        ]);
        Lesson::create([
            'section_id' => $enBasicSec1->id,
            'title'      => '自己紹介',
            'content'    => '名前や出身を伝える。',
            'images'     => ['4E.png','5E.png','6E.png'],
             'video' => 'LV3.mp4',
            'pages'      => 3,
        ]);

        $enBasicSec2 = Section::create(['course_id' => $enBasic->id, 'title' => 'Shopping']);
        Lesson::create([
            'section_id' => $enBasicSec2->id,
            'title'      => '買い物の会話',
            'content'    => 'お店で使える表現。',
            'images'     => ['7E.png','8E.png'],
            'pages'      => 2,
        ]);
        Lesson::create([
            'section_id' => $enBasicSec2->id,
            'title'      => '値段を尋ねる',
            'content'    => 'How much is this? の使い方。',
            'images'     => ['9E.png','10E.png'],
            'pages'      => 2,
        ]);
    }
}
