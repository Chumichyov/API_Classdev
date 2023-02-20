<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileType extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function files()
    {
        return $this->hasMany(File::class, 'file_type_id');
    }
}
