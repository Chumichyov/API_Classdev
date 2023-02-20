<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mode extends Model
{
    use HasFactory;

    protected $fillable = ['mode'];

    public function extensions()
    {
        return $this->belongsTo(FileExtension::class, 'file_extension_id');
    }
}
