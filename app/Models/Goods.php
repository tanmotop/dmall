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

    public function getSaleGoodsList($catId)
    {
        $fields = [
            'goods.id',
            'goods.name',
            'goods.cat_id',
            'goods.sn',
            'goods.logo',
            'goods_attrs.id as attr_id',
            'goods_attrs.pv',
            'goods_attrs.price',
            'goods_attrs.user_prices',
            'goods_attrs.name as attr_name',
            'goods_attrs.stock',
            'goods_attrs.weight',
        ];
        $query = $this->join('goods_attrs', 'goods.id', '=', 'goods_attrs.goods_id')
            ->where('goods.status', '=', 1)
            ->orderBy('goods.sort', 'asc')
            ->select($fields);
        if ($catId != 0) {
            $query = $query->where('goods.cat_id', '=', $catId);
        }
        $list = $query->paginate(4);

        $list->each(function($item, $i) {
            $item->user_prices = json_decode($item->user_prices, true);
        });

        return $list;
    }
}
