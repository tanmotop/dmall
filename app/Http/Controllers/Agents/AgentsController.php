<?php

namespace App\Http\Controllers\Agents;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AgentsController extends Controller
{
    /**
     * @return view
     */
    public function inactive()
    {
        return view('agents/inactive', ['title' => '未激活代理商']);
    }

    /**
     * 邀请码发放(生成邀请码)
     * 
     * @return view
     */
    public  function codeSending()
    {
        return view('agents/code_sending', ['title' => '邀请码发放']);
    }

    /**
     * 邀请码列表
     * 
     * @return view
     */
    public function codes()
    {
        return view('agents/codes', ['title' => '邀请码查询']);
    }
}
