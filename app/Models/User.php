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
use Illuminate\Support\Facades\DB;

class User extends Model
{
    use ModelTree, AdminBuilder;

    /**
     * 等级
     * @var int
     */
    public static $LEVEL_DIAMOND = 1;
    public static $LEVEL_DEALER = 2;
    public static $LEVEL_VIP = 3;

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

    public function orders()
    {
        return $this->hasMany(Orders::class, 'user_id');
    }

    public function bonus()
    {
        return $this->hasMany(UserBonus::class, 'user_id');
    }

    public function userLevel()
    {
        return $this->hasOne(UserLevel::class, 'level', 'level');
    }

    /**
     * 前置0
     */
    public function getIdAttribute($id)
    {
        return substr(1000000 + $id, 1);
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
            ->select('id', 'username', 'realname', 'level', 'phone', 'actived_at')
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
        $user = $this->where('id', $uid)->first();
        $user = $this->statUserTeamInfo($user);

        ///
        $levels = (new UserLevel)->getLevelNameArray();
        $members = $this->where([
            ['parent_id', $uid],
            ['status', 1]
        ])->get();

        ///
        $members->each(function ($item, $i) use ($levels) {
            $item = $this->statUserTeamInfo($item);
            $item->level_name = $levels[$item->level];
        });

        return [
            'my' => $user,
            'members' => $members,
        ];
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function getMemberCount($uid)
    {
        $count = $this->where([
            ['parent_id', '=', $uid],
            ['status', '=', 1]
        ])->count();

        return $count;
    }

    public function getMemberPvByMonth($uid, $month)
    {
        $members = $this->where([
            ['parent_id', '=', $uid],
            ['status', '=', 1]
        ])->orWhere('id', $uid)->get();

        ///
        $between = [date('Y-m-01 00:00:00', strtotime($month)), date('Y-m-t 23:59:59', strtotime($month))];
        $members->each(function ($member, $i) use ($between) {
            $pv = UserBonus::where('user_id', $member->id)->whereBetween('created_at', $between)->sum('personal_pv');
            $member->personal_pv = $pv;
        });

        ///
        return $members;
    }

    /**
     * @param $uid
     * @return array
     */
    public function getTeamsPayRank($uid)
    {
        $self = $this->where('id', $uid)->first();
        $count = $self->orders->count();
        $totalPrice = $self->orders->sum('total_price');
        $totalPv = $self->orders->sum('total_pv');

        ///
        $members = $this->getMembersPayRank($uid);
        $count += $members['count'];
        $totalPrice += $members['total_price'];
        $totalPv += $members['total_pv'];

        return [
            'count' => $count,
            'total_price' => $totalPrice,
            'total_pv' => $totalPv
        ];
    }

    /**
     * @param $uid
     * @return int
     */
    public function getTeamsPv($uid)
    {
        $self = $this->where('id', $uid)->first();
        $pv = $self->bonus->sum('personal_pv');

        $pv += $this->getMembersPv($uid);

        return $pv;
    }

    /**
     * @param $uid
     * @return int
     */
    private function getMembersPv($uid)
    {
        $totalPv = 0;
        $users = $this->where('parent_id', $uid)->get();
        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                $pv = $this->getMembersPv($user->id);
                $totalPv += $pv + $user->bonus->sum('personal_pv');
            }
        }

        return $totalPv;
    }

    /**
     * @param $uid
     * @return array
     */
    private function getMembersPayRank($uid)
    {
        $count = 0;
        $totalPrice = 0;
        $totalPv = 0;

        ///
        $users = $this->where('parent_id', $uid)->get();
        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                $rank = $this->getMembersPayRank($user->id);

                $count += $rank['count'] + $user->orders->count();
                $totalPrice += $rank['total_price'] + $user->orders->sum('total_price');
                $totalPv += $rank['total_pv'] + $user->orders->sum('total_pv');
            }
        }

        return [
            'count' => $count,
            'total_price' => $totalPrice,
            'total_pv' => $totalPv
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

        $data = $this->select(DB::raw('COUNT(*) as team_count,SUM(`pv`) as team_pv'))
            ->where([
                ['parent_id', '=', $user->id],
                ['status', '=', 1]
            ])->first();

        ///
        $user->team_count = $data->team_count;
        $user->team_pv = $data->team_pv + $user->pv;

        return $user;
    }
}