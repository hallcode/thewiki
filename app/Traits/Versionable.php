<?php

namespace App\Traits;


use App\Models\Version;

trait Versionable
{
    public function versions()
    {
        return $this->morphMany(Version::class, 'parent');
    }
    
    public function current_version()
    {
        return $this->belongsTo(Version::class);
    }
}