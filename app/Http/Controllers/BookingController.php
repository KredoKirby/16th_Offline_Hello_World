<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i', Rule::in($this->timeOptions())],
        ]);

        Booking::create([
            'teacher_id' => Auth::id(),     // ログイン中ユーザー
            'date'       => $validated['date'],
            'time'       => $validated['time'],
            // student_id / topic_id / course_id は指定しない => DB に NULL で入る
            // created_at は自動、updated_at は NULL（モデル設定で無効化）
        ]);

        return redirect()->route('teachers.index');
    }

    // 00:00〜23:00 を "HH:00" で許可（Blade の @for と一致）
    private function timeOptions(): array
    {
        $opts = [];
        for ($h = 0; $h < 24; $h++) {
            $opts[] = sprintf('%02d:00', $h);
        }
        return $opts;
    }
}