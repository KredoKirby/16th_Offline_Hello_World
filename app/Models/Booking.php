<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // created_at は使うが updated_at は使わない
    public $timestamps = true;
    // const UPDATED_AT = null;

    protected $fillable = [
        'teacher_id',
        'student_id',
        'course_id',
        'topic_id',
        'date',
        'time',
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
}
