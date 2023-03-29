<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'task_id', 'description', 'grade_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function files()
    {
        return $this->hasMany(DecisionFile::class);
    }

    public function folders()
    {
        return $this->hasMany(DecisionFolder::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
