<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        // role_id = 2 のユーザーだけを新しい順で一覧
        $teachers = User::where('role_id', 2)
            ->with(['coursesTaught:id,title'])   
            ->withCount('coursesTaught')
            ->orderByDesc('id')   // latest() でもOK
            ->paginate(10);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 2,  // Teacher
        ]);

        return redirect()
            ->route('admin.teachers.index')
            ->with('status', 'Teacher added.'); // ← Blade とキーを合わせる
    }

    public function attach(Request $request, User $user)
    {
        $this->authorize('admin-only');

        $data = $request->validate([
            'course_id' => ['required','exists:courses,id'],
        ]);

        $user->courses()->syncWithoutDetaching([$data['course_id']]);

        return back()->with('status', 'Course added.');
    }

    public function detach(User $user, Course $course)
    {
        $this->authorize('admin-only');

        $user->courses()->detach($course->id);

        return back()->with('status', 'Course removed.');
    }
}
