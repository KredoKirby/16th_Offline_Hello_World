<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    private function seed()
    {
        $students = ['Alice', 'Bob', 'Carol', 'Dave'];
        $teachers = ['Mr. Smith', 'Ms. Tan', 'Dr. Lee'];
        $courses  = ['PHP 101', 'Laravel Basics', 'MySQL'];
        $forums   = [
            ['question'=>'How to install Laravel?', 'course'=>'Laravel Basics', 'username'=>'Alice'],
            ['question'=>'What is MVC?', 'course'=>'PHP 101', 'username'=>'Bob'],
        ];
        return compact('students','teachers','courses','forums');
    }

    public function index()
    {
        $data = $this->seed();
        return view('admin.index', $data);
    }

    public function students() { return view('admin.students', ['items'=>$this->seed()['students']]); }
    public function teachers() { return view('admin.teachers', ['items'=>$this->seed()['teachers']]); }
    public function courses()  { return view('admin.courses',  ['items'=>$this->seed()['courses']]); }
    public function forums()   { return view('admin.forums',   ['items'=>$this->seed()['forums']]); }
}
