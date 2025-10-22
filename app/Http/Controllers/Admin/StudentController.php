<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class StudentController extends Controller
{
    public function index()
    {
        // role_id=3 を Students として取得
        $items = User::where('role_id', 3)
            ->orderByDesc('id')
            ->get(['id','name','email','created_at'])
            ->map(function ($u) {
                return [
                    'id'         => $u->id,
                    'name'       => $u->name,
                    'email'      => $u->email,
                    'created_at' => optional($u->created_at)->format('Y-m-d H:i'),
                    // Bladeが使っているキーに合わせてダミー値を付与
                    'active'     => true,
                    'avatar'     => 'https://ui-avatars.com/api/?name='.urlencode($u->name),
                ];
            });

        // ← あなたのファイルは「resources/views/admin/students/students.blade.php」なので
        return view('admin.students.students', compact('items'));
        // もし view 名が admin/students/index.blade.php なら:
        // return view('admin.students.index', compact('items'));
    }
}
