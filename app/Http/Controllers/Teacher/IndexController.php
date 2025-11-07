<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    // public function index()
    // {
    //     return view('teacher.index');
    // }

      public function index()
    {
        $user = Auth::user();
        $hasMeetingUrl = filled(optional($user)->meeting_url); // ← ★ これをBladeに渡す
        return view('teacher.index', compact('hasMeetingUrl'));
        // ↑ viewパスはあなたのBladeに合わせて変更（例: resources/views/teachers/calendar/weekly.blade.php）
    }
}
