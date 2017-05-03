<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
