<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Edit extends Model
{
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function parent()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeHomeEdits($query)
    {
        return $query->where('parent_type', 'special:home');
    }
}
