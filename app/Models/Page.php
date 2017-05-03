<?php

namespace App\Models;

use App\Traits\HasFavourites;
use App\Traits\Versionable;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;
    use Versionable;
    use HasFavourites;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function created_by()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function attachments()
    {
        return $this->morphToMany(Upload::class, 'attachable');
    }

    public function folders()
    {
        return $this->morphMany(Folder::class, 'parent');
    }

    public function edits()
    {
        return $this->morphMany(Edit::class, 'parent');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'parent');
    }

    public function protections()
    {
        return $this->morphMany(Protection::class, 'target');
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function links_to()
    {
        return $this->hasMany(Interlink::class, 'page_id');
    }

    public function linked_from()
    {
        return $this->hasMany(Interlink::class, 'target_page_id');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst($value);
        $this->attributes['reference'] = str_replace(' ', '_', $this->attributes['title']);
    }

    public function scopeFindByTitle($query, $title)
    {
        return $query->where('title', $title)->orWhere('reference', $title)->get()->first();
    }

    public function tabsLeft()
    {
        return [
            'Page' => route('page.show', ['reference' => $this->reference]),
            'Talk' => '#',
            'Data' => '#',
            'Attachments' => '#'
        ];
    }

}
