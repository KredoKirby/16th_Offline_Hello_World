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
        return $this->hasMany(Enrollment::class);
    }
   

     public function sections()
    {
        return $this->hasMany(Section::class);
    }
    public function users()
{
    return $this->belongsToMany(User::class, 'course_user')->withTimestamps();
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


}
