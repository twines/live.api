<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repository\Api\v1\RoomRepository;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    //
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function addRoom(Request $request)
    {
        $title = $request->get('title');
        $description = $request->get('description');
        if (!$title) {
            return $this->error();
        }
        if (!$description) {
            return $this->error();
        }
        if ($this->roomRepository->getRoomByTitle($title)) {
            return $this->error('房间已经存在');
        }
        $user = auth('api')->user();
        if ($user->status !== 4) {
            return $this->error('你还没有认证，请先认证');
        }
        $data = [];
        $data['title'] = $title;
        $data['description'] = $description;
        $data['user_id'] = $user->id;
        if ($room = $this->roomRepository->addRoom($data)) {
            return $this->success($room->toArray());
        }
        return $this->error('创建房间失败');
    }
}
