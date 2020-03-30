<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Repository\Admin\v1\RoomRepository;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    //
    private $roomRepository;
    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function getRoomList(Request $request)
    {
        $roomList = $this->roomRepository->getRoomList();
        foreach ($roomList as $key => $room) {
            $room->avatar = asset($room->avatar);
            $roomList[$key] = $room;
        }
        return $this->success($roomList->toArray());
    }
}
