<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topshiriq extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'category_id',
        'ijrochi',
        'title',
        'file',
        'muddat',
    ];
    protected $casts = [
        'muddat' => 'date',
    ];
    

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function hududTopshiriq()
    {
        return $this->hasMany(HududTopshiriq::class);
    }
    public function hududs()
    {
        return $this->belongsToMany(Hudud::class, 'hudud_topshiriqs', 'topshiriq_id', 'hudud_id');
    }
    
}
