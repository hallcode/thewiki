<?php

namespace App\Models;

use App\Parsers\WikiParser;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function setMarkdownAttribute($value)
    {
        $this->attributes['markdown'] = strip_tags($value);
        $this->attributes['word_count'] = str_word_count($value);
    }
}
