<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPay extends Model
{
    //
    protected $fillable = [
        'room_id',
        'pay_user_id',
        'room_user_id',
        'money',
        'order_number',
    ];
}
