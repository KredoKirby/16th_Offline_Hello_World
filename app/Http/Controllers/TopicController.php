<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function toggle(\App\Models\Topic $topic)
    {
        $topic->status = (bool) $topic->status ? 0 : 1;
        $topic->save();
        return back()->with('success', 'Topic status updated.');
    }

}
