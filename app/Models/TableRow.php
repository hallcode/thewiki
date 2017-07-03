<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Yaml\Yaml;

class TableRow extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class);
    }

    public function getCellsAttribute()
    {
        return Yaml::parse($this->attributes['cells']);
    }

    public function setCellsAttribute($value)
    {
        if (!is_array($value))
        {
            throw new \InvalidArgumentException;
        }

        $this->attributes['cells'] = Yaml::dump($value);
    }

    public function attachments()
    {
        return $this->morphToMany(Upload::class, 'attachable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'parent');
    }
}
