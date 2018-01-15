<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/9
 * Time: 11:46
 * Function:
 */

namespace App\Http\Controllers\User;


use App\Http\Requests\CustomerRequest;
use App\Models\CustomerAddress;
use App\Models\Region;
use Illuminate\Http\Request;

class CustomerController
{
    protected $customerModel;
    protected $regionModel;

    public function __construct(CustomerAddress $customerAddress, Region $region)
    {
        $this->customerModel = $customerAddress;
        $this->regionModel = $region;
    }

    public function index(Request $request)
    {
        $title = '客户资料';
        $uid = session('auth_user')->id;
        $users = $this->customerModel->getCustomerInfoList($uid);
        $provinces = $this->regionModel->getProvinceIdNameArray();
        $provincesJson = json_encode($provinces);

        // 获取更多/分页数据 => 直接返回json
        if ($request->has('dataType') && $request->dataType == 'json') {
            return $users;
        }

        return view('user.customer', compact('title', 'users', 'provinces', 'provincesJson'));
    }

    public function update(CustomerRequest $request)
    {
        $id = $request->get('id');
        $data = $request->all(['name', 'phone', 'province', 'city', 'area', 'address']);

        $customer = $this->customerModel->find($id);
        $customer->name = $data['name'];
        $customer->phone = $data['phone'];
        $customer->province_id = $data['province'];
        $customer->city_id = $data['city'];
        $customer->area_id = $data['area'];
        $customer->address = $data['address'];
        $customer->save();

        return response()->json(['code' => 10000, 'msg' => 'success']);
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $res = $this->customerModel->find($id)->delete();

        if ($res) {
            return response()->json(['code' => 10000, 'msg' => 'success']);
        }
        else {
            return response()->json(['code' => 10001, 'msg' => 'fail']);
        }
    }

    public function region(Request $request)
    {
        $parentId = $request->parent_id;
        $level = $request->level;

        $data = $this->regionModel->getRegionIdNameArray($parentId, $level);

        return response()->json(['code' => 10000, 'data' => $data]);
    }
}