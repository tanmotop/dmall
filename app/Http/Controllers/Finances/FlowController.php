<?php

namespace App\Http\Controllers\Finances;

use App\Models\Orders;
use App\Models\RechargeLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlowController extends Controller
{
    protected $uid;

    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $this->uid = $request->session()->get('auth_user')->id;

            return $next($request);
        });
    }

    public function cost(Request $request)
    {
        if ($request->has('dataType') && $request->dataType == 'json') {
            return response()->json([
                'code' => 10000,
                'data' => Orders::where('user_id', $this->uid)->orderBy('created_at', 'desc')->get()->toArray()
            ]);
        }

        return view('finances/flow_cost', ['title' => '财务流水']);
    }

    public function charge(Request $request)
    {
        if ($request->has('dataType') && $request->dataType == 'json') {
            return response()->json([
                'code' => 10000,
                'data' => RechargeLog::where('uid', $this->uid)->orderBy('created_at', 'desc')->get()->toArray()
            ]);
        }

    	return view('finances/flow_charge', ['title' => '财务流水']);
    }
}
