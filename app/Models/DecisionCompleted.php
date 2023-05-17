<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecisionCompleted extends Model
{
    use HasFactory;
    protected $fillable = ['title'];
    protected $table = 'decision_completed';

    public function decisions()
    {
        return $this->hasMany(Decision::class);
    }
}
