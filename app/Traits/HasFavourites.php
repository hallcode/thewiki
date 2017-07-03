<?php

namespace App\Traits;

use Auth;
use App\Models\Favourite;

trait HasFavourites
{
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'target');
    }

    public function favourite()
    {
        if ( $this->favourites()->byUser(Auth::id)->count() !== 0 )
        {
            abort(403, "User has already favourited this resource.");
        }

        $favourite = new Favourite();
        $favourite->user = Auth::user();

        return $this->favourites()->save($favourite);
    }

    public function unfavourite()
    {
        $favourite = $this->favourites()->byUser(Auth::id);

        if ( $favourite->count() === 0 )
        {
            abort(403, "User has not favourited this resource.");
        }

        return $favourite->delete();
    }

    public function toggleFavourite()
    {
        if ( $this->favourites()->byUser(Auth::id)->count() !== 0 )
        {
            return $this->unfavourite();
        }
        else
        {
            return $this->favourite();
        }

    }
}