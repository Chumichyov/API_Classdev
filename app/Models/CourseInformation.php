<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseInformation extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'image_path', 'image_name', 'image_extension', 'custom_image', 'code', 'link'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
