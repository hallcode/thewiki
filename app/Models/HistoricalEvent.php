<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoricalEvent extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'occurred_at'
    ];

    public function scopeOnToday($query)
    {
        $today = Carbon::now();

        return $query
                ->whereMonth('occurred_at', $today->month)
                ->whereDay('occurred_at', $today->day);
    }

    public function scopeOnDate($query, $day, $month)
    {
        return $query
            ->whereMonth('occurred_at', $month)
            ->whereDay('occurred_at', $day);
    }
}
