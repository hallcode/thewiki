<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function rows()
    {
        return $this->hasMany(TableRow::class);
    }

    public function getColumnsAttribute()
    {
        return Yaml::parse($this->attributes['cells']);
    }

    public function setColumnsAttribute($value)
    {
        if (!is_array($value))
        {
            throw new \InvalidArgumentException;
        }

        $this->attributes['columns'] = Yaml::dump($value);
    }

    public function edits()
    {
        return $this->morphMany(Edit::class, 'parent');
    }
}
