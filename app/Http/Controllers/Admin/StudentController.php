<?php
// app/Http/Controllers/Admin/StudentController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class StudentController extends Controller
{
    public function index()
    {
        // まずは全ユーザーで表示確認（あとで条件を足す）
        $students = User::orderByDesc('id')->paginate(10);
        return view('admin.students.index', compact('students'));
    }
}

