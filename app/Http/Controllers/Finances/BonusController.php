<?php

namespace App\Http\Controllers\Finances;

use App\Models\Pv;
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
        $pvModel = new Pv();
        $pvConf = $pvModel->getPvConf();
        $select = DB::raw(
            'sum(level_money) as level_money,
            sum(invite_money) as invite_money,
            sum(retail_money) as retail_money,
            sum(personal_pv) as personal_pv,
            sum(teams_pv) as teams_pv');
        $data = UserBonus::select($select)->where('user_id', $userId)->whereBetween('created_at', $between)->first();
        $data->personal_pv_bonus = $pvModel->getBonus($pvConf, $data->personal_pv);
        $data->teams_pv_bonus = $pvModel->getBonus($pvConf, $data->teams_pv);
        $data->total_bonus = $data->level_money + $data->invite_money + $data->personal_pv_bonus;

        return view('finances/bonus', ['title' => '奖金查询', 'data' => $data]);
    }
}
