<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function doAuth(Request $request)
    {
        $trueName = $request->get('trueName');
        $cardNumber = $request->get('cardNumber');
        $cardA = $request->get('cardA');
        $cardB = $request->get('cardB');
    }
}
