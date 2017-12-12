<?php

namespace App\Models;

use App\Traits\HasFavourites;
use App\Traits\Versionable;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Contracts\Encryption\DecryptException;

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

    public function redirects()
    {
        return $this->hasMany(Redirect::class);
    }

    public function setInfoboxAttribute($value)
    {
        $this->attributes['infobox'] = encrypt($value);
    }

    public function getInfoboxAttribute()
    {
        // Try to decrypt, if it fails, return raw string
        // This is a dev thing, remove in prod
        try
        {
            $infoboxDecrypted = decrypt($this->attributes['infobox']);
            return new Infobox($infoboxDecrypted);
        }
        catch (DecryptException $e)
        {
            return new Infobox($this->attributes['infobox']);
        }


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
            'Attachments' => '#',
            'InfoBox' => route('infobox.edit', ['reference' => $this->reference]),
        ];
    }

    public function wordCountChange($time)
    {
        $versions = $this->versions()->before($time)->limit(2)->get();

        return $versions->first()->word_count - $versions->last()->word_count;
    }

}
