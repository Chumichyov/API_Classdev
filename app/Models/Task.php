<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'title', 'description'];

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
        return $this->hasMany(TaskFile::class);
    }
}
