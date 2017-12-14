<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_levels';

    public function getActiveRecharge($level)
    {
        $level = $this->where('level', $level)->first();

        return [$level->name => $level->second_amount];
    }

    public function getUnActiveRecharge()
    {
        $levels = $this->all();

        ///
        $arr = [];
        foreach ($levels as $level) {
            $arr[$level->name] = $level->first_amount;
        }

        ///
        return $arr;
    }

//    public function getIdNameArray()
//    {
//        $levels = [];
//    	foreach ($this->all() as $item) {
//            $levels[$item->id] = $item->name;
//        }
//
//        return $levels;
//    }

    public function getLevelNameArray()
    {
        $levels = [];
        foreach ($this->orderBy('level')->get() as $item) {
            $levels[$item->level] = $item->name;
        }

        return $levels;
    }

    /**
     * 用户层级存储在session => 前台需要用到
     * 
     * @return void
     */
    public function saveUserLevelsToSession()
    {
        $userLevels = $this->getLevelNameArray();
        $firstLevel = 0;
        foreach ($userLevels as $key => $level) {
            if ($key != 0) {
                $firstLevel = $key;
                break;
            }
        }

        session(['user_levels' => [
            'levels' => $userLevels,
            'first_level' => $firstLevel,
        ]]);
    }

    public function getMyBuyLevel()
    {
        $myBuyLevel = session('auth_user')->level;
        if ($myBuyLevel == 0) {
            if (!session()->has('user_levels')) {
                $this->saveUserLevelsToSession();
            }
            $myBuyLevel = session('user_levels')['first_level'];
        }

        return $myBuyLevel;
    }
}
