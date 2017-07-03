<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Role extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'expires_at'
    ];

    protected $fillable = [
        'user_id',
        'type',
        'expires_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeExpired($query)
    {
        return $this
            ->whereNotNull('expires_at')
            ->whereDate('expires_at', '<', Carbon::now()->toDateTimeString());
    }

    public function scopeActive($query)
    {
        return $this
            ->whereNull('expires_at')
            ->orWhere(function ($query) {
                $query->whereDate('expires_at', '>', Carbon::now()->toDateTimeString());
            });
    }

    public function scopeType($query, $type)
    {
        return $this->where('type', $type);
    }
}
