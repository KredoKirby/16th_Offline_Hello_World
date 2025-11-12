<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /** 一覧 */
    public function index()
    {
        // role_id=2 を Teacher として扱う
        $teachers = User::where('role_id', 2)
            ->with(['courses:id,title'])
            ->orderByDesc('id')
            ->paginate(10);

        // （indexでモーダル追加を使う場合のために取得）
        // $courses = Course::select('id', 'title')->orderBy('title')->get();
        $courses = Course::select('id', 'title')
            ->whereNotIn('title', ['title', 'test'])
            ->orderBy('title')
            ->get();

        return view('admin.teachers.index', compact('teachers', 'courses'));
    }

    /** 追加フォーム */
    public function create()
    {
        // $courses = Course::select('id', 'title')->orderBy('title')->get();
        $courses = Course::select('id', 'title')
            ->whereNotIn('title', ['title', 'test'])
            ->orderBy('title')
            ->get();

        return view('admin.teachers.create', compact('courses'));
    }

    /** 作成 */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
        ]);

        $teacher = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 2,
            'status' => 'active',
        ]);

        // pivot 反映
        $teacher->courses()->sync($data['course_ids'] ?? []);

        return redirect()->route('admin.teachers.index')->with('status', 'Teacher added.');
    }

    /** 編集フォーム */
    public function edit(User $teacher)
    {
        abort_if($teacher->role_id !== 2, 404);

        // $courses = Course::select('id', 'title')->orderBy('title')->get();
        $courses = Course::select('id', 'title')
            ->whereNotIn('title', ['title', 'test'])
            ->orderBy('title')
            ->get();

        return view('admin.teachers.edit', compact('teacher', 'courses'));
    }

    /** 更新 */
    public function update(Request $request, User $teacher)
    {
        abort_if($teacher->role_id !== 2, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $teacher->id],
            'status' => ['nullable', 'in:active,inactive'],
            'password' => ['nullable', 'string', 'min:8'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
        ]);

        $teacher->name = $data['name'];
        $teacher->email = $data['email'];
        if (isset($data['status'])) {
            $teacher->status = $data['status'];
        }
        if (!empty($data['password'])) {
            $teacher->password = Hash::make($data['password']);
        }
        $teacher->save();

        $teacher->courses()->sync($data['course_ids'] ?? []);

        return redirect()->route('admin.teachers.index')->with('status', 'Teacher updated.');
    }

    /** ステータス切替 */
    public function toggle(User $teacher)
    {
        abort_if($teacher->role_id !== 2, 404);

        $teacher->status = ($teacher->status === 'active') ? 'inactive' : 'active';
        $teacher->save();

        return redirect()->route('admin.teachers.index')->with('success', 'Status updated.');
    }

    /** 単一コース付与 */
    public function attach(Request $request, User $user)
    {
        abort_if($user->role_id !== 2, 404);

        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $user->courses()->syncWithoutDetaching([$data['course_id']]);

    // skills リレーションで紐付け
    $user->skills()->syncWithoutDetaching([$data['course_id']]);

    /** コース解除 */
    public function detach(User $user, Course $course)
    {
        abort_if($user->role_id !== 2, 404);

public function detach(User $user, Course $course)
{
    // 同様に middleware で保護済み
    $user->skills()->detach($course->id);

        return back()->with('status', 'Course removed.');
    }
}
