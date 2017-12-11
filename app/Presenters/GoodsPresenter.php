<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/12/8
 * Time: 15:37
 * Function:
 */

namespace App\Presenters;


class GoodsPresenter
{
    public function isInvaild($goods)
    {
        if ($goods->status == 0 || $goods->status == 2) {
            return <<<EOF
<span style="color: #f00;">无效</span>
<input type="hidden" name="invalid_cart_ids" value="$goods->cart_id">
EOF;
        }

        return '';
    }
}