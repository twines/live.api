<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repository\Api\v1\PayRepository;
use App\Repository\Api\v1\RoomRepository;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    //
    private $roomRepository;
    private $payRepository;

    public function __construct(RoomRepository $roomRepository, PayRepository $payRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->payRepository = $payRepository;
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

    public function joinRoom(Request $request)
    {
        $userId = $request->get('userId');
        $roomId = $request->get('roomId');
        $room = $this->roomRepository->getRoomById($roomId);
        $ua = $request->userAgent();
        if ($ua == 'android' || $ua = 'ios') {
            if (!$room) {
                return $this->error();
            }
            if (!$room->free) {
                $pay = $this->payRepository->getRoomPay($roomId, $userId);
                if (!$pay) {
                    return $this->error();
                }
            }
            return $this->success();
        } else {
            if (!$room) {
                abort(404);
            }
            if (!$room->free) {
                $pay = $this->payRepository->getRoomPay($roomId, $userId);
                if (!$pay) {
                    abort(403);
                }
            }
            return $this->success();
        }

    }
}
