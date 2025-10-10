<?php

namespace App\Http\Controllers\Teacher;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function show($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('student.profile', compact('user'));
    }
}
