<?php

namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('uid')) {
            $user = User::find($request->uid);
        }
        else {
            $user = session('auth_user');
        }

        ///
        $parent = null;
        if ($user->parent_id > 0) {
            $parent = User::find($user->parent_id);
        }

        ///
        $allPv = 0;
        $month = $request->month ?? date('Y-m');
        $teams = (new User)->getMemberPvByMonth($user->id, $month);
        foreach ($teams as $team) {
            $allPv += $team->personal_pv;
        }

        ///
        $title = '团队业绩';
        return view('finances.team', compact('title', 'user', 'parent', 'teams', 'allPv'));
    }
}
