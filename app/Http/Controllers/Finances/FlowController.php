<?php

namespace App\Http\Controllers\Finances;

use App\Models\RechargeLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlowController extends Controller
{
    public function cost()
    {
        return view('finances/flow_cost', ['title' => '财务流水']);
    }

    public function charge(Request $request)
    {
        $uid = session()->get('auth_user')->id;
        if ($request->has('dataType') && $request->dataType == 'json') {
            return response()->json([
                'code' => 10000,
                'data' => RechargeLog::where('uid', $uid)->orderBy('created_at', 'desc')->get()->toArray()
            ]);
        }

    	return view('finances/flow_charge', ['title' => '财务流水']);
    }
}
