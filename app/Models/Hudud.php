<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hudud extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hududTopshiriq()
    {
        return $this->hasMany(HududTopshiriq::class);
    }

    public function topshiriq()
    {
        return $this->hasMany(Topshiriq::class);
    }
    public function tasks()
    {
        return $this->hasManyThrough(
            Topshiriq::class,
            HududTopshiriq::class,
            'hudud_id',     // Foreign key on hudud_topshiriqs table
            'id',           // Foreign key on topshiriqs table
            'id',           // Local key on hududs table
            'topshiriq_id'  // Local key on hudud_topshiriqs table
        );
    }
    public function topshiriqs()
    {
        return $this->belongsToMany(Topshiriq::class, 'hudud_topshiriqs')
                    ->withPivot('status');
    }
    
    // Hudud.php
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withPivot('status');
    }



}
