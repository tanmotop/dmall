<?php

namespace App\Http\Controllers\Agents;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CodeService;
use App\Models\UserCode;

class AgentsController extends Controller
{
    public $userCodeModel;

    public function __construct(UserCode $userCode)
    {
        $this->userCodeModel = $userCode;
    }

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
    public function codeSending()
    {
        $user = session('auth_user');
        return view('agents/code_sending', [
            'title' => '邀请码发放',
            'user'  => $user,
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

        return view('agents/codes', [
            'title' => '邀请码查询',
            'codes' => $codes,
        ]);
    }

    public function generateCode()
    {
        $codeService = new CodeService;
        $code = $codeService->makeInviteCode();

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
