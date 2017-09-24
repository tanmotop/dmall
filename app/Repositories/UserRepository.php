<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/9/21
 * Time: 21:34
 */

namespace App\Repositories;


use App\Models\User;
use App\Models\UserCode;
use App\Services\CodeService;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var UserCode
     */
    protected $userCode;

    /**
     * @var CodeService
     */
    protected $codeService;

    /**
     * UserRepository constructor.
     * @param User $user
     * @param UserCode $userCode
     * @param CodeService $codeService
     */
    public function __construct(User $user, UserCode $userCode, CodeService $codeService)
    {
        $this->user = $user;
        $this->userCode = $userCode;
        $this->codeService = $codeService;
    }

    /**
     * @return string
     */
    public function makeInviteCode()
    {
        return $this->codeService->makeInviteCode();
    }

    /**
     * @param $createUid
     * @param string $code
     * @return bool
     */
    public function grantInviteCode($createUid, string $code)
    {
        return DB::table('user_codes')->insert([
            'create_uid' => $createUid,
            'code' => $code,
            'create_at' => date('Y-m-d H:i:s')
        ]);
    }
}