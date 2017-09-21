<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/9/21
 * Time: 21:34
 */

namespace App\Repositories;


use App\Models\User;

class UserRepository
{
    /**
     * @var User
     */
    protected $user;

    /**
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}