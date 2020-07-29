<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the document model associated with the user.
     */
    public function document()
    {
        return $this->hasOne(Document::class);
    }

    /**
     * Get the profile model associated with the user.
     */
    public function profile() 
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Get the technologies associated with the user.
     */
    public function technologies() 
    {
        return $this->hasMany(Technology::class)->orderBy('id', 'desc');
    }

    /**
     * Get the employments associated with the user.
     */
    public function employments() 
    {
        return $this->hasMany(Employment::class)->orderBy('current', 'desc')->orderBy('end', 'desc');
    }

    /**
     * Get the projects associated with the user.
     */
    public function projects() 
    {
        return $this->hasMany(Project::class)->orderBy('title', 'desc');
    }
}
