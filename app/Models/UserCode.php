<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/9/22
 * Time: 11:17
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserCode extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_codes';

    protected $fillable = ['*'];

    public function checkCode($code)
    {
    	$row = $this->where('code', $code)->first();
    	if (!$row) {
    		return [
    			'code' => 10001,
    			'msg'  => '邀请码不存在',
    		];
    	}

    	if ($row->use_uid) {
    		return [
    			'code' => 10002,
    			'msg'  => '邀请码已被使用',
    		];
    	}

    	if (\Carbon\Carbon::now() > $row->expired_at) {
    		return [
    			'code' => 10003,
    			'msg'  => '邀请码已过期',
    		];
    	}

    	return [
			'code' => 10000,
			'msg'  => '激活码可以使用',
		];
	}

    public function createUser()
    {
        return $this->belongsTo(User::class, 'create_uid', 'id');
    }

    public function useUser()
    {
        return $this->belongsTo(User::class, 'use_uid');
    }

    /**
     * 通过状态获取邀请码列表 0为全部，1为已使用 2为未使用
     */
    public function getCodesByType($cuid, $type)
    {
        $query = $this->where('create_uid', '=', $cuid);
        if ($type == 1) {
            $query = $query->where('use_uid', '<>', '');
        } elseif ($type == 2) {
            $query = $query->where('use_uid', '=', '');
        }

        $codes = $query->paginate(5);

        $codes->each(function ($item, $i) {
            if ($item->use_uid) {
                $item->use_uname = $item->useUser->username;
                $item->stat = 1; // 已使用
            } else {
                $item->stat = $item->expired_at < \Carbon\Carbon::now() ? 2 : 3; // 2已过期 3未使用
            }
        });

        return $codes;
    }
}