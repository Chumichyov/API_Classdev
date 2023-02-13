<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseInformation extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'photo_path', 'code', 'link'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
