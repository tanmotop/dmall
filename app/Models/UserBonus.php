<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/21
 * Time: 14:30
 * Function:
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserBonus extends Model
{
    protected $table = 'user_bonus';

    protected $fillable = ['user_id', 'level_money', 'invite_money', 'retail_money', 'personal_pv', 'teams_pv', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getUserIdAttribute($id)
    {
        return substr(1000000 + $id, 1);
    }

    /**
     * @param $userId
     * @param $money
     */
    public function saveActualSales($userId, $money)
    {
        $this->saveData($userId, 'actual_sales', $money);
    }

    /**
     * @param $userId
     * @param $money
     */
    public function saveLevelMoney($userId, $money)
    {
        $this->saveData($userId, 'level_money', $money);
    }

    /**
     * @param $userId
     * @param $pv
     */
    public function savePersonalPv($userId, $pv)
    {
        $this->saveData($userId, 'personal_pv', $pv);
    }

    /**
     * @param $userId
     * @param $money
     */
    public function saveInviteMoney($userId, $money)
    {
        $this->saveData($userId, 'invite_money', $money);
    }

    /**
     * @param $userId
     * @param $money
     */
    public function saveRetailMoney($userId, $money)
    {
        $this->saveData($userId, 'retail_money', $money);
    }

    /**
     * @param $userId
     * @param $pv
     */
    public function saveTeamsPv($userId, $pv)
    {
        $this->saveData($userId, 'teams_pv', $pv);
    }

    /**
     * @param $userId
     * @param $key
     * @param $value
     */
    protected function saveData($userId, $key, $value)
    {
        $date = date('Y-m-d');
        $userBonus = $this->exists($userId, $date);

        if (empty($userBonus)) {
            $this->create([
                'user_id' => $userId,
                'date' => $date,
                $key => $value
            ]);
        }
        else {
            $userBonus->$key += $value;
            $userBonus->save();
        }
    }

    /**
     * @param $userId
     * @param $date
     * @return mixed
     */
    protected function exists($userId, $date)
    {
        return $this->where([
            ['user_id', $userId],
            ['date', $date]
        ])->first();
    }


    public function delInviteMoney($userId, $money, $actived_at)
    {
        $userBonus = $this->exists($userId, $actived_at);
        
        if($userBonus) {
            $userBonus->invite_money -= $money;
            $userBonus->save();
        }
    }
}