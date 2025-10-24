<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['booking_id','status','feedback','next_topic'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function nextTopic()
    {
        return $this->belongsTo(Topic::class, 'next_topic');
    }
}
