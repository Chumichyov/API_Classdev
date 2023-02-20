<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileExtension extends Model
{
    use HasFactory;

    protected $fillable = ['extension', 'mode_id'];

    public function files()
    {
        return $this->hasMany(File::class, 'file_extension_id');
    }

    public function mode()
    {
        return $this->belongsTo(Mode::class);
    }
}
