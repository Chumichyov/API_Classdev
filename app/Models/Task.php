<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'title', 'description', 'type_id', 'is_published'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function decisions()
    {
        return $this->hasMany(Decision::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function type()
    {
        return $this->belongsTo(TaskType::class);
    }
}
