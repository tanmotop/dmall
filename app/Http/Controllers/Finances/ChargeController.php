<?php

namespace App\Http\Controllers\Finances;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChargeController extends Controller
{
    public function index()
    {
        echo '跳转到充值页面';
    }

    public function records()
    {
        return view('finances/charge_records', ['title' => '充值记录']);
    }
}
