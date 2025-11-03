<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics';
    protected $primaryKey = 'id';
    public $timestamps = true;

    // 実DBのカラム名に合わせて。title列が無い環境でもOK
    protected $fillable = [
        'course_id',
        'section_id',
        'name',      // ← 実DBにある方
        'content',
        'video',
        'images',
        'thumbs',
        'pages',
        'image',
        'order',
        'duration',
    ];

    protected $casts = [
        'images'   => 'array',
        'thumbs'   => 'array',
        'pages'    => 'integer',
        'order'    => 'integer',
        'duration' => 'integer',
    ];

    // リレーション
    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class, 'course_id');
    }

    // ★アクセサ：Blade側は $topic->title でOK（実体は name を返す）
    public function getTitleAttribute()
    {
        return $this->attributes['name'] ?? null;
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
