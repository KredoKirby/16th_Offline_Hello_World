<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['section_id', 'title', 'content'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function users()
{
    return $this->belongsToMany(User::class)
                ->withPivot('is_completed', 'completed_at')
                ->withTimestamps();
}

    public function completedByUsers()
{
    return $this->belongsToMany(User::class, 'lesson_user')->withTimestamps();
}

public function progress()
{
    return $this->hasMany(Progress::class);
}

public function isCompletedBy($user)
{
    return $this->progress()
                ->where('user_id', $user->id)
                ->where('completed', true)
                ->exists();
}

}
