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
        Lesson::insert([
            ['section_id' => $phpIntro->id, 'title' => 'PHPとは？', 'content' => 'PHPの歴史と特徴。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $phpIntro->id, 'title' => '環境構築', 'content' => 'XAMPP/MAMPで環境を整える。', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $phpSyntax = Section::create(['course_id' => $php->id, 'title' => '基礎文法']);
        Lesson::insert([
            ['section_id' => $phpSyntax->id, 'title' => '変数と定数', 'content' => '変数と定数の基本。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $phpSyntax->id, 'title' => '条件分岐', 'content' => 'if, switch文。', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $phpPrac = Section::create(['course_id' => $php->id, 'title' => '実践']);
        Lesson::insert([
            ['section_id' => $phpPrac->id, 'title' => 'フォーム処理', 'content' => 'ユーザー入力を処理する。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $phpPrac->id, 'title' => '掲示板アプリ', 'content' => 'シンプルなアプリを作成。', 'created_at' => now(), 'updated_at' => now()],
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
            ['section_id' => $jsIntro->id, 'title' => 'JavaScriptとは？', 'content' => 'ブラウザで動く言語。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $jsIntro->id, 'title' => '環境準備', 'content' => 'ブラウザとエディタを準備。', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $jsSyntax = Section::create(['course_id' => $js->id, 'title' => '文法基礎']);
        Lesson::insert([
            ['section_id' => $jsSyntax->id, 'title' => '変数と型', 'content' => 'let, const, 型について。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $jsSyntax->id, 'title' => '関数', 'content' => '関数の定義と呼び出し。', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $jsDom = Section::create(['course_id' => $js->id, 'title' => 'DOM操作']);
        Lesson::insert([
            ['section_id' => $jsDom->id, 'title' => '要素の取得', 'content' => 'getElementById, querySelector。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $jsDom->id, 'title' => 'イベント処理', 'content' => 'クリックイベントの設定。', 'created_at' => now(), 'updated_at' => now()],
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
            ['section_id' => $pyIntro->id, 'title' => 'Pythonとは？', 'content' => 'AIやデータ分析に強い言語。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $pyIntro->id, 'title' => '環境構築', 'content' => 'PythonとIDEを準備。', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $pySyntax = Section::create(['course_id' => $py->id, 'title' => '基礎文法']);
        Lesson::insert([
            ['section_id' => $pySyntax->id, 'title' => '変数と型', 'content' => '動的型付けの仕組み。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $pySyntax->id, 'title' => 'ループ', 'content' => 'for, whileの使い方。', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $pyData = Section::create(['course_id' => $py->id, 'title' => 'データ処理']);
        Lesson::insert([
            ['section_id' => $pyData->id, 'title' => 'リストと辞書', 'content' => 'Pythonのデータ構造。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $pyData->id, 'title' => 'ファイル操作', 'content' => 'ファイルの読み書き。', 'created_at' => now(), 'updated_at' => now()],
        ]);


        /*
        |--------------------------------------------------------------------------
        | English Courses
        |--------------------------------------------------------------------------
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
            ['section_id' => $enBasicSec1->id, 'title' => '挨拶', 'content' => 'Hello, Good morning など。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $enBasicSec1->id, 'title' => '自己紹介', 'content' => '名前や出身を伝える。', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $enBasicSec2 = Section::create(['course_id' => $enBasic->id, 'title' => 'Shopping']);
        Lesson::insert([
            ['section_id' => $enBasicSec2->id, 'title' => '買い物の会話', 'content' => 'お店で使える表現。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $enBasicSec2->id, 'title' => '値段を尋ねる', 'content' => 'How much is this? の使い方。', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ===== English Advanced =====
        $enAdv = Course::create([
            'title'       => 'English Advanced',
            'description' => 'ディスカッションやプレゼンができる上級英語を学ぶコースです。',
            'image'       => 'englishadvanced.jpg',
            'language'    => 'english',
            'level'       => 'advanced',
        ]);

        $enAdvSec1 = Section::create(['course_id' => $enAdv->id, 'title' => 'Discussion']);
        Lesson::insert([
            ['section_id' => $enAdvSec1->id, 'title' => 'ディベート', 'content' => '賛成・反対を述べる。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $enAdvSec1->id, 'title' => '意見交換', 'content' => '多様な意見を表現する。', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $enAdvSec2 = Section::create(['course_id' => $enAdv->id, 'title' => 'Presentation']);
        Lesson::insert([
            ['section_id' => $enAdvSec2->id, 'title' => 'スライド作成', 'content' => '効果的な資料作り。', 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $enAdvSec2->id, 'title' => '発表練習', 'content' => '自信を持って発表する方法。', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
