<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /** 初期データ（配列） */
    private function seed()
    {
        return [
            'students' => [
                ['id' => 1, 'name' => 'David', 'email' => 'david@gmail.com', 'created_at' => '2025-08-22 06:52:47', 'active' => true, 'avatar' => '/images/avatar1.jpg'],
                ['id' => 2, 'name' => 'Emma', 'email' => 'emma@gmail.com', 'created_at' => '2025-08-22 06:52:47', 'active' => false, 'avatar' => '/images/avatar2.jpg'],
                ['id' => 3, 'name' => 'Liam', 'email' => 'liam@gmail.com', 'created_at' => '2025-08-22 06:52:47', 'active' => true, 'avatar' => '/images/avatar3.jpg'],
                ['id' => 4, 'name' => 'Mia', 'email' => 'mia@gmail.com', 'created_at' => '2025-08-22 06:52:47', 'active' => true, 'avatar' => '/images/avatar4.jpg'],
            ],
            'teachers' => [
                ['id' => 1, 'name' => 'David', 'email' => 'david@gmail.com', 'created_at' => '2025-08-22 06:52:47', 'active' => true, 'avatar' => '/images/avatar1.jpg'],
                ['id' => 2, 'name' => 'Sara', 'email' => 'sara@gmail.com', 'created_at' => '2025-08-22 06:52:47', 'active' => false, 'avatar' => '/images/avatar2.jpg'],
                ['id' => 3, 'name' => 'Ken', 'email' => 'ken@gmail.com', 'created_at' => '2025-08-22 06:52:47', 'active' => true, 'avatar' => '/images/avatar3.jpg'],
                ['id' => 4, 'name' => 'Aya', 'email' => 'aya@gmail.com', 'created_at' => '2025-08-22 06:52:47', 'active' => true, 'avatar' => '/images/avatar4.jpg'],
            ],
            'courses' => [
                ['id' => 1, 'name' => 'PHP', 'active' => true],
                ['id' => 2, 'name' => 'HTML', 'active' => false],
                ['id' => 3, 'name' => 'Laravel', 'active' => true],
            ],
            'forums' => [
                ['question' => 'How to install Laravel?', 'course' => 'Laravel Basics', 'username' => 'Alice'],
                ['question' => 'What is MVC?', 'course' => 'PHP 101', 'username' => 'Bob'],
            ],
        ];
    }

    /** セッションに初期データ注入（初回だけ） */
    private function ensureBootstrapped()
    {
        if (!Session::has('admin_data')) {
            Session::put('admin_data', $this->seed());
        }
    }

    /** 手動初期化用 */
    public function bootstrap()
    {
        Session::put('admin_data', $this->seed());
        return redirect()->route('admin.index');
    }

    /** 共通データ取得/保存 */
    private function data()
    {
        $this->ensureBootstrapped();
        return Session::get('admin_data');
    }
    private function save($data)
    {
        Session::put('admin_data', $data);
    }

    // ── 画面表示 ────────────────────────────
    public function index()
    {
        $d = $this->data();

        $studentCount = count($d['students']);
        $teacherCount = count($d['teachers']);
        $courseCount = count($d['courses']);
        $forumCount = count($d['forums']);

        $latestStudents = array_slice($d['students'], 0, 5);
        $latestTeachers = array_slice($d['teachers'], 0, 5);
        $latestCourses = array_slice($d['courses'], 0, 5);
        $latestForums = array_slice($d['forums'], 0, 4);

        return view('admin.index', compact(
            'studentCount',
            'teacherCount',
            'courseCount',
            'forumCount',
            'latestStudents',
            'latestTeachers',
            'latestCourses',
            'latestForums'
        ));
    }
    public function students()
    {
        return view('admin.students', ['items' => $this->data()['students']]);
    }
    public function teachers()
    {
        return view('admin.teachers', ['items' => $this->data()['teachers']]);
    }
    public function courses()
    {
        return view('admin.courses', ['items' => $this->data()['courses']]);
    }
    public function forums()
    {
        return view('admin.forums', ['items' => $this->data()['forums']]);
    }

    // ── アクション：トグル ───────────────────
    private function toggleByType(string $type, int $id)
    {
        $data = $this->data();
        foreach ($data[$type] as &$row) {
            if ($row['id'] === $id) {
                $row['active'] = !$row['active'];
                break;
            }
        }
        $this->save($data);
    }

    public function studentToggle($id)
    {
        $this->toggleByType('students', (int) $id);
        return back();
    }
    public function teacherToggle($id)
    {
        $this->toggleByType('teachers', (int) $id);
        return back();
    }
    public function courseToggle($id)
    {
        $this->toggleByType('courses', (int) $id);
        return back();
    }

    // ── Teachers & Courses (Add)追加  ────────────────────────
    // Teachers 追加フォーム
    public function teacherAddForm()
    {
        // 下の Blade を作る: resources/views/admin/teachers/create.blade.php
        return view('admin.teachers.create');
    }

    // Teachers 保存
    public function teacherAdd(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:4',
        ]);

        $data = $this->data();
        $maxId = collect($data['teachers'])->max('id');
        $nextId = ($maxId ?? 0) + 1;

        $data['teachers'][] = [
            'id' => $nextId,
            'name' => $req->name,
            'email' => $req->email,
            'created_at' => date('Y-m-d H:i:s'),
            'active' => true,
            'avatar' => '/images/avatar1.jpg',
        ];
        $this->save($data);

        return redirect()->route('admin.teachers')->with('status', 'Teacher added.');
    }

    // Courses 追加フォーム
    public function courseAddForm()
    {
        // 下の Blade を作る: resources/views/admin/courses/create.blade.php
        return view('admin.courses.create');
    }

    // Courses 保存
    public function courseAdd(Request $req)
    {
        $req->validate(['name' => 'required']);

        $data = $this->data();
        $maxId = collect($data['courses'])->max('id');
        $nextId = ($maxId ?? 0) + 1;

        $data['courses'][] = [
            'id' => $nextId,
            'name' => $req->name,
            'active' => true,
        ];
        $this->save($data);

        return redirect()->route('admin.courses')->with('status', 'Course added.');
    }
}
