<?php
/**
 * Created by 莘莘
 * User: 寒云
 * Email: 1355081829@qq.com
 * Date: 2020/3/30
 * Time: 22:35
 */

namespace App\Repository\Api\v1;


use App\Models\RoomPay;

class PayRepository
{
    public function getRoomPay($roomId,$userId)
    {
        return RoomPay::where('room_id', $roomId)
            ->where('pay_user_id')
            ->first();
    }
}
