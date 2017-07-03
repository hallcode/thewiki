<?php

namespace App\Models;

use App\Traits\HasFavourites;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    use HasFavourites;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function parent()
    {
        return $this->morphTo();
    }
}
