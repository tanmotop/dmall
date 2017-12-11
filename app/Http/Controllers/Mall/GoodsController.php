<?php

namespace App\Http\Controllers\Mall;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GoodsCat;
use App\Models\Goods;
use App\Models\GoodsAttr;
use App\Models\UserLevel;
use App\Models\Cart;

class GoodsController extends Controller
{
	private $goodsModel;
	private $catModel;
	private $attrModel;
    private $cartModel;

	public function __construct(Goods $goods, GoodsCat $cat, GoodsAttr $attr, Cart $cart)
	{
		$this->goodsModel = $goods;
		$this->catModel   = $cat;
		$this->attrModel  = $attr;
        $this->cartModel  = $cart;
	}

    /**
     * 商品列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $catId = $request->input('cat_id', 0);
        $goodsList = $this->goodsModel->getSaleGoodsList($catId, $keyword);

        // 获取更多/分页数据 => 直接返回json
        if ($request->has('dataType') && $request->dataType == 'json') {
            return $goodsList;
        }

    	$cats = $this->catModel->enable()->get(['id', 'name']);
        foreach ($cats as $key => $cat) {
            if ($cat->id == $catId) {
                $catPlace = $key + 1;
            }
        }
        list($userLevels, $myLevel) = $this->getUserLevelsInfo();

        return view('mall/goods', [
        	'title'      => '商品',
            'catId'      => $catId,
            'catPlace'   => isset($catPlace) ? $catPlace : 0,
        	'cats'       => $cats,
            'goodsList'  => $goodsList,
            'userLevels' => $userLevels,
            'myLevel'    => $myLevel,
            'keyword'    => $keyword,
        ]);
    }

    public function getUserLevelsInfo()
    {
        if (!session()->has('user_levels')) {
            (new UserLevel)->saveUserLevelsToSession();
        }
        $userLevelsInfo = session('user_levels');
        $levels = $userLevelsInfo['levels'];
        if (isset($levels[0])) {
            unset($levels[0]);
        }
        // 如果用户是顶级，则购买的价格以第一级的价格计算
        if (!($myLevel = session('auth_user')->level)) {
            $myLevel = $userLevelsInfo['first_level'];
        }
        
        return [$levels, $myLevel];
    }

    /**
     * 添加商品到购物车
     * @param Request $request
     *
     * @return array
     */
    public function addToCart(Request $request)
    {
        $selectAttrs = json_decode($request->selectGoods, true);
        $flag = $this->cartModel->addToCart(session('auth_user')->id, $selectAttrs);

        if ($flag == true) {
            return [
                'code' => 10000,
                'msg'  => 'Success',
            ];
        } else {
            return [
                'code' => 10002,
                'msg'  => 'Error',
            ];
        }
    }
}
