<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_users');
    }

    public function decisions()
    {
        return $this->hasMany(Decision::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'creator_id');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function information()
    {
        return $this->hasOne(UserInformation::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
