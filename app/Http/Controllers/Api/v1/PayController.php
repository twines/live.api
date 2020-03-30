<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\RoomPay;
use App\Repository\Api\v1\RoomRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PayController extends Controller
{
    //
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function pay(Request $request)
    {
        $roomId = $request->get('roomId');
        $room = $this->roomRepository->getRoomById($roomId);
        $money = $request->get('money');
        if ($room) {
            return $this->error('房间不存在');
        }
        $user = auth()->user();
        DB::beginTransaction();
        $user = User::where('id', $user->id)->lockForUpdate()->first();
        $balance = $user->balance;
        if ($balance < $money) {
            DB::rollBack();
            return $this->error('金币不足，请充值');
        }
        $user->balance = $balance - $money;
        if (!$user->save()) {
            DB::rollBack();
            return $this->error('用户扣款失败');
        }
        $orderNumber = Str::random(16);
        $roomPay = new RoomPay();
        $roomPay->room_id = $room->id;
        $roomPay->room_user_id = $room->user_id;
        $roomPay->pay_user_id = $user->id;
        $roomPay->money = $money;
        $roomPay->order_number = $orderNumber;
        if (!$roomPay->save()) {
            DB::rollBack();
            return $this->error('支付失败');
        }
        DB::commit();
        return $this->success(['orderNumber' => $orderNumber]);
    }
}
