<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HududTopshiriq extends Model
{
    use HasFactory;

    protected $dillable =
    [
        'hudud_id',
        'topshiriq_id',
        'status'
    ];

    public function hudud()
    {
        return $this->belongsTo(Hudud::class , 'hudud_id');
    }

    public function topshiriq()
    {
        return $this->belongsTo(Topshiriq::class , 'topshiriq_id');
    }
}
