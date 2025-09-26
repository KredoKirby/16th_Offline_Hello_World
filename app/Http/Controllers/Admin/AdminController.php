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
                ['id' => 1, 'name' => 'David', 'email' => 'david@gmail.com', 'created_at' => '2025-08-22 06:52:47', 'active' => true,  'avatar' => '/images/avatar1.jpg'],
                ['id' => 2, 'name' => 'Emma',  'email' => 'emma@gmail.com',  'created_at' => '2025-08-22 06:52:47', 'active' => false, 'avatar' => '/images/avatar2.jpg'],
                ['id' => 3, 'name' => 'Liam',  'email' => 'liam@gmail.com',  'created_at' => '2025-08-22 06:52:47', 'active' => true,  'avatar' => '/images/avatar3.jpg'],
                ['id' => 4, 'name' => 'Mia',   'email' => 'mia@gmail.com',   'created_at' => '2025-08-22 06:52:47', 'active' => true,  'avatar' => '/images/avatar4.jpg'],
            ],
            'teachers' => [
                ['id' => 1, 'name' => 'David', 'email' => 'david@gmail.com', 'created_at' => '2025-08-22 06:52:47', 'active' => true,  'avatar' => '/images/avatar1.jpg'],
                ['id' => 2, 'name' => 'Sara',  'email' => 'sara@gmail.com',  'created_at' => '2025-08-22 06:52:47', 'active' => false, 'avatar' => '/images/avatar2.jpg'],
                ['id' => 3, 'name' => 'Ken',   'email' => 'ken@gmail.com',   'created_at' => '2025-08-22 06:52:47', 'active' => true,  'avatar' => '/images/avatar3.jpg'],
                ['id' => 4, 'name' => 'Aya',   'email' => 'aya@gmail.com',   'created_at' => '2025-08-22 06:52:47', 'active' => true,  'avatar' => '/images/avatar4.jpg'],
            ],
            'courses' => [
                ['id' => 1, 'name' => 'PHP',     'active' => true],
                ['id' => 2, 'name' => 'HTML',    'active' => false],
                ['id' => 3, 'name' => 'Laravel', 'active' => true],
            ],
            'forums' => [
                ['question' => 'How to install Laravel?', 'course' => 'Laravel Basics', 'username' => 'Alice'],
                ['question' => 'What is MVC?',            'course' => 'PHP 101',        'username' => 'Bob'],
            ],
        ];
    }

    private function ensureBootstrapped()
    {
        if (!Session::has('admin_data')) {
            Session::put('admin_data', $this->seed());
        }
    }

    public function bootstrap()
    {
        Session::put('admin_data', $this->seed());
        return redirect()->route('admin.index');
    }

    private function data()
    {
        $this->ensureBootstrapped();
        return Session::get('admin_data');
    }
    private function save($data)
    {
        Session::put('admin_data', $data);
    }

    private function findItem(string $type, int $id): ?array
    {
        $d = $this->data();
        foreach ($d[$type] as $row) {
            if (($row['id'] ?? null) === $id) return $row;
        }
        return null;
    }
    private function updateItem(string $type, int $id, callable $mutator): void
    {
        $d = $this->data();
        foreach ($d[$type] as $i => $row) {
            if (($row['id'] ?? null) === $id) {
                $d[$type][$i] = $mutator($row);
                break;
            }
        }
        $this->save($d);
    }

    // ── 画面表示 ────────────────────────────
    public function index()
    {
        $d = $this->data();

        $studentsCount = count($d['students']);
        $teachersCount = count($d['teachers']);
        $coursesCount  = count($d['courses']);
        $forumsCount   = count($d['forums']);

        $latestStudents = array_slice($d['students'], 0, 5);
        $latestTeachers = array_slice($d['teachers'], 0, 5);
        $latestCourses  = array_slice($d['courses'],  0, 5);
        $latestForums   = array_slice($d['forums'],   0, 4);

        return view('admin.index', compact(
            'studentsCount','teachersCount','coursesCount','forumsCount',
            'latestStudents','latestTeachers','latestCourses','latestForums'
        ));
    }

    public function students()
    {
        return view('admin.students.students', ['items' => $this->data()['students']]);
    }

    public function teachers()
    {
        return view('admin.teachers.teachers', ['items' => $this->data()['teachers']]);
    }

    public function courses()
    {
        return view('admin.courses.courses', ['items' => $this->data()['courses']]);
    }

    public function forums()
    {
        return view('admin.forum.forums', ['items' => $this->data()['forums']]);
    }

    // ── アクション：トグル ───────────────────
    private function toggleByType(string $type, int $id)
    {
        $this->updateItem($type, $id, function ($row) {
            $row['active'] = !($row['active'] ?? false);
            return $row;
        });
    }

    public function studentToggle($id) { $this->toggleByType('students', (int)$id); return back(); }
    public function teacherToggle($id) { $this->toggleByType('teachers', (int)$id); return back(); }
    public function courseToggle($id)  { $this->toggleByType('courses',  (int)$id); return back(); }

    // ── Teachers: Add ───────────────────────
    public function teacherAddForm()
    {
        return view('admin.teachers.create');
    }

    public function teacherAdd(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:4',
        ]);

        $data  = $this->data();
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

        // ✅ resourceルートに合わせて .index へ
        return redirect()->route('admin.teachers.index')->with('status', 'Teacher added.');
    }

    // ── Courses: Add / Show / Edit / Update ─────────────────
    public function courseAddForm()
    {
        return view('admin.courses.create');
    }

    public function courseAdd(Request $req)
    {
        $req->validate(['name' => 'required']);

        $data  = $this->data();
        $maxId = collect($data['courses'])->max('id');
        $nextId = ($maxId ?? 0) + 1;

        $data['courses'][] = ['id' => $nextId, 'name' => $req->name, 'active' => true];
        $this->save($data);

        // ✅ resourceルートに合わせて .index へ
        return redirect()->route('admin.courses.index')->with('status', 'Course added.');
    }

    /** 詳細（/admin/courses/{id}） */
    public function courseShow($id)
    {
        $course = $this->findItem('courses', (int)$id);
        abort_if(!$course, 404);
        return view('admin.courses.show', compact('course'));
    }

    /** 編集フォーム（/admin/courses/{id}/edit） */
    public function courseEdit($id)
    {
        $course = $this->findItem('courses', (int)$id);
        abort_if(!$course, 404);
        return view('admin.courses.edit', compact('course'));
    }

    /** 更新（PUT /admin/courses/{id}） */
    public function courseUpdate(Request $req, $id)
    {
        $req->validate(['name' => 'required|string|max:255']);
        $this->updateItem('courses', (int)$id, function ($row) use ($req) {
            $row['name']   = $req->input('name');
            $row['active'] = $req->has('active');
            return $row;
        });

        return redirect()->route('admin.courses.show', $id)->with('status', 'Course updated.');
    }
      // ── Teachers: Edit / Update ─────────────────────────
    /** 編集フォーム（/admin/teachers/{id}/edit） */
    public function teacherEdit($id)
    {
        $t = $this->findItem('teachers', (int)$id);
        abort_if(!$t, 404);

        // ビュー側では $t を使います（例：resources/views/admin/teachers/edit.blade.php）
        return view('admin.teachers.edit', compact('t'));
    }

    /** 更新（PUT /admin/teachers/{id}） */
    public function teacherUpdate(Request $req, $id)
    {
        $req->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            // avatar は任意（テキストパス想定）
        ]);

        $this->updateItem('teachers', (int)$id, function ($row) use ($req) {
            $row['name']   = $req->input('name');
            $row['email']  = $req->input('email');
            $row['active'] = $req->boolean('active'); // on/true のとき true
            if ($req->filled('avatar')) {
                $row['avatar'] = $req->input('avatar');
            }
            return $row;
        });

        return redirect()->route('admin.teachers.index')->with('status', 'Teacher updated.');
    }
}
