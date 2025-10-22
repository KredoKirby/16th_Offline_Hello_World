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

    public function users() {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')
                ->withPivot(['status','enrollment_date'])
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

public function getDisplayImageAttribute()
{
    // Base64ならそのまま返す
    if ($this->image && str_starts_with($this->image, 'data:image')) {
        return $this->image;
    }

    // 通常のファイルパスならasset()で返す
    if ($this->image && file_exists(public_path('images/courses/' . $this->image))) {
        return asset('images/courses/' . $this->image);
    }

    // どちらもない場合はデフォルト画像
    return asset('images/default-course.jpg');
}

 public function getImagePathAttribute()
    {
        if ($this->image && str_starts_with($this->image, 'data:image')) {
            // base64文字列そのまま返す
            return $this->image;
        }

        if ($this->image) {
            // 通常ファイルパス
            return asset('images/courses/' . $this->image);
        }

        // デフォルト画像
        return asset('images/courses/sample.jpg');
    }

}
