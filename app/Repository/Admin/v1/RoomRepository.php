<?php
/**
 * Created by 莘莘
 * User: 寒云
 * Email: 1355081829@qq.com
 * Date: 2020/3/30
 * Time: 17:13
 */

namespace App\Repository\Admin\v1;


use App\Models\Room;

class RoomRepository
{
    public function getRoomList()
    {
        return Room::leftjoin('users', 'users.id', '=', 'rooms.user_id')
            ->select('rooms.*', 'users.avatar', 'users.username')
            ->orderby('rooms.id', 'desc')
            ->paginate();
    }
}
