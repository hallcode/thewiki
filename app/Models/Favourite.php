<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    public function target()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
}
