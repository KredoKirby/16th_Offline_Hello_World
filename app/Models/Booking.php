<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Booking extends Model
{
    // created_at は使うが updated_at は使わない
    public $timestamps = true;
    // const UPDATED_AT = null;

    /** 1コマの長さ（分） **/
    public const DURATION_MINUTES = 50;

    protected $fillable = [
        'teacher_id',
        'student_id',
        'course_id',
        'topic_id',
        'date',
        'time',
    ];

    /** 軽い型付け（date は Carbon、time は文字列のまま使う） */
    protected $casts = [
        'date' => 'date:Y-m-d',
        'time' => 'string',
    ];

    /** Relationship: booking belongs to a teacher (User) */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /** Relationship: booking belongs to a student (User) */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /** Relationship: booking belongs to a course */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /** Relationship: booking belongs to a topic */
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function report()
    {
        return $this->hasOne(Report::class, 'booking_id');
    }

     /* ===================== 便利アクセサ =====================
       - FullCalendar やAPIでそのまま返せる ISO8601 を提供
       - 画面表示に使える Carbon も提供
       ===================================================== */

    /** 画面用：開始/終了の Carbon（アプリTZで“解釈”） */
    public function startCarbon(string $tz = null): Carbon
{
    $tz = $tz ?: config('app.timezone', 'Asia/Tokyo');

    // date を 'Y-m-d' 文字列に正規化
    $rawDate = $this->getAttribute('date');
    $dateStr = $rawDate instanceof Carbon ? $rawDate->format('Y-m-d') : (string) $rawDate;

    // time を 'H:i:s' 文字列に正規化（'H:i' なら ':00' を足す）
    $rawTime = $this->getAttribute('time'); // TIME型なら通常は 'HH:MM:SS' の文字列
    if ($rawTime instanceof Carbon) {
        $timeStr = $rawTime->format('H:i:s');
    } else {
        $timeStr = (string) $rawTime;
        if (preg_match('/^\d{2}:\d{2}$/', $timeStr)) {
            $timeStr .= ':00';
        }
    }

    // ここまで来れば "YYYY-mm-dd HH:ii:ss" に統一されている
    return Carbon::createFromFormat('Y-m-d H:i:s', "$dateStr $timeStr", $tz);
}

public function endCarbon(string $tz = null): Carbon
{
    return $this->startCarbon($tz)->copy()->addMinutes(self::DURATION_MINUTES ?? 50);
}

    /** API用：ISO8601（FullCalendarに最適） */
    protected function startIso(): Attribute
    {
        return Attribute::get(fn () => $this->startCarbon()->toIso8601String());
    }

    protected function endIso(): Attribute
    {
        return Attribute::get(fn () => $this->endCarbon()->toIso8601String());
    }

    /** JSON に含めたい仮想属性 */
    protected $appends = ['start_iso', 'end_iso'];

    /* ===================== クエリ スコープ ===================== */

    /**
     * ログインユーザーの役割に応じて「自分の予約だけ」に絞る。
     * 例: Booking::forUser(auth()->user())->get();
     */
    public function scopeForUser($query, $user)
    {
        return $query->when(
            $user->role === 'teacher',
            fn ($q) => $q->where('teacher_id', $user->id),
            fn ($q) => $q->where('student_id', $user->id)
        );
    }

    /**
     * ある講師の特定日時と重なる予約があるか（50分固定前提）
     * 重複チェックに利用。
     */
    public function scopeOverlapsForTeacher($query, int $teacherId, string $date, string $time, ?int $exceptId = null)
    {
        $tz = config('app.timezone', 'Asia/Tokyo');
        $start = Carbon::createFromFormat('Y-m-d H:i', "{$date} {$time}", $tz);
        $end   = (clone $start)->addMinutes(self::DURATION_MINUTES);

        return $query->where('teacher_id', $teacherId)
            ->where('date', $date)
            ->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
            ->where(function ($q) use ($start, $end, $tz) {
                // DBは date+time 列なので、同日の行に対して time で範囲を判定
                $q->whereRaw('TIME_FORMAT(time, "%H:%i") < ?', [$end->format('H:i')])
                  ->whereRaw('ADDTIME(time, SEC_TO_TIME(?*60)) > ?', [self::DURATION_MINUTES, $start->format('H:i')]);
            });
    }

    // 仮想属性：date_ymd（'YYYY-MM-DD'）
public function getDateYmdAttribute(): ?string
{
    $raw = $this->getAttribute('date');
    if (!$raw) return null;
    return $raw instanceof Carbon ? $raw->format('Y-m-d') : (string) $raw;
}

// 仮想属性：time_hms（'HH:MM:SS'）
public function getTimeHmsAttribute(): ?string
{
    $raw = $this->getAttribute('time');
    if (!$raw) return null;

    if ($raw instanceof Carbon) {
        return $raw->format('H:i:s');
    }
    $s = (string) $raw;
    return preg_match('/^\d{2}:\d{2}$/', $s) ? $s . ':00' : $s;
}
}
