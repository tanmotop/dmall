<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $guarded = [];
    
    public function getList($uid)
    {
        $list = $this->where('user_id', '=', $uid)
            ->get();

        return $list;
    }

    public function saveAddress($data)
    {
        $uid = session('auth_user')->id;
        $address = $this->where('user_id', '=', $uid)
            ->where('province_id', '=', $data['user_province'])
            ->where('city_id', '=', $data['user_city'])
            ->where('area_id', '=', $data['user_area'])
            ->where('name', '=', $data['user_name'])
            ->where('tel', '=', $data['user_tel'])
            ->where('address', '=', $data['user_address'])
            ->first();
        if ($address) return false;

        $data = [
            'user_id'     => $uid,
            'province_id' => $data['user_province'],
            'city_id'     => $data['user_city'],
            'area_id'     => $data['user_area'],
            'name'        => $data['user_name'],
            'tel'         => $data['user_tel'],
            'address'     => $data['user_address'],
        ];

        $this->create($data);
    }
}
