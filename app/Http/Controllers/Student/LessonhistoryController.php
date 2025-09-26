<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LessonhistoryController extends Controller
{
    public function show()
    {
        return view('student.lessonhistory');
    }
}
