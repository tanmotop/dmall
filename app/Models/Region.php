<?php

namespace App\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use App\Models\Freight;

class Region extends Model
{
    use ModelTree, AdminBuilder;

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('sort');
        $this->setTitleColumn('name');
    }

    public function getAllIdNameArray()
    {
        $all = $this->all();

        ///
        $arr = [];
        foreach ($all as $item) {
            $arr[$item->id] = $item->name;
        }

        ///
        return $arr;
    }

    public function getRegionIdNameArray($parentId, $level)
    {
        $regs = $this->where([
            ['level', '=', $level],
            ['parent_id', '=', $parentId]
        ])->get();

        foreach ($regs as $reg) {
            $arr[$reg->id] = $reg->name;
        }

        return $arr;
    }

    // public function freight()
    // {
    //     return $this->belongsTo(Freight::class);
    // }

    /**
     * 获取省份 id => name 形式
     * 
     * @return array
     */
    public function getProvinceIdNameArray()
    {
        $regs = $this->where('level', '=', 1)->get();
        $arr = ['0' => '请选择'];
        foreach ($regs as $reg) {
            $arr[$reg->id] = $reg->name;
        }

        return $arr;
    }

    public function getChildrenByPid($pid)
    {
        $regs = $this->where('parent_id', '=', $pid)->get();
        $arr = [];
        foreach ($regs as $reg) {
            $arr[$reg->id] = $reg->name;
        }

        return $arr;
    }

    public function getRegionByLevel($level = 0)
    {
        $res = $this->where('level', '<=', $level)->get();
        $arr = [];
        foreach ($res as $region)
        {
            $arr[$region->id] = $region->name;
        }

        return $arr;
    }

    public function getLevelById($id)
    {
        $region = $this->find($id);
        return $region->level;
    }
}
