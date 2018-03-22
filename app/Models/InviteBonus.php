<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/20
 * Time: 11:45
 * Function:
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class InviteBonus extends Model
{
    protected $table = 'invite_bonus';

    /**
     * @param User $parent
     * @param User $child
     * @return array
     */
    public function getBonus(User $parent, User $child)
    {
        $bonus = [];
        $rules = $this->rules();
        if ($parent->parent_id > 0 && $parent->level == User::$LEVEL_DEALER) {
            $grandFather = User::find($parent->parent_id);
            if ($grandFather && $grandFather->level == User::$LEVEL_DIAMOND) {
                /// 这孙子还有爷爷而且还是钻石级，则他爷爷也有奖金
                $key = sprintf('%s,%s,%s', $grandFather->level, $parent->level, $child->level);

                if (array_key_exists($key, $rules)) {
                    $bonus[$grandFather->id] = $rules[$key];
                }
            }
        }

        $key = sprintf('%s,%s', $parent->level, $child->level);
        if (array_key_exists($key, $rules)) {
            $bonus[$parent->id] = $rules[$key];
        }

        return $bonus;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = $this->all();

        $res = [];
        foreach ($rules as $rule) {
            $res[$rule->rule] = $rule->bonus;
        }

        return $res;
    }
}