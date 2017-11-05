<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Goods;
use App\Models\GoodsAttrPrice;
use App\Models\OrderGoods;

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
}
