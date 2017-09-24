<?php

namespace App\Http\Controllers\Finances;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BonusController extends Controller
{
    public function index()
    {
        return view('finances/bonus', ['title' => '奖金查询']);
    }
}
