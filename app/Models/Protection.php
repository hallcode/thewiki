<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Protection extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'expires_at'
    ];
    
    public function target()
    {
        return $this->morphTo();
    }

    public function created_by()
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
