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
}
