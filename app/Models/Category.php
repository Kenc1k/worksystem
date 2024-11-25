<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function topshiriq()
    {
        return $this->hasMany(Topshiriq::class);
    }
    // Category.php
    public function hududs()
    {
        return $this->belongsToMany(Hudud::class)->withPivot('status');
    }

}
