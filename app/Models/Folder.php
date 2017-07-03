<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function parent()
    {
        return $this->morphTo();
    }

    public function attachments()
    {
        return $this->morphToMany(Upload::class, 'attachable');
    }
}
