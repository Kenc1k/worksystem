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
}
