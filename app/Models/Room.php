<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'cover',
        'free',
    ];

    public function getCoverAttribute($val)
    {
        return asset($val);
    }
    public function getStreamPathAttribute($val)
    {
        return asset($val);
    }

}
