<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
     protected $fillable = ['title', 'description'];
    // コースに紐づくレッスン
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    
   public function enrollments()
{
    return $this->belongsToMany(User::class, 'course_user')
                ->withTimestamps();
}

     public function sections()
    {
        return $this->hasMany(Section::class);
    }
    public function users()
{
    return $this->belongsToMany(User::class, 'course_user')->withTimestamps();
}

    // app/Models/Course.php
public function completionRate($userId)
{
    $totalLessons = $this->sections->flatMap->lessons->count();
    $completedLessons = \DB::table('lesson_user')
        ->where('user_id', $userId)
        ->whereIn('lesson_id', $this->sections->flatMap->lessons->pluck('id'))
        ->where('is_completed', 1)
        ->count();

    return $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
}

}
