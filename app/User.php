<?php

namespace App;

use App\Models\Edit;
use DB;
use Carbon\Carbon;
use App\Models\Favourite;
use App\Models\Page;
use App\Models\Role;
use App\Models\Upload;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class, 'created_by_id');
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class, 'uploaded_by_id');
    }

    public function edits()
    {
        return $this->hasMany(Edit::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function hasRole($type)
    {
        $userRoles = DB::table('roles')
            ->select('type')
            ->where('user_id', $this->id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere(function ($query) {
                        $query->whereDate('expires_at', '>', Carbon::now()->toDateTimeString());
                    });
            });

        if (is_array($type))
        {
            $userRoles->whereIn('type', $type);
        }
        else
        {
            $userRoles->where('type', $type);
        }
        
        return $userRoles->count() !== 0 ? true : false ;
    }
}
