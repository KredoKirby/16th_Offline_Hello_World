<?php

namespace App\Models;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
   //変更
    protected $table = 'courses';
    protected $fillable = ['title', 'description', 'image_url', 'language', 'level', 'image', 'category'];

    // コースに紐づくレッスン
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }


    // App\Models\Course.php

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledUsers()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('status', 'progress')
            ->withTimestamps();
    }



    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')
            ->withPivot(['status', 'enrollment_date'])
            ->withTimestamps();
    }


    public function completionRate($userId)
    {
        $totalLessons = $this->sections->flatMap->lessons->count();

        $completedLessons = User::find($userId)
            ->completedLessons()
            ->whereIn('lesson_id', $this->sections->flatMap->lessons->pluck('id'))
            ->count();

        return $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
    }

public function topics()
{
    return $this->hasMany(Topic::class);
}

public function teachers()
{
    // 第2引数: テーブル名, 第3引数: 現在モデル側FK（course_id）, 第4引数: 相手側FK（teacher_id）
    return $this->belongsToMany(User::class, 'teacher_course', 'course_id', 'teacher_id');
}


}
