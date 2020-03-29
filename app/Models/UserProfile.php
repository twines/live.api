<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    //
    protected $fillable = [
        'user_id',
        'true_name',
        'card_number',
        'card_a',
        'card_b',

    ];
}
