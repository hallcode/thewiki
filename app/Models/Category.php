<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'title',
        'colour'
    ];

    public function pages()
    {
        return $this->belongsToMany(Page::class);
    }
}
