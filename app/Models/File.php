<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['file_type_id', 'task_id', 'decision_id', 'file_extension_id', 'original_name', 'file_name', 'file_path'];

    public function type()
    {
        return $this->belongsTo(FileType::class, 'file_type_id');
    }

    public function extension()
    {
        return $this->belongsTo(FileExtension::class, 'file_extension_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function decision()
    {
        return $this->belongsTo(Decision::class);
    }
}
