<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * 一覧表示
     */
    public function index()
    {
        $courses = Course::all();
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * 新規作成フォーム
     */
    public function create()
    {
        return view('admin.courses.create'); // Bladeの場所に合わせて
    }

    /**
     * 保存処理
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'language' => 'required|string',
            'level' => 'required|string',
            'image' => 'nullable|string', // Base64
        ]);

        $course = new Course();
        $course->title = $request->title;
        $course->price = $request->price ?? 0;
        $course->description = $request->description;
        $course->category = $request->category;
        $course->language = $request->language;
        $course->level = $request->level;

        // Base64画像をStorageに保存
        if ($request->image) {
            $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $request->image);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = time() . '.png';

            // storage/app/public/courses に保存
            Storage::disk('public')->put('courses/' . $imageName, base64_decode($imageData));
            $course->image = 'courses/' . $imageName;
        }

        $course->save();

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully!');
    }

    // ===== PHP Basic =====
        $php = Course::create([
            'title'       => 'Basic PHP',
            'description' => 'PHPを中心に、プログラミングの基礎から実践までを学ぶコースです。',
            'image'       => 'default.jpg',
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

        // ===== JavaScript Basic =====
        $js = Course::create([
            'title'       => 'Basic JavaScript',
            'description' => 'JavaScriptの基礎からDOM操作までを学ぶコースです。',
            'image'       => 'default.jpg',
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
        Lesson::create([
            'course_id'   => $js->id,
            'section_id'  => $jsIntro->id,
            'title'       => '環境準備',
            'content'     => 'ブラウザとエディタを準備。',
            'images'      => $encodeImages(['3_JS.png','4_JS.png','5_JS.png']),
            'pages'       => 3,
            'duration'    => 200,
        ]);

        $jsSyntax = Section::create(['course_id' => $js->id, 'title' => '文法基礎']);
        Lesson::create([
            'course_id'   => $js->id,
            'section_id'  => $jsSyntax->id,
            'title'       => '変数と型',
            'content'     => 'let, const, 型について。',
            'images'      => $encodeImages(['6_JS.png','7_JS.png','8_JS.png']),
            'pages'       => 3,
            'duration'    => 180,
        ]);
        Lesson::create([
            'course_id'   => $js->id,
            'section_id'  => $jsSyntax->id,
            'title'       => '関数',
            'content'     => '関数の定義と呼び出し。',
            'images'      => $encodeImages(['9_JS.png','10_JS.png','11_JS.png']),
            'pages'       => 3,
            'duration'    => 240,
        ]);

        // ===== English Basic =====
        $enBasic = Course::create([
            'title'       => 'English Basic',
            'description' => '日常会話の基礎を学ぶコースです。',
            'image'       => 'default.jpg',
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
        Lesson::create([
            'course_id'   => $enBasic->id,
            'section_id'  => $enBasicSec1->id,
            'title'       => '自己紹介',
            'content'     => '名前や出身を伝える。',
            'images'      => $encodeImages(['4E.png','5E.png','6E.png']),
            'pages'       => 3,
            'duration'    => 200,
        ]);
    }


    /**
     * 編集フォーム
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * 更新処理
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'language' => 'required|string',
            'level' => 'required|string',
            'image' => 'nullable|string', // Base64
        ]);

        $course->title = $request->title;
        $course->price = $request->price ?? 0;
        $course->description = $request->description;
        $course->category = $request->category;
        $course->language = $request->language;
        $course->level = $request->level;

        if ($request->image) {
            // 古い画像を削除
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }

            $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $request->image);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = time() . '.png';

            Storage::disk('public')->put('courses/' . $imageName, base64_decode($imageData));
            $course->image = 'courses/' . $imageName;
        }

        $course->save();

        return redirect()->route('admin.courses')->with('success', 'Course updated successfully!');
    }

    /**
     * 削除処理
     */
    public function destroy(Course $course)
    {
        // 画像も削除
        if ($course->image && Storage::disk('public')->exists($course->image)) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully!');
    }
}
