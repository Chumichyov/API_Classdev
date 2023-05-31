<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['file_id', 'folder_id', 'creator_id', 'start', 'end', 'color', 'title', 'description'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
