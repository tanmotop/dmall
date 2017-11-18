<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $guarded = [];

    public function scopeEnable($query)
    {
        return $query->where('status', 1);
    }

    /**
     * 获取列表
     */
    public function getList($keyword)
    {
        $query = $this->where('status', 1);
        if ($keyword) {
            $query = $query->where('title', 'like', '%'.$keyword.'%');
        }
        $list = $query->paginate(10);

        return $list;
    }
}
