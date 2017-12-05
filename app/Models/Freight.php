<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Courier;
use App\Models\Region;

class Freight extends Model
{
    public function courier()
    {
    	return $this->hasOne(Courier::class, 'id', 'courier_id');
    }

    public function region()
    {
    	return $this->hasOne(Region::class, 'id', 'region_id');
    }

    /**
     * @param $courierId
     * @param $regionId
     * @param $weight
     * @return array
     */
    public function calculate($courierId, $regionId, $weight)
    {
        $freight = $this->where([
            ['courier_id', '=', $courierId],
            ['region_id', '=', $regionId]
        ])->first();

        if (empty($freight))
            return ['code' => 10001];

        ///
        if ($weight < $freight->norm_weight * 1000) {
            return ['code' => 10000, 'freight' => $freight->norm_price];
        }

        ///
        return ['code' => 10000, 'freight' => $freight->over_first_price + (ceil($weight/1000) - 1) * $freight->over_next_price];
    }
}
