<?php

namespace App\Http\Controllers\Student;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // プロフィール表示
    public function show(User $user)
    {
        // $this->authorizeView($user);

        // view: resources/views/student/profile.blade.php
        return view('student.profile', [
            'user' => $user,
        ]);
    }

    // モーダルからの更新
    public function update(Request $request, User $user)
    {
        // $this->authorizeOwnerOrAdmin($user);

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'about' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update($validated);

        return redirect()
            ->route('students.profile.show', ['user' => $user->id])
            ->with('status', 'Profile updated.');
    }

  public function updatePhoto(Request $request, User $user)
{
    // $this->authorizeOwnerOrAdmin($user);

    $request->validate([
        'photo' => ['required', 'image', 'max:2048'],
    ]);

    // 古い画像削除（あれば）
    if (!empty($user->avatar_path)) {
        Storage::disk('public')->delete($user->avatar_path);
    }

    // storage/app/public/avatars/xxx.jpg に保存される
    // 戻り値は "avatars/xxx.jpg"
    $path = $request->file('photo')->store('avatars', 'public');

    // DBには "avatars/xxx.jpg" のみを保存
    $user->avatar_path = $path;
    $user->save();

    return redirect()
        ->route('students.profile.show', ['user' => $user->id])
        ->with('status', 'Photo updated.');
}

    // ========= 権限チェック =========

    // private function authorizeView(User $user): void
    // {
    //     $auth = Auth::user();
    //     if (!$auth) abort(403);

    //     // Admin OK
    //     if ((int) $auth->role_id === 1) return;

    //     // 本人OK
    //     if ($auth->id === $user->id) return;

    //     abort(403);
    // }

    // private function authorizeOwnerOrAdmin(User $user): void
    // {
    //     $auth = Auth::user();
    //     if (!$auth) abort(403);

    //     if ((int) $auth->role_id === 1 || $auth->id === $user->id) return;

    //     abort(403);
    // }
}