<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'course_id', 'task_id', 'decision_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function decision()
    {
        return $this->belongsTo(Decision::class);
    }

    public function type()
    {
        return $this->belongsTo(NotificationType::class, 'type_id');
    }
}
