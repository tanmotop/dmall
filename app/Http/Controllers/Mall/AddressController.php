<?php

namespace App\Http\Controllers\Mall;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Freight;
use App\Models\Cart;
use App\Models\CustomerAddress;

class AddressController extends Controller
{
    private $regionModel;
    private $freightModel;
    private $cartModel;
    private $customerModel;

    public function __construct(Region $region, Freight $freight, Cart $cart, CustomerAddress $customer)
    {
        $this->regionModel   = $region;
        $this->freightModel  = $freight;
        $this->cartModel     = $cart;
        $this->customerModel = $customer; 
    }

    /**
     * 我的客户列表
     * 
     * @return json
     */
    public function customers()
    {
        $uid = session('auth_user')->id;
        $list = $this->customerModel->getList($uid);

        return $list->toArray();
    }

    /**
     * 三级联动
     */
    public function regions(Request $request)
    {
        $pid = $request->pid;

        return $this->regionModel->getChildrenByPid($pid);
    }

    public function freights(Request $request)
    {
        $courierId = $request->courier_id;
        $regionId = $request->region_id;
        $weight = $request->weight;
        $freight = $this->freightModel->calculate($courierId, $regionId, $weight);

        return $freight;
    }
}
