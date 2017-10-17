<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GoodsCat;
use App\Models\GoodsAttr;

class Goods extends Model
{
	/**
     * @var string
     */
    protected $table = 'goods';

    public function cat()
    {
    	return $this->hasOne(GoodsCat::class, 'id', 'cat_id');
    }

    public function attrs()
    {
    	return $this->hasMany(GoodsAttr::class, 'goods_id');
    }

    public function scopeEnable($query)
    {
        return $query->where('status', 1);
    }

    public function getSaleGoodsList()
    {
        $fields = [
            'goods.id', 'goods.name', 'goods.sn', 'goods_attrs.price', 'goods_attrs.name as attr_name', 'goods_attrs.stock', 'goods_attrs.weight'
        ];
        return $this->join('goods_attrs', 'goods.id', '=', 'goods_attrs.goods_id')
            ->where('goods.status', '=', 1)
            ->orderBy('goods.sort', 'asc')
            ->get($fields);
    }
}
