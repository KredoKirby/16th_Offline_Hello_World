<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Teachers 一覧
     */
    public function index()
    {
        // role_id=2 を Teacher として扱う
        $teachers = User::where('role_id', 2)
            ->with(['courses:id,title'])   // courses リレーション
            ->withCount('courses')         // 担当コース数
            ->orderByDesc('id')
            ->paginate(10);

        // Add モーダル用（複数選択）
        $courses = Course::select('id', 'title')->orderBy('title')->get();

        return view('admin.teachers.index', compact('teachers', 'courses'));
    }

    /**
     * Teacher 作成（モーダルから）
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'email'        => ['required','email','max:255','unique:users,email'],
            'password'     => ['required','string','min:8'],
            'course_ids'   => ['array'],
            'course_ids.*' => ['integer','exists:courses,id'],
        ]);

        $teacher = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'  => 2, // Teacher
            'status'   => $request->input('status', 'active'), // 任意
        ]);

        // コース紐づけ
        $teacher->courses()->sync($data['course_ids'] ?? []);

        return redirect()
            ->route('admin.teachers.index')
            ->with('status', 'Teacher added.');
    }

    /**
     * ステータス切替（active / inactive）
     */
    public function toggle(User $teacher)
    {
        // Teacher 以外は 404
        abort_if($teacher->role_id !== 2, 404);

        $teacher->status = ($teacher->status === 'active') ? 'inactive' : 'active';
        $teacher->save();

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Status updated.');
    }

    /**
     * 単一コースを付与（一覧の行メニュー等から想定）
     */
    public function attach(Request $request, User $user)
    {
        $actor = $request->user();

        // 実行者：admin(role_id=1)のみ
        abort_unless($actor && (int) $actor->role_id === 1, 403);

        // 対象ユーザー：teacher(role_id=2)のみ
        abort_if((int) $user->role_id !== 2, 404);

        $data = $request->validate([
            'course_id' => [
                'required',
                'integer',
                // status=1 のコースのみ許可
                Rule::exists('courses', 'id')->where(fn($q) => $q->where('status', 1)),
            ],
        ]);

        $courseId = $data['course_id'];

        // teacher に紐付け（重複回避）
        $user->skills()->syncWithoutDetaching([$courseId]);

        return back()->with('status', 'Course attached to teacher.');
    }

    public function detach(Request $request, User $user, Course $course)
    {
        $actor = $request->user();

        // 実行者：adminのみ
        abort_unless($actor && (int) $actor->role_id === 1, 403);

        // 対象ユーザー：teacher のみ
        abort_if((int) $user->role_id !== 2, 404);

        // 操作対象は status=1 のコースだけ（仕様に合わせる）
        abort_if((int) $course->status !== 1, 404);

        $user->skills()->detach($course->id);

        return back()->with('status', 'Course removed from teacher.');
    }

    /**
     * 編集フォーム
     */
    public function edit(User $teacher)
    {
        abort_if($teacher->role_id !== 2, 404);

        // 編集画面でもセレクト表示したい場合は一覧と同様に渡す
        $courses = Course::select('id','title')->orderBy('title')->get();

        return view('admin.teachers.edit', compact('teacher','courses'));
    }

    /**
     * 更新処理
     */
    public function update(Request $request, User $teacher)
    {
        abort_if($teacher->role_id !== 2, 404);

        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'email'        => ['required','email','max:255','unique:users,email,' . $teacher->id],
            'status'       => ['nullable','in:active,inactive'],
            'course_ids'   => ['array'],
            'course_ids.*' => ['integer','exists:courses,id'],
            'password'     => ['nullable','string','min:8'], // 任意でパス変更
        ]);

        // 基本情報
        $teacher->name  = $data['name'];
        $teacher->email = $data['email'];

        if (isset($data['status'])) {
            $teacher->status = $data['status'];
        }
        if (!empty($data['password'])) {
            $teacher->password = Hash::make($data['password']);
        }

        $teacher->save();

        // コースの同期（編集画面に複数選択がある想定）
        if ($request->has('course_ids')) {
            $teacher->courses()->sync($data['course_ids'] ?? []);
        }

        return redirect()
            ->route('admin.teachers.index')
            ->with('status', 'Teacher updated.');
    }
}
