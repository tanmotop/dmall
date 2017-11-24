<?php

namespace App\Http\Controllers\Finances;

use App\Models\PayConfig;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChargeController extends Controller
{
    public function index()
    {
        $title = '在线充值';
        $payConfig = PayConfig::find(1);
        $user = session('auth_user');

        ///
        if ($user->status == 1) {
            $levels = (new UserLevel)->getActiveRecharge($user->level);
        }
        else {
            $levels = (new UserLevel)->getUnActiveRecharge();
        }

        ///
        return view('finances.charge', compact('title', 'payConfig', 'levels'));
    }

    public function records()
    {
        return view('finances/charge_records', ['title' => '充值记录']);
    }
}
