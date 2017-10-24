<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_levels';

    public function getLevelNameArray()
    {
    	foreach ($this->all() as $item) {
            $levels[$item->id] = $item->name;
        }

        return $levels;
    }
}
