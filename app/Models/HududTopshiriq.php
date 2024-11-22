<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HududTopshiriq extends Model
{
    use HasFactory;

    protected $fillable = [
        'hudud_id',
        'topshiriq_id',
        'status'
    ];

    // Status constants
    const STATUS_SEND = 'SEND';          // Default status
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';

    // Status list for dropdown or reference
    public static $statuses = [
        self::STATUS_SEND => 'Yuborilgan',
        self::STATUS_COMPLETED => 'Bajarilgan',
        self::STATUS_REJECTED => 'Qaytarilgan',
        self::STATUS_EXPIRED => 'Muddat o\'tgan'
    ];

    protected $attributes = [
        'status' => self::STATUS_SEND  // Set default status
    ];

    public function hudud()
    {
        return $this->belongsTo(Hudud::class);
    }

    public function topshiriq()
    {
        return $this->belongsTo(Topshiriq::class);
    }

    // Helper method to get status label
    public function getStatusLabelAttribute()
    {
        return self::$statuses[$this->status] ?? 'Noaniq';
    }

    // Helper method to get status badge class
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_SEND => 'badge-info',
            self::STATUS_COMPLETED => 'badge-success',
            self::STATUS_REJECTED => 'badge-danger',
            self::STATUS_EXPIRED => 'badge-warning',
            default => 'badge-secondary'
        };
    }
}