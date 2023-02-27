<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecisionFile extends Model
{
    use HasFactory;

    protected $fillable = ['decision_id', 'user_id', 'file_extension_id', 'original_name', 'file_name', 'file_path'];

    public function extension()
    {
        return $this->belongsTo(FileExtension::class, 'file_extension_id');
    }

    public function decision()
    {
        return $this->belongsTo(Decision::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
