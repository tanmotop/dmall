<?php

namespace App\Http\Controllers\Agents;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class TeamsController extends Controller
{
	private $userModel;

	public function __construct(User $user)
	{
		$this->userModel = $user;
	}

    public function members(Request $request)
    {
    	$uid = session('auth_user')->id;
    	$users = $this->userModel->getTeamMembers($uid);

    	// 获取更多/分页数据 => 直接返回json
        if ($request->has('dataType') && $request->dataType == 'json') {
            return $users;
        }

        return view('agents/teams_members', [
        	'title' => '团队成员',
        	'users' => $users,
        ]);
    }

    public function levels($id = null)
    {
    	$uid = $id ? $id : session('auth_user')->id;
    	$teamLevelInfo = $this->userModel->getTeamLevelInfo($uid);

        return view('agents/teams_levels', [
        	'title' => '团队层级图',
        	'teamLevelInfo' => $teamLevelInfo,
        ]);
    }
}
