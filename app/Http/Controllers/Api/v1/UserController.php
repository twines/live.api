<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repository\Api\v1\RoomRepository;
use App\Repository\Api\v1\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    private $userRepository;
    private $roomRepository;

    public function __construct(UserRepository $userRepository, RoomRepository $roomRepository)
    {
        $this->userRepository = $userRepository;
        $this->roomRepository = $roomRepository;
    }

    public function doAuth(Request $request)
    {
        $trueName = $request->get('trueName');
        $cardNumber = $request->get('cardNumber');
        $cardA = $request->get('cardA');
        $cardB = $request->get('cardB');
        $user = auth('api')->user();
        $data = [];
        $data['true_name'] = $trueName;
        $data['card_number'] = $cardNumber;
        $data['card_a'] = $cardA;
        $data['card_b'] = $cardB;
        $data['user_id'] = $user->id;
        if ($this->userRepository->addUserProfile($data)) {
            return $this->success();
        }
        return $this->error();
    }

    public function getUserRoomList()
    {
        $user = auth()->user();
        $roomList = $this->roomRepository->getUserRoomList($user->id);
        return $this->success($roomList->toArray());
    }
}
