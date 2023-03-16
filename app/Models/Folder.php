<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = ['decision_id', 'task_id', 'folder_id', 'is_main', 'folder_path'];

    public function decision()
    {
        return $this->belongsTo(Decision::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function files()
    {
        return $this->hasMany(DecisionFile::class, 'folder_id');
    }

    public function folder()
    {
        return $this->belongsTo(DecisionFolder::class, 'folder_id');
    }

    public function folders()
    {
        return $this->hasMany(DecisionFolder::class, 'folder_id');
    }
}
