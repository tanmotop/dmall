<?php

namespace App\Http\Controllers\Finances;

use App\Models\PayConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChargeController extends Controller
{
    public function index()
    {
        $title = '在线充值';
        $payConfig = PayConfig::find(1);
        return view('finances.charge', compact('title', 'payConfig'));
    }

    public function records()
    {
        return view('finances/charge_records', ['title' => '充值记录']);
    }
}
