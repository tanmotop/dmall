<?php

namespace App\Services;

class AuthService
{
    private $role = 'user';

    /**
     * 添加认证角色
     *
     * @param $role
     *
     * @return $this
     */
    public function role($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * 验证
     *
     * @return bool
     */
    public function check()
    {
        return !!session('auth_' . $this->role);
    }
}