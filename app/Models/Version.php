<?php

namespace App\Models;

use App\Parsers\SectionParser;
use App\Parsers\WikiParser;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

use Illuminate\Contracts\Encryption\DecryptException;

class Version extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at'
    ];

    protected $fillable = [
        'markdown',
        'user_id'
    ];

    protected $touches = [
        'parent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->morphTo();
    }
    
    public function getHtmlAttribute()
    {
        $parser = new WikiParser();

        return $parser->parse($this->markdown);
    }

    public function getMarkdownAttribute()
    {
        // Try to decrypt, if it fails, return raw string
        // This is a dev thing, remove in prod
        try
        {
            return decrypt($this->attributes['markdown']);
        }
        catch (DecryptException $e)
        {
            return $this->attributes['markdown'];
        }
    }

    public function setMarkdownAttribute($value)
    {
        $this->attributes['markdown'] = encrypt(strip_tags($value));
        $this->attributes['word_count'] = str_word_count($value);
    }

    public function scopeBefore($query, $time)
    {
        return $query->where('created_at', '<=', $time)
            ->latest();
    }

    public function getSectionsAttribute()
    {
        $sectionParser = new SectionParser($this->markdown);

        return $sectionParser->sections;
    }
}
