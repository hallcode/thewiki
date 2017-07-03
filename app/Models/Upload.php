<?php

namespace App\Models;

use App\Traits\HasFavourites;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Model
{
    use SoftDeletes;
    use HasFavourites;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function uploaded_by()
    {
        return $this->belongsTo(User::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function pages()
    {
        return $this->morphedByMany(Page::class, 'attachable');
    }

    public function folders()
    {
        return $this->morphedByMany(Folder::class, 'attachable');
    }

    public function table_rows()
    {
        return $this->morphedByMany(TableRow::class, 'attachable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'parent');
    }

    public function protections()
    {
        return $this->morphMany(Protection::class, 'target');
    }
}
