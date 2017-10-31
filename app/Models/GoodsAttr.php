<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Goods;
use App\Models\GoodsAttrPrice;

class GoodsAttr extends Model
{
	 protected $fillable = ['name', 'price', 'stock', 'weight', 'pv', 'user_prices', 'created_at', 'updated_at'];

	/**
     * @var string
     */
    protected $table = 'goods_attrs';

    public function goods()
    {
    	return $this->belongsTo(Goods::class, 'goods_id');
    }

    public function prices()
    {
        return $this->hasMany(GoodsAttrPrice::class, 'goods_attr_id');
    }
}
