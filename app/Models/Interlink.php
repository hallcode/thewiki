<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Interlink extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'page_id',
        'target_page_id',
        'link_reference'
    ];

    /**
     * This is the parent page. The one that's linking to something.
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function target_page()
    {
        return $this->belongsTo(Page::class);
    }

    public function getTargetExistsAttribute()
    {
        return $this->target_page_id !== null ? true : false;
    }

    public function scopeForPage($query, $title, $namespace = null)
    {
        if ($namespace !== null)
        {
            return $query
                ->where('link_reference', $namespace . ':' . $title)
                ->orWhere('link_reference', $namespace . ':' . str_replace(' ', '_', $title));
        }

        return $query
            ->where('link_reference', $title)
            ->orWhere('link_reference', str_replace(' ', '_', $title));
    }

    public function scopeRedlinks($query)
    {
        return $query
            ->whereNull('target_page_id')
            ->select('link_reference', DB::raw('count(`link_reference`) as `count`'))
            ->groupBy('link_reference');
    }
}
