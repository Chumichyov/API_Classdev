<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskFile extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'user_id', 'file_extension_id', 'original_name', 'file_name', 'file_path'];

    public function extension()
    {
        return $this->belongsTo(FileExtension::class, 'file_extension_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
