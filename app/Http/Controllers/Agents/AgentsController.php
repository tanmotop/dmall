<?php

namespace App\Http\Controllers\Agents;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CodeService;
use App\Models\UserCode;
use App\Models\User;

class AgentsController extends Controller
{
    public $userCodeModel;

    public $userModel;

    public function __construct(UserCode $userCode, User $user)
    {
        $this->userCodeModel = $userCode;
        $this->userModel = $user;
    }

    /**
     * @return view
     */
    public function inactive(Request $request)
    {
        $uid = session('auth_user')->id;
        $users = $this->userModel->getInactiveUsers($uid);

        // 获取更多/分页数据 => 直接返回json
        if ($request->has('dataType') && $request->dataType == 'json') {
            return $users;
        }

        return view('agents/inactive', [
            'title' => '未激活代理商',
            'users' => $users,
        ]);
    }

    /**
     * 邀请码发放(生成邀请码)
     * 
     * @return view
     */
    public function codeSending()
    {
        $user = session('auth_user');
        $date =  date('Y-m-d', strtotime('+15 day'));
        return view('agents/code_sending', [
            'title' => '邀请码发放',
            'user'  => $user,
            'date'  => $date,
        ]);
    }

    /**
     * 邀请码列表
     * 
     * @return view
     */
    public function codes(Request $request)
    {
        $codeType  = $request->input('type', 0);
        $createUid = session('auth_user')->id;
        $codes = $this->userCodeModel->getCodesByType($createUid, $codeType);

        // 获取更多/分页数据 => 直接返回json
        if ($request->has('dataType') && $request->dataType == 'json') {
            return $codes;
        }

        return view('agents/codes', [
            'title' => '邀请码查询',
            'codes' => $codes,
            'codeType' => $codeType,
        ]);
    }

    public function generateCode()
    {
        $codeService = new CodeService;
        $code = $codeService->makeCode();

        return [
            'code' => 10000,
            'data' => $code,
        ];
    }

    /**
     * 发放邀请码
     * 
     * @param  Request $request
     * @return json
     */
    public function issueCode(Request $request)
    {
        $userCodeModel = new UserCode;
        $userCodeModel->create_uid = session('auth_user')->id;
        $userCodeModel->code = $request->code;
        if ($request->expire_time) {
            $userCodeModel->expired_at = new \Carbon\Carbon($request->expire_time);
        }
        if ($userCodeModel->save()) {
            return ['code' => 10000, 'msg' => '邀请码发放成功'];
        } else {
            return ['code' => 10001, 'msg' => '邀请码发放失败'];
        }
    }
}
