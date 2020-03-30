<?php
/**
 * Created by 莘莘
 * User: 寒云
 * Email: 1355081829@qq.com
 * Date: 2020/3/29
 * Time: 21:54
 */

namespace App\Repository\Api\v1;


use App\Models\Room;

class RoomRepository
{
    public function addRoom($data)
    {
        return Room::create($data);
    }

    public function getRoomByTitle($title)
    {
        return Room::where('title', '=', $title)->first();
    }

    public function getUserRoomList($userId)
    {
        return Room::where('user_id', $userId)->orderby('id', 'desc')->get();
    }

    public function getRoomById($roomId)
    {
        return Room::find($roomId);
    }
}
