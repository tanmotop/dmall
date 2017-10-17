<?php

namespace App\Http\Controllers\Mall;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GoodsCat;
use App\Models\Goods;
use App\Models\GoodsAttr;

class GoodsController extends Controller
{
	private $goodsModel;
	private $catModel;
	private $attrModel;

	public function __construct(Goods $goods, GoodsCat $cat, GoodsAttr $attr)
	{
		$this->goodsModel = $goods;
		$this->catModel   = $cat;
		$this->attrModel  = $attr;
	}

    public function index()
    {
    	$cats = $this->catModel->enable()->get(['id', 'name']);
    	// dd($cats);
        return view('mall/goods', [
        	'title' => '商品',
        	'cats'  => $cats,
        ]);
    }

    /**
     * POST 获取商品数量
     * 
     * @return json
     */
    public function getGoodsList()
    {
    	$goodsList = $this->goodsModel->getSaleGoodsList()->toArray();

    	foreach ($goodsList as & $goods) {
    		$goods['pv'] = 10;
    		// $goods['']
    	}

    	return [
    		'code' => 'success',
    		'data' => $goodsList,
    	];
    }
}
