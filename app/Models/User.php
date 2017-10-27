<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/9/21
 * Time: 21:37
 */

namespace App\Models;


use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use ModelTree, AdminBuilder;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'realname', 'avatar', 'id_card_num', 'wechat', 'phone', 'email', 'level', 'invitation_code', 'actived_at', 'parent_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTitleColumn('realname');
        $this->setOrderColumn('level');
    }

    /**
     * 获取未激活的用户
     */
    public function getInactiveUsers($uid)
    {
        $users = $this->where('parent_id', '=', $uid)
            ->where('status', '=', 0)
            ->paginate(5);

        return $users;
    }

    /**
     * 获取团队成员
     */
    public function getTeamMembers($uid)
    {
        $users = $this->where('parent_id', '=', $uid)
            ->where('status', '=', 1)
            ->select('username', 'realname', 'level', 'phone', 'actived_at')
            ->paginate(10);

        foreach (UserLevel::all() as $item) {
            $levels[$item->id] = $item->name;
        }
        $users->each(function($item, $i) use ($levels) {
            $item->level_name = $levels[$item->level];
        });

        return $users;
    }

    /**
     * 获取团队成员信息
     */
    public function getTeamLevelInfo($uid)
    {
        $user = $this->where('id', '=', $uid)->first();
        $user = $this->statUserTeamInfo($user);

        return [
            'my' => $user,
            'members' => [],
        ];
    }

    /**
     * 统计用户团队相关信息
     */
    private function statUserTeamInfo($user)
    {
        if ($user->parent_id == 0) {
            $user->parent_realname = $user->realname;
        } else {
            $user->parent_realname = '待添加';
        }
        $user->team_count = $this->where('parent_id', '=', $user->id)
            ->where('status', '=', 1)
            ->count();
        $user->team_pv = 1000;

        return $user;
    }
}