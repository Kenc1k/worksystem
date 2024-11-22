<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hudud()
    {
        $this->hasMany(Hudud::class);
    }
    public function topshiriq()
    {
        return $this->hasMany(Topshiriq::class); // Replace `Topshiriq` with the actual task model name
    }
    // User.php
    public function hududs()
    {
        return $this->hasMany(Hudud::class);
    }
    
    
    public function tasks()
    {
        return $this->hasManyThrough(
            Topshiriq::class,
            Hudud::class,
            'user_id',    // Foreign key on 'hududs' table
            'id',         // Foreign key on 'topshiriqs' table
            'id',         // Local key on 'users' table
            'topshiriq_id' // Local key on 'hududs' table
        );
    }
    
    

}
