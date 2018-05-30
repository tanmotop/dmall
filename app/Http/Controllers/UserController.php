<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/9/24
 * Time: 9:52
 */

namespace App\Http\Controllers;


use App\Repositories\UserRepository;

class UserController
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * 生成邀请码
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeInviteCode()
    {
        $data = [
            'code' => 10000,
            'invite_code' => $this->userRepository->makeInviteCode()
        ];

        ///
        return response()->json($data);
    }

    /**
     * 发放邀请码
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function grantInviteCode()
    {
        $createUid = 1;
        $inviteCode = 'abc';
        $code = $this->userRepository->grantInviteCode($createUid, $inviteCode) ? 10000 : 10001;

        ///
        return response()->json(['code' => $code]);
    }

    public function showMoney(){
        /***
        $data=[];
        $users=\App\Models\User::all();
        foreach($users as $user){
            $money=0;
            $rlogs=\App\Models\RechargeLog::where([['uid',$user->id],['describe','like','邀代奖励%']])->get();
            foreach($rlogs as $rlog){
                $money+=$rlog->money;
            }
            $data[$user->realname]=$money;
        }
        dd($data);*/
        /**
        $rlogs=\App\Models\RechargeLog::where('describe','like','邀代奖励%')->get();
        foreach($rlogs as $rlog)
        {
            $rlog->delete();
        }
        */
    }
}