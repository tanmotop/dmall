<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserCode;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
	public $userModel;

	public $userCodeModel;

	public function __construct(User $userModel, UserCode $userCodeModel)
	{
		$this->userModel = $userModel;
		$this->userCodeModel = $userCodeModel;
	}

    public function index()
    {
        return view('auth/register', ['title' => '注册']);
    }

    public function contract()
    {
    	return view('auth/contract', ['title' => '注册协议']);
    }

    public function checkInvitationCode(Request $request)
    {
    	return $this->userCodeModel->checkCode($request->code);
    }

    public function submit(RegisterRequest $request)
    {
    	$data = $request->all();
    	$data['password'] = md5($data['password']);
    	$data['level']    = 3;
    	$codeRow = $this->userCodeModel->where('code', $data['invitation_code'])->first();
    	$data['parent_id'] = $codeRow->create_uid;

    	if ($user = $this->userModel->create($data)) {
    		// 更新userCode记录
    		$codeRow->use_uid = $user->id;
    		$codeRow->used_at = \Carbon\Carbon::now();
    		$codeRow->save();
    		return ['code' => 10000, 'msg'  => '注册成功'];
    	} else {
    		return ['code' => 10001, 'msg'  => '注册失败'];
    	}
    } 
}