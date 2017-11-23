<?php

namespace App\Http\Controllers\Finances;

use App\Models\UserBonus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BonusController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('auth_user')->id;

        ///
        $between = [date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')];
        if ($request->has('month')) {
            $month = $request->month;
            $between = [date($month . '-01 00:00:00'), date($month . '-t 23:59:59')];
        }

        ///
        $select = DB::raw(
            'sum(level_money) as level_money,
            sum(invite_money) as invite_money,
            sum(retail_money) as retail_money,
            sum(personal_pv) as personal_pv,
            sum(teams_pv) as teams_pv');
        $data = UserBonus::select($select)->where('user_id', $userId)->whereBetween('created_at', $between)->first();

        return view('finances/bonus', ['title' => '奖金查询', 'data' => $data]);
    }
}
