<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Freight;

class Courier extends Model
{
    public function freight()
    {
        return $this->belongsTo(Freight::class);
    }

    /**
     * 获取id => name 形式
     * 
     * @return array
     */
    public function getIdNameArray()
    {
       $curs = $this->get();
        $arr = ['0' => '请选择'];
        foreach ($curs as $cur) {
            $arr[$cur->id] = $cur->name;
        }

        return $arr;
    }
}
