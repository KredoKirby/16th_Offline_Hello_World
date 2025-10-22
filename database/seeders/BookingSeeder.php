<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
           DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('bookings')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
        // ===== 事前に既存のIDを取得（存在しない場合は適宜用意してください） =====
        $teacherId = DB::table('users')->where('role_id', 2)->value('id'); // teacher
        $studentId = DB::table('users')->where('role_id', 3)->value('id'); // student
        $courseId  = DB::table('courses')->value('id');
        // そのコースに属するトピック（最初の一つ）
        $topicId   = DB::table('topics')->where('course_id', $courseId)->value('id');

        if (!$teacherId || !$courseId) {
            $this->command->warn('BookingSeeder: teacher or course is missing. Seed users/courses/topics first.');
            return;
        }

        $now = Carbon::now();

        // 時刻を分離（DBはdateとtimeで持つ前提）
        $pastDate  = $now->copy()->subDay()->toDateString();        // 昨日
        $futureDate= $now->copy()->addDay()->toDateString();        // 明日

        // 時刻（:00:00に丸め）
        $hNow      = (int) $now->format('H');
        $pastTime  = Carbon::createFromTime(max(0, $hNow - 2), 0, 0)->format('H:i:s');
        $pastTime2 = Carbon::createFromTime(max(0, $hNow - 1), 0, 0)->format('H:i:s');
        $futureTime= Carbon::createFromTime(min(23, $hNow + 2), 0, 0)->format('H:i:s');
        $futureTime2=Carbon::createFromTime(min(23, $hNow + 3), 0, 0)->format('H:i:s');

        $nowTs     = $now->toDateTimeString();

        DB::table('bookings')->insert([
            // 1) 過去の「空き枠」：student_id, course_id, topic_id, updated_at = null
            [
                'teacher_id' => $teacherId,
                'student_id' => null,
                'course_id'  => null,
                'topic_id'   => null,
                'date'       => $pastDate,
                'time'       => $pastTime,
                'created_at' => $nowTs,
                'updated_at' => null,         // ★ null 明示
            ],
            // 2) 過去の「予約済み」：student/course/topic あり、updated_at あり
            [
                'teacher_id' => $teacherId,
                'student_id' => $studentId,
                'course_id'  => $courseId,
                'topic_id'   => $topicId,
                'date'       => $pastDate,
                'time'       => $pastTime2,
                'created_at' => $nowTs,
                'updated_at' => $nowTs,       // ★ あり
            ],
            // 3) 未来の「空き枠」：student/course/topic なし、updated_at = null
            [
                'teacher_id' => $teacherId,
                'student_id' => null,
                'course_id'  => null,
                'topic_id'   => null,
                'date'       => $futureDate,
                'time'       => $futureTime,
                'created_at' => $nowTs,
                'updated_at' => null,         // ★ null 明示
            ],
            // 4) 未来の「予約済み」：student/course/topic あり、updated_at あり
            //    （将来の予約を既に確定しているパターン）
            [
                'teacher_id' => $teacherId,
                'student_id' => $studentId,
                'course_id'  => $courseId,
                'topic_id'   => $topicId,
                'date'       => $futureDate,
                'time'       => $futureTime2,
                'created_at' => $nowTs,
                'updated_at' => $nowTs,       // ★ あり
            ],
        ]);
    }
}