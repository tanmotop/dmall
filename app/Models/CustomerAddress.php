<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    public function getList($uid)
    {
        $list = $this->where('user_id', '=', $uid)
            ->get();

        return $list;
    }
}
