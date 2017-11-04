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
     * 计算运费
     */
    public function calculate($regionId, $weight)
    {
        $freight = $this->where('region_id', '=', $regionId)->first();
        if ($weight < $freight->norm_weight * 1000) {
            return $freight->norm_price;
        }
        return $freight->over_first_price + (ceil($weight/1000) - 1) * $freight->over_next_price;
    }
}
