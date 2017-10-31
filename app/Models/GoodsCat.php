<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Goods;
use App\Models\GoodsPrice;

class GoodsCat extends Model
{

    /**
     * @var string
     */
    protected $table = 'goods_cats';

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function prices()
    {
        return $this->hasManyThrough(GoodsPrice::class, Goods::class, 'goods_id', 'goods_cat_id');
    }

    public function scopeEnable($query)
    {
        return $query->where('status', 1);
    }

    public function getIdNameArray()
    {
        $cats = $this->enable()->get();
        $arr = ['0' => '请选择'];
        foreach ($cats as $cat) {
            $arr[$cat->id] = $cat->name;
        }

        return $arr;
    }
}
