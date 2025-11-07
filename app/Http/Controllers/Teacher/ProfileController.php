<?php

namespace App\Http\Controllers\Teacher;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function show($user_id)
{
    // 担当コース（titleだけ使う想定）を一括ロード
    $user = \App\Models\User::with(['courses:id,title'])
        ->findOrFail($user_id);

    // 管理者だけが追加フォームで選べるように、全コースも用意（adminのみ使用）
    $allCourses = [];
    if (auth()->check() && auth()->user()->role === 'admin') {
        $allCourses = \App\Models\Course::select('id','title')->orderBy('title')->get();
    }

    return view('teacher.profile', [
        'user'        => $user,
        'courses'     => $user->courses, // ← View で一覧表示用
        'allCourses'  => $allCourses,    // ← admin だけ Add 用セレクトで使用
    ]);
}

public function update(Request $request, $user_id)
{
    $user = User::findOrFail($user_id);

    // 権限チェック（必要に応じて）
    // abort_unless(auth()->id() === $user->id || auth()->user()?->role === 'admin', 403);

    $validated = $request->validate([
        'name'         => ['required','string','max:255'],
        'email'        => ['required','email','max:255'],
        'about'        => ['nullable','string','max:2000'],
        'meeting_url'  => ['nullable','url','max:2048'],
    ]);

    $user->fill($validated)->save();

    return back()->with('status', 'Profile updated.');
}
}
