<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Topic;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'course_id'  => ['required', 'integer', 'exists:courses,id'],
            'topic_id'   => ['required', 'integer', 'exists:topics,id'],
        ]);

        $studentId = Auth::id();

        // 対象のbookingを取得（teacherがopenしている枠）
        $booking = Booking::where('id', $validated['booking_id'])->first();

        if (!$booking) {
            return back()->withErrors(['booking_id' => 'Invalid booking ID.'])->withInput();
        }

        // すでに他の生徒が予約していないかチェック
        if ($booking->student_id !== null) {
            return back()->withErrors(['booking_id' => 'This slot has already been booked.'])->withInput();
        }

        // 更新処理（既存レコードを埋める）
        $booking->update([
            'student_id' => $studentId,
            'course_id'  => $validated['course_id'],
            'topic_id'   => $validated['topic_id'],
        ]);

        return redirect()->back()->with('success', 'Booking completed successfully!');
    }

    /**
     * DELETE /students/bookings/{booking}
     * Cancels (deletes) an upcoming booking for the logged-in student.
     */
    public function destroy(Booking $booking, Request $request)
    {
        $user = Auth::user();

        // Guard: only the owner (student) can cancel this booking
        if ((int)$booking->student_id !== (int)$user->id) {
            abort(403, 'You are not allowed to cancel this booking.');
        }

        // Guard: only future (or not-yet-started today) can be canceled
        $now      = Carbon::now(); // uses app timezone
        $today    = $now->toDateString();      // 'YYYY-MM-DD'
        $nowTime  = $now->format('H:i:s');     // 'HH:MM:SS'

        $isFuture = ($booking->date > $today)
                 || ($booking->date === $today && $booking->time >= $nowTime);

        if (!$isFuture) {
            return back()->with('error', 'Past lessons cannot be canceled.');
        }

        // Perform cancellation.
        // If your schema has a status column, you can update instead:
        // $booking->update(['status' => 'canceled']);
        $booking->delete();

        return back()->with('success', 'The booking was canceled.');
    }
}
