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
        |-------------------------------------------------
        | IT Courses
        |-------------------------------------------------
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
        Lesson::insert([
            [
                'section_id' => $phpIntro->id,
                'title'      => 'PHPとは？',
                'content'    => 'PHPの歴史と特徴。',
                'image'      => 'lesson_php_intro.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $phpIntro->id,
                'title'      => '環境構築',
                'content'    => 'XAMPP/MAMPで環境を整える。',
                'image'      => 'lesson_php_setup.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $phpSyntax = Section::create(['course_id' => $php->id, 'title' => '基礎文法']);
        Lesson::insert([
            [
                'section_id' => $phpSyntax->id,
                'title'      => '変数と定数',
                'content'    => '変数と定数の基本。',
                'image'      => 'lesson_php_variable.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $phpSyntax->id,
                'title'      => '条件分岐',
                'content'    => 'if, switch文。',
                'image'      => 'lesson_php_condition.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $phpPrac = Section::create(['course_id' => $php->id, 'title' => '実践']);
        Lesson::insert([
            [
                'section_id' => $phpPrac->id,
                'title'      => 'フォーム処理',
                'content'    => 'ユーザー入力を処理する。',
                'image'      => 'lesson_php_form.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $phpPrac->id,
                'title'      => '掲示板アプリ',
                'content'    => 'シンプルなアプリを作成。',
                'image'      => 'lesson_php_app.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
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
        Lesson::insert([
            [
                'section_id' => $jsIntro->id,
                'title'      => 'JavaScriptとは？',
                'content'    => 'ブラウザで動く言語。',
                'image'      => 'lesson_js_intro.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $jsIntro->id,
                'title'      => '環境準備',
                'content'    => 'ブラウザとエディタを準備。',
                'image'      => 'lesson_js_setup.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $jsSyntax = Section::create(['course_id' => $js->id, 'title' => '文法基礎']);
        Lesson::insert([
            [
                'section_id' => $jsSyntax->id,
                'title'      => '変数と型',
                'content'    => 'let, const, 型について。',
                'image'      => 'lesson_js_variable.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $jsSyntax->id,
                'title'      => '関数',
                'content'    => '関数の定義と呼び出し。',
                'image'      => 'lesson_js_function.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $jsDom = Section::create(['course_id' => $js->id, 'title' => 'DOM操作']);
        Lesson::insert([
            [
                'section_id' => $jsDom->id,
                'title'      => '要素の取得',
                'content'    => 'getElementById, querySelector。',
                'image'      => 'lesson_js_dom.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $jsDom->id,
                'title'      => 'イベント処理',
                'content'    => 'クリックイベントの設定。',
                'image'      => 'lesson_js_event.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
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
        Lesson::insert([
            [
                'section_id' => $pyIntro->id,
                'title'      => 'Pythonとは？',
                'content'    => 'AIやデータ分析に強い言語。',
                'image'      => 'lesson_py_intro.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $pyIntro->id,
                'title'      => '環境構築',
                'content'    => 'PythonとIDEを準備。',
                'image'      => 'lesson_py_setup.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $pySyntax = Section::create(['course_id' => $py->id, 'title' => '基礎文法']);
        Lesson::insert([
            [
                'section_id' => $pySyntax->id,
                'title'      => '変数と型',
                'content'    => '動的型付けの仕組み。',
                'image'      => 'lesson_py_variable.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $pySyntax->id,
                'title'      => 'ループ',
                'content'    => 'for, whileの使い方。',
                'image'      => 'lesson_py_loop.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $pyData = Section::create(['course_id' => $py->id, 'title' => 'データ処理']);
        Lesson::insert([
            [
                'section_id' => $pyData->id,
                'title'      => 'リストと辞書',
                'content'    => 'Pythonのデータ構造。',
                'image'      => 'lesson_py_data.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $pyData->id,
                'title'      => 'ファイル操作',
                'content'    => 'ファイルの読み書き。',
                'image'      => 'lesson_py_file.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        /*
        |-------------------------------------------------------------------
        | English Courses
        |-------------------------------------------------------------------
        */

        // ===== English Basic =====
        $enBasic = Course::create([
            'title'       => 'English Basic',
            'description' => '日常会話の基礎を学ぶコースです。',
            'image'       => 'englishbasic.jpg',
            'language'    => 'english',
            'level'       => 'basic',
        ]);

        $enBasicSec1 = Section::create(['course_id' => $enBasic->id, 'title' => 'Greetings']);
        Lesson::insert([
            [
                'section_id' => $enBasicSec1->id,
                'title'      => '挨拶',
                'content'    => 'Hello, Good morning など。',
                'image'      => 'lesson_en_greeting.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $enBasicSec1->id,
                'title'      => '自己紹介',
                'content'    => '名前や出身を伝える。',
                'image'      => 'lesson_en_intro.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $enBasicSec2 = Section::create(['course_id' => $enBasic->id, 'title' => 'Shopping']);
        Lesson::insert([
            [
                'section_id' => $enBasicSec2->id,
                'title'      => '買い物の会話',
                'content'    => 'お店で使える表現。',
                'image'      => 'lesson_en_shopping.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => $enBasicSec2->id,
                'title'      => '値段を尋ねる',
                'content'    => 'How much is this? の使い方。',
                'image'      => 'lesson_en_price.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
