<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    // コースに紐づくレッスン
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    // コースに紐づく受講記録
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
