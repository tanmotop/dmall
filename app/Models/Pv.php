<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/19
 * Time: 21:58
 * Function:
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Pv extends Model
{
    protected $table = 'pv';

    public function getPvConf()
    {
        $pvs = $this->select('target_pv', 'percent')->get();

        $pvs->map(function (&$item, $key) {
            $item->target_pv *= 10000;
            $item->percent /= 100;
        });

        return $pvs;
    }

    /**
     * @param $pvConf
     * @param $totalPv
     * @return int
     */
    public function getBonus(Collection $pvConf, $totalPv)
    {
        $percent = 0;
        foreach ($pvConf as $pv) {
            if ($totalPv >= $pv->target_pv) {
                $percent = $pv->percent;
                continue;
            }

            break;
        }

        return $totalPv * $percent;
    }
}