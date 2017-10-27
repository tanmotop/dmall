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
}
