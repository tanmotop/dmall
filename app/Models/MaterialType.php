<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialType extends Model
{
    protected $guarded = [];

    public function scopeEnable($query)
    {
        return $query->where('status', 1);
    }

    public function getIdNameArray()
    {
        $cats = $this->where('status', 1)->get();
        $arr = ['0' => '请选择'];
        foreach ($cats as $cat) {
            $arr[$cat->id] = $cat->name;
        }

        return $arr;
    }
}
