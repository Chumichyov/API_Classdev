<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'group', 'description', 'leader_id'];

    public function information()
    {
        return $this->hasOne(CourseInformation::class);
    }

    public function messengers()
    {
        return $this->hasMany(Messenger::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'course_users');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
