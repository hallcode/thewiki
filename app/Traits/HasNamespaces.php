<?php

namespace App\Traits;

trait HasNamespaces
{
    public function getHasNamespaceAttribute()
    {
        if ($this->namespace !== null || !emptyString($this->namespace))
        {
            return true;
        }

        return false;
    }

    public function getCombinedTitleAttribute()
    {
        if ($this->hasNamespace)
        {
            return ucfirst($this->namespace) . ':' . $this->title;
        }

        return $this->title;
    }

    public function getCombinedReferenceAttribute()
    {
        if ($this->hasNamespace)
        {
            return ucfirst($this->namespace) . ':' . $this->reference;
        }

        return $this->reference;
    }

    public function setNamespaceAttribute($value)
    {
        $this->attributes['namespace'] = strtolower($value);
    }

    public function scopeNamespace($query, $namespace)
    {
        if ($namespace === null)
        {
            return $query->whereNull('namespace');
        }

        return $query->where('namespace', strtolower($namespace));
    }
}