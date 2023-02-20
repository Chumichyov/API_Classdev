<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['file_type_id', 'file_extension_id', 'original_name', 'file_name', 'file_path'];

    public function type()
    {
        return $this->hasOne(FileType::class, 'file_type_id');
    }

    public function extension()
    {
        return $this->hasOne(FileExtension::class, 'file_extension_id');
    }
}
