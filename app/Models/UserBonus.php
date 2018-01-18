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
     * @param $pv
     * @return bool
     */
    public function savePv($userId, $pv)
    {
        $date = date('Y-m-d');

        $userBonus = $this->where([
            ['user_id', $userId],
            ['date', $date]
        ])->first();

        if (empty($userBonus)) {
            $this->create([
                'user_id' => $userId,
                'personal_pv' => $pv,
                'date' => $date
            ]);
        }
        else {
            $userBonus->personal_pv = $userBonus->personal_pv + $pv;
            $userBonus->save();
        }

        return true;
    }

    public function getTeamsPv($uid)
    {

    }
}