<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $guarded = [];

    /**
     * 获取列表
     */
    public function getList($typeId, $keyword)
    {
        if ($typeId) {
            $query = $this;
        } else {
            $list = $this->where('type_id', $typeId);
        }
        if ($keyword) {
            $query = $query->where('title', 'like', '%'.$keyword.'%');
        }
        $list = $query->paginate(10);

        $list->each(function($item, $i) {
            // 获取文件后缀
            $arr = explode('.', $item->attach);
            $item->file_ext = strtolower(end($arr));
            if (!in_array($item->file_ext, ['word', 'ppt', 'pdf', 'excel'])) {
                $item->file_ext = 'other';
            }
            // 文件地址
            $item->attach = env('APP_URL') . '/uploads/'. $item->attach;
        });

        return $list;
    }
}
