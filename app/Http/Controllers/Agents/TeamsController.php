<?php

namespace App\Http\Controllers\Agents;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeamsController extends Controller
{
    public function members()
    {
        return view('agents/teams_members', ['title' => '团队成员']);
    }

    public function levels()
    {
        return view('agents/teams_levels', ['title' => '团队层级图']);
    }
}
